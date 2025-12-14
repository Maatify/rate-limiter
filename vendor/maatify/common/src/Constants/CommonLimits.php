<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:22
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Constants;

/**
 * ⚙️ **CommonLimits**
 *
 * 🧩 Centralized constant definitions for global system limits,
 * default configurations, and safety constraints used throughout
 * the Maatify ecosystem.
 *
 * ✅ Typical usages:
 * - Pagination and API query restrictions
 * - Authentication and security parameter controls
 * - System configuration consistency
 *
 * @package Maatify\Common\Constants
 *
 * @example
 * ```php
 * use Maatify\Common\Constants\CommonLimits;
 *
 * $pageSize = min($userInput, CommonLimits::MAX_PAGE_SIZE);
 *
 * if (strlen($password) < CommonLimits::MIN_PASSWORD_LENGTH) {
 *     throw new Exception('Password too short');
 * }
 * ```
 */
final class CommonLimits
{
    /** 📄 Maximum number of items per page for pagination queries */
    public const int MAX_PAGE_SIZE = 100;

    /** 🔐 Minimum required password length for authentication */
    public const int MIN_PASSWORD_LENGTH = 8;

    /** ⏳ Default expiration time (in seconds) for temporary tokens */
    public const int TOKEN_EXPIRY_SECONDS = 3600;
}
