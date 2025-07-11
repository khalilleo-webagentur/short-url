<?php

declare(strict_types=1);

namespace App\Mails\Account;

use App\Mails\AbstractMail;
use App\Mails\MailInterface;
use App\Service\ConfigService;
use App\Service\Dev\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class HandelTwoFactorAuthMail extends AbstractMail implements MailInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly ConfigService   $configService
    ) {
    }

    public function send(...$context): void
    {
        [$username, $userEmail, $otp] = $context;

        $email = new TemplatedEmail()
            ->from(
                new Address(
                    $this->configService->getParameter('noReply'),
                    $this->configService->getParameter('appName')
                )
            )
            ->to(new address($userEmail, $username))
            ->subject('k24.ing | Two-Step Verification')
            ->htmlTemplate('mails/account/send_otp.html.twig')
            ->textTemplate('mails/account/send_otp.txt.twig')
            ->context([
                'username' => $username,
                'otp' => $otp,
            ]);

        Mailer:: catch(sprintf('Login Security-code %s for [%s] %s', $otp, $username, $userEmail));

        $this->mailer->send($email);
    }
}
