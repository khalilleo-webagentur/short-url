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
}