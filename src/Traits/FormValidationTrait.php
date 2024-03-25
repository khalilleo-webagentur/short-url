<?php

declare(strict_types=1);

namespace App\Traits;

use DateTime;
use DateTimeInterface;

trait FormValidationTrait
{
    private function validate(?string $input, bool $isRaw = false): ?string
    {
        if (empty($input)) {
            return null;
        }

        return $this->escape($input);
    }

    private function validateEmail(?string $input): ?string
    {
        if (empty($input)) {
            return null;
        }

        $input = strtolower($input);
        $input = $this->limitWords($input, 200);
        $input = $this->escape($input);

        return filter_var($input, FILTER_VALIDATE_EMAIL) ? $input : null;
    }

    private function validateURL(?string $input): ?string
    {
        if (empty($input)) {
            return null;
        }

        if (!preg_match("@^https?://@", $input)) {
            $input = 'https://' . $input;
        }

        $input = $this->escape($input);

        return filter_var($input, FILTER_VALIDATE_URL) ? $input : null;
    }

    private function validateCheckbox(?string $input): bool
    {
        if (empty($input)) {
            return false;
        }

        return ($this->escape($input) === strtolower('on'));
    }

    private function validateTextarea(?string $input, bool $isRaw = false): ?string
    {
        if (empty($input)) {
            return null;
        }

        if ($isRaw) {
            return nl2br($input);
        }

        return $this->limitWords($this->escape($input), 50000);
    }

    private function validateIpV4(?string $input): ?string
    {
        if (!preg_match('/^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$/', $input)) {
            return null;
        }

        return $input;
    }

    private function validateDatetime(?string $datetime): DateTimeInterface
    {
        $format = 'Y-m-d H:i';

        return DateTime::createfromformat($format, $datetime) === false
            ? new DateTime()
            : DateTime::createfromformat($format, $datetime);
    }

    // At least one lowercase, uppercase, numeric alphabetic character and its length has to be at least 8 characters
    private function passwordError(?string $input, bool $allowSimplePassword = false): ?string
    {
        if ($input === null || strlen($input) < '8') {
            // Password must contain at least 8 characters.
            return "Das Passwort muss mindestens 8 Zeichen enthalten.";
        }

        if (true === $allowSimplePassword) {
            return null;
        }

        $password = $this->escape($input);
        $error = null;

        if (!preg_match("#\d+#", $password)) {
            // Password must contain at least 1 number.
            $error = "Das Passwort muss mindestens 1 Zahl enthalten.";
        } elseif (!preg_match("#[A-Z]+#", $password)) {
            // Password must contain at least 1 capital letter.
            $error = "Das Passwort muss mindestens 1 Großbuchstaben enthalten.";
        } elseif (!preg_match("#[a-z]+#", $password)) {
            // Password must contain at least 1 lowercase letter.
            $error = "Das Passwort muss mindestens einen Kleinbuchstaben enthalten.";
        } elseif (!preg_match('/[\'$_+%*?=@#~!]/', $password)) {
            // Password must contain at least 1 special character.
            $error = "Das Passwort muss mindestens 1 Sonderzeichen enthalten.";
        }

        return $error;
    }

    private function usernameError(?string $input): ?string
    {
        $error = null;

        if (empty($input)) {
            // Username is required
            $error = 'Benutzername ist erforderlich.';
        } elseif (strlen($input) < '3') {
            // Username is to short. Min 3 chars.
            $error = 'Der Benutzername ist zu kurz. Mindestens 3 Zeichen.';
        } elseif (strlen($input) > '20') {
            // Username is to long. Max 20 chars.
            $error = 'Der Benutzername ist zu lang. Maximal 20 Zeichen.';
        } elseif (preg_match('/[\'^$%&*()}{@#~?>,<|=-]/', $input)) {
            // Username contains invalid chars. Please use underscores.
            $error = 'Benutzername enthält ungültige Zeichen. Bitte verwenden Sie Unterstriche.';
        }

        return $error;
    }

    private function validateNumber(null|string|int $input): int
    {
        if (empty($input)) {
            return 0;
        }

        return (int) $this->escape((string) $this->limitWords($input, 10));
    }

    private function validateFloat(mixed $input): float
    {
        if (!is_numeric((float) $input) || !filter_var((float) $input, FILTER_VALIDATE_FLOAT)) {
            return 0.0;
        }

        return (float) $input;
    }

    private function validateArray(?array $inputs): array
    {
        $results = [];

        if (!empty($inputs)) {
            foreach ($inputs as $input) {
                $results[] = $this->validate($input);
            }
        }

        return $results;
    }


    private function escape(?string $input): ?string
    {
        if (empty($input)) {
            return null;
        }

        $temp = trim($input);
        $temp = stripslashes($temp);

        return htmlspecialchars($temp);
    }

    private function limitWords(?string $input, int $length): string
    {
        $inputAsInt = (int) $input;

        if ($inputAsInt >= 2147483648) {
            return '';
        }

        if (!empty($input) && strlen($input) > $length) {
            $input = substr($input, 0, $length);
        }

        return $input;
    }

    private function validateUsernameAndReplaceSpace(?string $input, string $replacement = '_'): ?string
    {
        if (empty($input)) {
            return null;
        }

        return $this->escape(str_replace(' ', $replacement, $input));
    }
}
