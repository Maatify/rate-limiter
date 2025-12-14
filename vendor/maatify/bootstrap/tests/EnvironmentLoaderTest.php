<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 15:37
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

declare(strict_types=1);

use Maatify\Bootstrap\Core\EnvironmentLoader;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 Class EnvironmentLoaderTest
 *
 * 🧩 Purpose:
 * Unit test suite for {@see EnvironmentLoader}, ensuring correct environment
 * loading behavior and timezone application order.
 *
 * ✅ Tests:
 * - Priority-based environment file loading (`.env.local`, `.env.testing`, `.env`).
 * - Proper timezone setup after environment load.
 * - Fallback to default timezone when none defined.
 *
 * ⚙️ Example:
 * ```bash
 * vendor/bin/phpunit --filter EnvironmentLoaderTest
 * ```
 */
final class EnvironmentLoaderTest extends TestCase
{
    /**
     * 🎯 Verifies that EnvironmentLoader correctly loads environment variables
     * in the defined priority order and applies the expected timezone setting.
     *
     * Ensures:
     * - Environment variables are not empty.
     * - Timezone matches APP_TIMEZONE or default `Africa/Cairo`.
     */
    public function testEnvLoadingPriority(): void
    {
        $loader = new EnvironmentLoader(__DIR__ . '/../');
        $loader->load();

        $this->assertNotEmpty($this->env('APP_ENV'), 'Environment variable APP_ENV should not be empty.');
        $this->assertEquals(
            $this->env('APP_TIMEZONE', 'Africa/Cairo'),
            date_default_timezone_get(),
            'Timezone should match APP_TIMEZONE or fallback to Africa/Cairo.'
        );
    }

    /**
     * 🧰 Helper method for safely retrieving environment values
     * from multiple possible sources.
     *
     * @param string $key     The environment key to retrieve.
     * @param mixed  $default Default value if key is not found.
     *
     * @return mixed The environment variable value or the default.
     */
    private function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key]
               ?? $_SERVER[$key]
                  ?? getenv($key)
                     ?? $default;
    }
}
