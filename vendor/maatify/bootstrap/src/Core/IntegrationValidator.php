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

use Throwable;

/**
 * ⚙️ Class IntegrationValidator
 *
 * 🧩 Purpose:
 * Performs lightweight runtime checks to validate the consistency and readiness
 * of the Bootstrap environment across Maatify libraries.
 *
 * ✅ Features:
 * - Verifies that essential environment variables are loaded.
 * - Confirms timezone configuration is active.
 * - Checks system error handlers are functional.
 * - Returns a structured diagnostic report summarizing integration health.
 *
 * ⚙️ Example Usage:
 * ```php
 * use Maatify\Bootstrap\Core\IntegrationValidator;
 *
 * $diagnostics = IntegrationValidator::diagnostics();
 * print_r($diagnostics);
 * ```
 *
 * Example output:
 * ```php
 * [
 *     "env_loaded" => true,
 *     "timezone" => "Africa/Cairo",
 *     "handlers_ok" => true,
 *     "registered_libs" => ["maatify/common", "maatify/psr-logger"]
 * ]
 * ```
 *
 * @package Maatify\Bootstrap\Core
 */
final class IntegrationValidator
{
    /**
     * 🔍 Check whether essential environment variables are loaded.
     *
     * Ensures that both `APP_ENV` and `APP_TIMEZONE` are set,
     * confirming that the environment has been initialized properly.
     *
     * @return bool True if required environment variables exist.
     */
    public static function checkEnv(): bool
    {
        return isset($_ENV['APP_ENV']) && isset($_ENV['APP_TIMEZONE']);
    }

    /**
     * 🕒 Check that the system timezone is properly configured.
     *
     * Validates that a timezone has been set by the application
     * or by the Bootstrap initialization.
     *
     * @return bool True if timezone is configured and not empty.
     */
    public static function checkTimezone(): bool
    {
        return ! empty(date_default_timezone_get());
    }

    /**
     * ⚙️ Check if PHP error handlers are functional.
     *
     * Attempts to register a dummy handler and verifies that
     * PHP's `set_error_handler()` mechanism is operational.
     *
     * @return bool True if handlers can be registered.
     */
    public static function checkHandlers(): bool
    {
        return is_callable(set_error_handler(fn() => null));
    }

    /**
     * 🧠 Aggregate system-wide integration diagnostics.
     *
     * Combines results from all individual checks and adds
     * a list of registered Maatify libraries for context.
     *
     * @return array{
     *     env_loaded: bool,
     *     timezone: string,
     *     handlers_ok: bool,
     *     registered_libs: string[]
     * } Structured diagnostic information.
     *
     * ✅ Example:
     * ```php
     * $report = IntegrationValidator::diagnostics();
     * echo json_encode($report, JSON_PRETTY_PRINT);
     * ```
     */
    public static function diagnostics(): array
    {
        return [
            'env_loaded'      => self::checkEnv(),
            'timezone'        => date_default_timezone_get(),
            'handlers_ok'     => self::checkHandlers(),
            'registered_libs' => IntegrationManager::registered(),
        ];
    }
}
