<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 16:45
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Bootstrap\Core;

use Exception;

/**
 * ⚙️ Class IntegrationManager
 *
 * 🧩 Purpose:
 * Coordinates and ensures consistent bootstrap initialization across
 * all Maatify libraries that depend on the `maatify/bootstrap` foundation.
 *
 * ✅ Features:
 * - Prevents duplicate initialization of the same library.
 * - Automatically initializes environment, timezone, and project setup via {@see Bootstrap::init()}.
 * - Keeps track of all successfully registered Maatify components.
 * - Throws clear exception messages for failed integrations.
 *
 * ⚙️ Example Usage:
 * ```php
 * use Maatify\Bootstrap\Core\IntegrationManager;
 *
 * IntegrationManager::register('maatify/psr-logger', __DIR__ . '/../');
 * IntegrationManager::register('maatify/mongo-activity', __DIR__ . '/../');
 *
 * print_r(IntegrationManager::registered());
 * ```
 *
 * Example output:
 * ```php
 * [
 *     "maatify/psr-logger",
 *     "maatify/mongo-activity"
 * ]
 * ```
 *
 * @package Maatify\Bootstrap\Core
 */
final class IntegrationManager
{
    /**
     * 🧠 List of already registered libraries.
     *
     * @var array<string, bool> Library name as key, registration status as value.
     */
    private static array $registered = [];

    /**
     * 🎯 Register a Maatify library that depends on the Bootstrap system.
     *
     * Prevents duplicate initialization and safely triggers
     * {@see Bootstrap::init()} for environment setup.
     *
     * @param string $library  Library name (e.g. `maatify/psr-logger`).
     * @param string $basePath Absolute path to the library root directory.
     *
     * @throws Exception If initialization fails during bootstrap.
     *
     * ✅ Example:
     * ```php
     * IntegrationManager::register('maatify/common', __DIR__ . '/../');
     * ```
     */
    public static function register(string $library, string $basePath): void
    {
        // 🔒 Prevent duplicate registration
        if (isset(self::$registered[$library])) {
            return;
        }

        try {
            // ⚙️ Initialize the bootstrap environment for the given library
            Bootstrap::init($basePath);
            self::$registered[$library] = true;
        } catch (Exception $e) {
            throw new Exception("❌ Failed to initialize Bootstrap for {$library}: {$e->getMessage()}");
        }
    }

    /**
     * 📦 Retrieve a list of all successfully registered libraries.
     *
     * @return string[] Array of registered library names.
     *
     * ✅ Example:
     * ```php
     * $libs = IntegrationManager::registered();
     * ```
     */
    public static function registered(): array
    {
        return array_keys(self::$registered);
    }
}
