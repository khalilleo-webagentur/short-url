<?php

declare(strict_types=1);

namespace App\Helper;

final class AppHelper
{
    public const DEFAULT_ALIAS_LENGTH = 8;

    public const DEFAULT_LINKS_OPTION_EXPORT_AS_JSON = 'json';

    public const AVAILABLE_LINKS_EXPORT_OPTIONS = [
        'csv',
        'json',
        'pdf'
    ];

    public const DEFAULT_AVATAR_NAME = 'avatar-160x160.png';

    public const MAX_LIMIT_TO_EXTRACT_DOMAINS_FROM_URLS = 1500;
}