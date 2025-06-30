<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\User;
use App\Service\ConfigService;
use App\Service\SocialProfileSettingService;
use DateTime;
use DateTimeInterface;
use Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigHelper extends AbstractExtension
{
    public function __construct(
        private readonly ConfigService               $configService,
        private readonly SocialProfileSettingService $socialProfileSettingService
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('hostFromUrl', [$this, 'hostFromUrl']),
            new TwigFunction('numberNotation', [$this, 'numberNotation']),
            new TwigFunction('profilesTree', [$this, 'profilesTree']),
            new TwigFunction('randomNumbers', [$this, 'randomNumbers']),
            new TwigFunction('userSocialLinkMainName', [$this, 'userSocialLinkMainName']),
            new TwigFunction('hash', [$this, 'hash']),
            new TwigFunction('timeAgo', [$this, 'timeAgo']),
            new TwigFunction('formatSizeUnits', [$this, 'formatSizeUnits']),
            new TwigFunction('dateTime', [$this, 'dateTime']),
            new TwigFunction('replaceLinkWithHref', [$this, 'replaceLinkInText']),
            new TwigFunction('count', [$this, 'getCount']),
            new TwigFunction('appName', [$this, 'getAppName']),
            new TwigFunction('year', [$this, 'getYear']),
            new TwigFunction('circle', [$this, 'circle']),
            new TwigFunction('faCircle', [$this, 'faCircle']),
            new TwigFunction('checkCircle', [$this, 'checkCircle']),
            new TwigFunction('faThumbsUp', [$this, 'getThumbsUp']),
            new TwigFunction('faThumbsDown', [$this, 'getThumbsDown']),
            new TwigFunction('appAuthor', [$this, 'getAppAuthor']),
            new TwigFunction('madeBy', [$this, 'getMadeBy']),
            new TwigFunction('version', [$this, 'getVersion']),
        ];
    }

    public function hostFromUrl(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (empty($host)) {
            return '';
        }

        return str_ireplace('www.', '', $host);
    }


    public function numberNotation(?int $number): string|int
    {
        if ($number && $number > 1000) {

            $x = round($number);
            $xArray = explode(',', number_format($x));
            $xUnits = ['K+', 'M+', 'B+', 'T+'];
            $xCountUnits = count($xArray) - 1;
            $xDisplay = $x;
            $xDisplay = $xArray[0] . ((int)$xArray[1][0] !== 0 ? '.' . $xArray[1][0] : '');
            $xDisplay .= $xUnits[$xCountUnits - 1];

            return $xDisplay;
        }

        return $number;
    }

    public function profilesTree(): string
    {
        $socialProfileSetting = $this->socialProfileSettingService->getById(1);

        return $socialProfileSetting ? $socialProfileSetting->getMainName() : '';
    }

    public function randomNumbers(): int
    {
        return random_int(11111111, 99999999);
    }

    public function userSocialLinkMainName(User $user): string
    {
        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        return $socialProfileSetting->getMainName();
    }

    public function hash(string $text): string
    {
        return sha1($text);
    }

    public function timeAgo(DateTimeInterface $datetime, bool $fullDateTime = false): string
    {
        $now = new DateTime();
        $ago = '';

        try {
            $ago = new DateTime($datetime->format('Y-m-d H:i:s'));
        } catch (Exception $e) {
        }

        $differentTime = $now->diff($ago);

        $differentTime->w = floor($differentTime->d / 7);
        $differentTime->d -= $differentTime->w * 7;

        $components = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        foreach ($components as $key => &$value) {
            if ($differentTime->$key) {
                $value = $differentTime->$key . ' ' . $value . ($differentTime->$key > 1 ? 's' : '');
            } else {
                unset($components[$key]);
            }
        }

        unset($value);

        if (!$fullDateTime) {
            $components = array_slice($components, 0, 1);
        }

        return $components ? implode(', ', $components) . ' ago' : 'just now';
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function dateTime(string $format = 'd.m.Y'): string
    {
        return new DateTime('now')->format($format);
    }

    public function getStripTags(string $text): string
    {
        return strip_tags($text);
    }

    public function replaceLinkInText(string $text): string
    {
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\(\w+\)|([^,[:punct:]\s]|/))#', $text, $matches);

        if (count($matches[0]) <= 0) {
            return $text;
        }

        $result = '';

        foreach ($matches[0] as $link) {
            $result = str_replace($link, '<a href="' . $link . '" target="_blank">' . $link . '</a>', $text);
        }

        return $result;
    }

    public function getCount(mixed $obj): int
    {
        if (is_countable($obj)) {
            return count($obj);
        }

        return 0;
    }

    public function getYear(): string
    {
        return new DateTime()->format('Y');
    }

    public function circle(string $color): void
    {
        echo "<span class='bi bi-circle-fill $color'></span>";
    }

    public function faCircle(string $color): void
    {
        echo "<span class='fa fa-circle fs-sm $color'></span>";
    }

    public function checkCircle(string $color): void
    {
        echo "<span class='bi bi-check2-circle fs-6 $color'></span>";
    }

    public function getThumbsUp(): void
    {
        echo "<span class='fa fa-thumbs-up fa-sm text-success'></span>";
    }

    public function getThumbsDown(?string $color = null): void
    {
        $color = $color ?? 'text-danger';

        echo "<span class='fa fa-thumbs-down fa-sm $color'></span>";
    }

    public function getAppName(): string
    {
        return $this->configService->getParameter('appName');
    }

    public function getMadeBy(): string
    {
        return $this->configService->getParameter('poweredBy');
    }

    public function getAppAuthor(): string
    {
        return $this->configService->getParameter('appAuthor');
    }

    public function getVersion(): string
    {
        return 'v' . $this->configService->getParameter('appVersion');
    }
}
