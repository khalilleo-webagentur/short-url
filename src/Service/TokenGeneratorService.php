<?php

declare(strict_types=1);

namespace App\Service;

use App\Helper\AppHelper;
use Khalilleo\TokenGen\Token;

final readonly class TokenGeneratorService
{
    private Token $tokenGen;

    public function __construct()
    {
        $this->tokenGen = new Token();
    }

    public function randomTokenForLink(): string
    {
        return $this->tokenGen->getRandomToken(AppHelper::DEFAULT_ALIAS_LENGTH);
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
