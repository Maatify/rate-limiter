<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 16:56
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

use Maatify\Bootstrap\Core\BootstrapDiagnostics;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **DiagnosticsTest**
 *
 * 🎯 Purpose:
 * Comprehensive PHPUnit test suite for {@see BootstrapDiagnostics}, ensuring reliable
 * runtime diagnostic behavior and Safe Mode logic validation across environments.
 *
 * 🧩 Context:
 * - Verifies environment, timezone, and error handler checks.
 * - Validates Safe Mode detection under both production and CI contexts.
 * - Guarantees deterministic results across isolated test runs.
 *
 * ✅ Example:
 * ```bash
 * composer run-script test
 * ```
 *
 * ⚙️ Executes automatically during CI/CD workflows to validate bootstrap readiness.
 */
final class DiagnosticsTest extends TestCase
{
    /**
     * 🧹 Prepare clean environment before every test.
     *
     * Ensures no residual environment variables, temp files, or cached
     * configurations from previous tests influence the next execution.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->resetEnvState();

        // 🧠 Clear cached env values (if supported by EnvHelper)
        if (class_exists(\Maatify\Bootstrap\Helpers\EnvHelper::class)) {
            \Maatify\Bootstrap\Helpers\EnvHelper::clearCache();
        }
    }

    /**
     * 🧹 Clean up test environment after every test.
     *
     * Restores initial state for subsequent tests.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->resetEnvState();
        parent::tearDown();
    }

    /**
     * 🧰 Helper method — restore a clean environment between tests.
     *
     * Clears environment variables, deletes temp `.env.local` files, and
     * restores the default timezone to `Africa/Cairo`.
     *
     * @return void
     */
    private function resetEnvState(): void
    {
        // 🚫 Clear environment variables
        putenv('APP_ENV');
        putenv('CI');
        unset($_ENV['APP_ENV'], $_SERVER['APP_ENV'], $_ENV['CI']);

        // 🧾 Remove temporary local environment file
        $envPath = dirname(__DIR__) . '/.env.local';
        if (file_exists($envPath)) {
            @unlink($envPath);
        }

        // 🕒 Reset timezone to baseline
        date_default_timezone_set('Africa/Cairo');
    }

    /**
     * ✅ Ensure that `run()` returns all expected diagnostic keys.
     *
     * 🎯 Purpose:
     * Confirms that {@see BootstrapDiagnostics::run()} produces a full
     * associative array with the required fields.
     *
     * ⚙️ Expected keys:
     * - `env_loaded`
     * - `timezone_ok`
     * - `error_handler`
     * - `safe_mode`
     *
     * @return void
     */
    public function testDiagnosticsReturnExpectedStructure(): void
    {
        // 🧩 Instantiate diagnostics (no logger used here)
        $diag = new BootstrapDiagnostics();

        // 🧪 Run diagnostics suite
        $data = $diag->run();

        // 🔍 Validate array structure completeness
        $this->assertArrayHasKey('env_loaded', $data);
        $this->assertArrayHasKey('timezone_ok', $data);
        $this->assertArrayHasKey('error_handler', $data);
        $this->assertArrayHasKey('safe_mode', $data);
    }

    /**
     * ✅ Validate Safe Mode activation and CI override logic.
     *
     * 🧠 Scenario:
     * 1. `APP_ENV=production`
     * 2. `.env.local` exists → Safe Mode **must activate**
     * 3. `CI=true` → Safe Mode **must be disabled**
     *
     * 🧩 Ensures that Safe Mode logic behaves predictably
     * under both local and CI conditions.
     *
     * @return void
     */
    public function testSafeModeDetection(): void
    {
        $envPath = dirname(__DIR__) . '/.env.local';
        touch($envPath);

        // 🧱 Simulate production environment
        putenv('APP_ENV=production');
        $_ENV['APP_ENV'] = 'production';
        $_SERVER['APP_ENV'] = 'production';
        $_ENV['CI'] = false;

        $diag = new BootstrapDiagnostics();

        // ✅ Expect Safe Mode to activate due to .env.local presence
        $this->assertTrue(
            $diag->isSafeMode(),
            'Safe mode should activate when .env.local exists in production.'
        );

        // 🧹 Remove temp file and simulate CI environment
        @unlink($envPath);
        $_ENV['CI'] = true;

        // 🚫 Expect Safe Mode to be disabled under CI
        $this->assertFalse(
            $diag->isSafeMode(),
            'Safe mode must be disabled when running under CI.'
        );
    }
}
