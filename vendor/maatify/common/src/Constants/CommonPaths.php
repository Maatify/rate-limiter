<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:21
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Constants;

/**
 * 📁 **CommonPaths**
 *
 * 🧩 Centralized path constants shared across all Maatify libraries.
 * Provides consistent and standardized relative paths used by
 * loggers, caches, configurations, and temporary file handlers.
 *
 * ✅ Common usage examples:
 * - File storage or log directory resolution
 * - Environment-aware file operations
 * - Reusable base paths for service layers
 *
 * @package Maatify\Common\Constants
 *
 * @example
 * ```php
 * use Maatify\Common\Constants\CommonPaths;
 *
 * $logDir = __DIR__ . CommonPaths::LOG_PATH;
 * $cacheFile = __DIR__ . CommonPaths::CACHE_PATH . '/app.cache';
 * ```
 */
final class CommonPaths
{
    /** 🧾 Default directory for application logs */
    public const string LOG_PATH = '/storage/logs';

    /** ⚡ Directory for caching system or user data */
    public const string CACHE_PATH = '/storage/cache';

    /** 🧪 Temporary directory for transient runtime files */
    public const string TEMP_PATH = '/storage/temp';

    /** ⚙️ Configuration directory for environment or service files */
    public const string CONFIG_PATH = '/config';
}
