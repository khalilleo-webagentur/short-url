<?php

declare(strict_types=1);

namespace App\Mails;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

abstract class AbstractMail
{
    /**
     * @throws TransportExceptionInterface
     */
    abstract protected function send(...$contents): void;
}