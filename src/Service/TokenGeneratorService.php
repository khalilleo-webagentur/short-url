<?php

declare(strict_types=1);

namespace App\Service;

use Khalilleo\TokenGen\Token;

final class TokenGeneratorService
{
    private readonly Token $tokenGen;

    public function __construct()
    {
        $this->tokenGen = new Token();
    }

    public function randomToken(): string
    {
        return $this->tokenGen->getRandomToken(8);
    }

    public function randomTokenForVerification(): string
    {
        return $this->tokenGen->getRandomToken(32);
    }
}
