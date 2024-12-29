<?php

declare(strict_types=1);

namespace App\Mails\Admin;

use App\Mails\AbstractMail;
use App\Mails\MailInterface;
use App\Service\ConfigService;
use App\Service\Dev\Mailer;
use App\Service\MonologService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class NotifiyAbuseLinkMail extends AbstractMail implements MailInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly ConfigService   $configService,
        private readonly MonologService  $monolog
    ) {
    }

    public function send(...$context): void
    {
        $webmasterName = $this->configService->getParameter('webMasterName');

        $webmasterEmail = $this->configService->getParameter('webMasterEmail');

        $email = (new TemplatedEmail())
            ->from(
                new Address(
                    $this->configService->getParameter('noReply'),
                    $this->configService->getParameter('appName')
                )
            )
            ->to(new address($webmasterEmail, $webmasterName))
            ->subject('New Notification Webmaster')
            ->htmlTemplate('mails/admin/notify_abuse_link.html.twig')
            ->context([
                'username' => $webmasterName
            ]);

        Mailer:: catch(sprintf('Notification mail has been sent to [%s]', $webmasterEmail));

        $this->mailer->send($email);
    }
}
