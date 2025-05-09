<?php

declare(strict_types=1);

namespace App\Mails\Admin;

use App\Mails\AbstractMail;
use App\Mails\MailInterface;
use App\Service\ConfigService;
use App\Service\Dev\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class NotifyAbuseLinkMail extends AbstractMail implements MailInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly ConfigService   $configService
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
            ->subject('k24.ing | New Notification Webmaster')
            ->htmlTemplate('mails/admin/notify_abuse_link.html.twig')
            ->textTemplate('mails/admin/notify_abuse_link.txt.twig')
            ->context([
                'username' => $webmasterName
            ]);

        Mailer:: catch(sprintf('Notification mail has been sent to [%s]', $webmasterEmail));

        $this->mailer->send($email);
    }
}
