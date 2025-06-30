<?php

declare(strict_types=1);

namespace App\Helper;

final class AppHelper
{
    public const int DEFAULT_ALIAS_LENGTH = 8;

    public const string DEFAULT_LINKS_OPTION_EXPORT_AS_JSON = 'json';

    public const array AVAILABLE_LINKS_EXPORT_OPTIONS = [
        'csv',
        'json',
        'pdf'
    ];

    public const string DEFAULT_AVATAR_NAME = 'avatar-160x160.png';

    public const int MAX_LIMIT_TO_EXTRACT_DOMAINS_FROM_URLS = 1500;
}