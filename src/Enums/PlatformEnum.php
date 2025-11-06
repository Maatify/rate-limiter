<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 00:04
 * Project: maatify/rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Enums;

enum PlatformEnum: string
{
    case WEB = 'web';
    case MOBILE = 'mobile';
    case API = 'api';
    case ADMIN = 'admin';
}