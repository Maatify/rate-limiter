<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 16:19
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Bootstrap\Helpers;

/**
 * ⚙️ **Class EnvHelper**
 *
 * 🧩 **Purpose:**
 * Provides safe, cached, and consistent access to environment variables across
 * all Maatify libraries and applications. Designed for high performance and immutability.
 *
 * ✅ **Features:**
 * - Retrieves environment variables from multiple sources (`$_ENV`, `$_SERVER`, `getenv()`).
 * - Implements in-memory caching for repeated access efficiency.
 * - Provides optional default fallback values.
 * - Compatible with both mutable and immutable Dotenv configurations.
 * - Includes a cache reset utility for test or runtime reloading.
 *
 * ⚙️ **Example Usage:**
 * ```php
 * use Maatify\Bootstrap\Helpers\EnvHelper;
 *
 * // Retrieve with default fallback
 * $dbHost = EnvHelper::get('DB_HOST', 'localhost');
 *
 * // Check existence
 * if (EnvHelper::has('APP_ENV')) {
 *     echo 'Environment: ' . EnvHelper::get('APP_ENV');
 * }
 *
 * // Debug cached variables
 * print_r(EnvHelper::cached());
 * ```
 *
 * @package Maatify\Bootstrap\Helpers
 */
final class EnvHelper
{
    /**
     * 🧠 Internal cache for resolved environment variables.
     *
     * Acts as a local memory layer to prevent redundant lookups
     * and improve performance during repeated configuration reads.
     *
     * @var array<string, mixed> Cached environment key-value pairs.
     */
    private static array $cache = [];

    /**
     * 🎯 Retrieve an environment variable with layered lookup and caching.
     *
     * The method checks the following sources **in order**:
     * 1️⃣ `$_ENV` (preferred)
     * 2️⃣ `$_SERVER`
     * 3️⃣ `getenv()`
     * 4️⃣ `$default` (if none found)
     *
     * Once a key is resolved, its value is cached for subsequent calls.
     *
     * @param string $key The environment variable name.
     * @param mixed  $default Default value returned if variable is not found.
     *
     * @return mixed The resolved environment variable value or the default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        // ⚡ Return cached value immediately if available
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        // 🔍 Search through multiple sources
        $value = $_ENV[$key]
                 ?? $_SERVER[$key]
                    ?? getenv($key)
                       ?? $default;

        // 🧠 Cache the resolved value for future requests
        return self::$cache[$key] = $value;
    }

    /**
     * 🔍 Determine whether a specific environment variable exists.
     *
     * @param string $key The key name of the variable to check.
     *
     * @return bool True if found, false otherwise.
     */
    public static function has(string $key): bool
    {
        return self::get($key) !== null;
    }

    /**
     * 📦 Retrieve all currently cached environment variables.
     *
     * Useful for:
     * - Debugging environment states.
     * - Logging configuration visibility.
     * - Testing Dotenv loading behavior.
     *
     * @return array<string, mixed> The cached environment values.
     */
    public static function cached(): array
    {
        return self::$cache;
    }

    /**
     * 🧹 Clear the cached environment variable map.
     *
     * Typically used in:
     * - Unit tests to force fresh re-evaluation.
     * - Runtime reloading when `.env` files are reloaded.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        self::$cache = [];
    }
}
