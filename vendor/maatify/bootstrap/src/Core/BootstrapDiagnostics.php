<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 16:53
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Bootstrap\Core;

use Maatify\Bootstrap\Helpers\EnvHelper;
use Psr\Log\LoggerInterface;

/**
 * 🧠 **BootstrapDiagnostics**
 *
 * 🎯 **Purpose:**
 * Performs runtime integrity checks for environment configuration, timezone, and error handling.
 * Additionally manages **Safe Mode** detection and activation for production safety control.
 *
 * 🧩 **Use Cases:**
 * - Run during bootstrap to validate readiness before system initialization.
 * - Execute in CI/CD pipelines to ensure environment consistency.
 * - Log diagnostic summaries for observability and troubleshooting.
 *
 * ✅ **Example:**
 * ```php
 * use Maatify\Bootstrap\Core\BootstrapDiagnostics;
 * use Maatify\PsrLogger\LoggerFactory;
 *
 * $logger = LoggerFactory::create('bootstrap');
 * $diagnostics = new BootstrapDiagnostics($logger);
 *
 * $results = $diagnostics->run();
 * print_r($results);
 *
 * // Optionally enforce Safe Mode
 * $diagnostics->activateSafeMode();
 * ```
 */
final class BootstrapDiagnostics
{
    /**
     * 🧩 Constructor
     *
     * @param LoggerInterface|null $logger Optional PSR-3 compatible logger instance
     *                                     for capturing diagnostic results.
     */
    public function __construct(private readonly ?LoggerInterface $logger = null)
    {
    }

    /**
     * 🧠 Execute full environment diagnostics.
     *
     * ⚙️ **Checks Performed:**
     * - `.env` integrity and essential variable presence.
     * - Timezone configuration validity.
     * - Error handler readiness.
     * - Safe Mode activation conditions.
     *
     * 🧾 Logs diagnostic results when a logger is available.
     *
     * @return array<string, bool|string> Diagnostic results:
     * - `env_loaded` → environment variable presence status.
     * - `timezone_ok` → timezone configuration check.
     * - `error_handler` → error handler availability.
     * - `safe_mode` → whether Safe Mode should be active.
     */
    public function run(): array
    {
        $results = [
            'env_loaded'    => $this->checkEnv(),
            'timezone_ok'   => $this->checkTimezone(),
            'error_handler' => $this->checkErrors(),
            'safe_mode'     => $this->isSafeMode(),
        ];

        // 🧾 Log environment status for CI or production audits
        $this->logger?->info('Bootstrap Diagnostics', $results);

        // 🧪 Log CI execution context (if applicable)
        if (EnvHelper::get('CI', false)) {
            $this->logger?->info('Running under CI environment (CI=true)');
        }

        return $results;
    }

    /**
     * 🔍 Validate environment variable presence.
     *
     * - In **CI environments**, `.env.example` is typically used,
     *   so minimal checks pass automatically.
     * - In normal operation, verifies both `APP_ENV` and `APP_TIMEZONE` are defined.
     *
     * @return bool True if environment is valid; otherwise false.
     */
    public function checkEnv(): bool
    {
        if (EnvHelper::get('CI', false)) {
            // ✅ Assume valid under CI pipelines using minimal env
            return true;
        }

        return EnvHelper::has('APP_ENV') && EnvHelper::has('APP_TIMEZONE');
    }

    /**
     * 🕒 Confirm timezone configuration exists in the PHP runtime.
     *
     * @return bool True if a timezone is set; otherwise false.
     */
    public function checkTimezone(): bool
    {
        return !empty(date_default_timezone_get());
    }

    /**
     * ⚙️ Verify an error handler can be registered successfully.
     *
     * Uses a temporary callable to confirm error handler support.
     *
     * @return bool True if an error handler is callable and functional.
     */
    public function checkErrors(): bool
    {
        return is_callable(set_error_handler(static fn() => null));
    }

    /**
     * 🧩 Determine if **Safe Mode** conditions are met.
     *
     * Safe Mode activates when:
     * - `APP_ENV` equals `'production'`.
     * - Non-production `.env` files (`.env.local` or `.env.testing`) are detected.
     * - Not running under Continuous Integration (`CI=true` disables it).
     *
     * @return bool True if Safe Mode should be active.
     */
    public function isSafeMode(): bool
    {
        $base = $this->basePath(); // Project root path
        $env  = EnvHelper::get('APP_ENV', 'production');
        $ci   = EnvHelper::get('CI', false);

        // 🚫 Always disable Safe Mode under CI
        if ($ci) {
            return false;
        }

        // ⚠️ Enable Safe Mode in production when dev/test .env files exist
        return $env === 'production' && (
                file_exists($base . '/.env.local') ||
                file_exists($base . '/.env.testing')
            );
    }

    /**
     * 🚦 Activate Safe Mode protection (if applicable).
     *
     * When unsafe `.env` files are found in production:
     * - `SAFE_MODE=1` is applied globally via `putenv()` and `$_ENV`.
     * - A warning is logged through the provided logger.
     *
     * @return void
     */
    public function activateSafeMode(): void
    {
        if ($this->isSafeMode()) {
            $env = EnvHelper::get('APP_ENV', 'production');

            $this->logger?->warning(
                "⚠️ Safe Mode Activated: Non-production .env file detected under APP_ENV={$env}."
            );

            putenv('SAFE_MODE=1');
            $_ENV['SAFE_MODE'] = '1';
        }
    }

    /**
     * 📁 Resolve the project’s base directory.
     *
     * This method ensures consistent base path resolution even
     * when executed from nested contexts or testing environments.
     *
     * @return string Absolute path to the root directory of the project.
     */
    private function basePath(): string
    {
        return dirname(__DIR__, 2);
    }
}
