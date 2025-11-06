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

/**
 * 🎯 Enum PlatformEnum
 *
 * 🧩 Purpose:
 * Defines supported platforms for applying rate-limiting rules.
 * Each case represents a distinct environment or client type where
 * rate limits can differ (e.g., `web` vs `api` vs `mobile`).
 *
 * ⚙️ Usage:
 * ```php
 * use Maatify\RateLimiter\Enums\PlatformEnum;
 *
 * $platform = PlatformEnum::API;
 * echo $platform->value; // "api"
 * ```
 *
 * ✅ Common use cases:
 * - Applying different rate limits per platform.
 * - Logging and analytics grouping.
 * - Segregating user behaviors in rate-limiter backends.
 *
 * @package Maatify\RateLimiter\Enums
 */
enum PlatformEnum: string
{
    /** 🌐 Standard web clients (e.g., browsers). */
    case WEB = 'web';

    /** 📱 Native mobile apps (iOS/Android). */
    case MOBILE = 'mobile';

    /** ⚙️ API integrations or external services. */
    case API = 'api';

    /** 🧑‍💼 Administrative dashboards or back-office tools. */
    case ADMIN = 'admin';
}
