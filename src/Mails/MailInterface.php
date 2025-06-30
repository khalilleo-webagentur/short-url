<?php

declare(strict_types=1);

namespace App\Mails;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

interface MailInterface
{
    /**
     * @throws TransportExceptionInterface
     */
    public function send(...$contents): void;
}