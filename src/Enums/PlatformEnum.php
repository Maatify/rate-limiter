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

use Maatify\RateLimiter\Contracts\PlatformInterface;

/**
 * 🎯 Enum PlatformEnum
 *
 * 🧩 Purpose:
 * Defines all supported **platform contexts** used in the Maatify rate-limiting system.
 * Each enum case represents a unique environment or access channel
 * where separate rate-limiting policies can apply (e.g., web vs API vs mobile).
 *
 * This enum implements {@see PlatformInterface} for cross-module compatibility,
 * allowing dependency injection and type-safe platform handling across projects.
 *
 * ⚙️ Usage:
 * ```php
 * use Maatify\RateLimiter\Enums\PlatformEnum;
 *
 * $platform = PlatformEnum::MOBILE;
 * echo $platform->value; // "mobile"
 * ```
 *
 * ✅ Common scenarios:
 * - Apply unique rate limits per platform.
 * - Distinguish API vs web usage analytics.
 * - Manage throttling by client type.
 *
 * @package Maatify\RateLimiter\Enums
 */
enum PlatformEnum: string implements PlatformInterface
{
    /** 🌐 Standard web clients (e.g., browsers). */
    case WEB = 'web';

    /** 📱 Native mobile apps (iOS/Android). */
    case MOBILE = 'mobile';

    /** ⚙️ Public or internal API integrations. */
    case API = 'api';

    /** 🧑‍💼 Administrative dashboards or internal panels. */
    case ADMIN = 'admin';

    /**
     * 🧠 Retrieve the string value of the platform enum.
     *
     * 🎯 Provides an interface-compliant accessor that mirrors `$this->value`
     * but ensures consistent behavior across enum and non-enum implementations.
     *
     * @return string The string identifier of the platform (e.g., "web", "api").
     *
     * ✅ Example:
     * ```php
     * echo PlatformEnum::WEB->value(); // "web"
     * ```
     */
    public function value(): string
    {
        // 🔹 Returns the string value of the platform case
        return $this->value;
    }
}
