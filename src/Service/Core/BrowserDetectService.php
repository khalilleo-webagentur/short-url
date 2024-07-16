<?php

declare(strict_types=1);

namespace App\Service\Core;

use Khalilleo\BrowserDetect\UserAgent;

final class BrowserDetectService
{
    public function userAgent(): UserAgent
    {
        return new UserAgent();
    }
}
