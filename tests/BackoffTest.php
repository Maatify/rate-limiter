<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 11:47
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;

/**
 * 🧩 Class BackoffTest
 *
 * 🎯 Purpose:
 * Validates the correctness of exponential backoff calculations and
 * related time formatting logic across rate-limiter drivers (especially Redis).
 *
 * 🧠 What it tests:
 * - Exponential growth pattern for backoff durations (`calculateBackoff()`).
 * - Proper upper limit enforcement (`max = 3600 seconds`).
 * - Valid `nextAllowedAt` date-time format consistency from `applyBackoff()`.
 *
 * ✅ Example Run:
 * ```bash
 * ./vendor/bin/phpunit --filter BackoffTest
 * ```
 *
 * @package Maatify\RateLimiter\Tests
 */
final class BackoffTest extends TestCase
{
    /**
     * 🧠 Test exponential backoff growth logic.
     *
     * Ensures that the backoff delay doubles with each attempt until the
     * maximum threshold is reached, confirming the expected exponential pattern.
     *
     * ✅ Example:
     * - Attempt 1 → 2 seconds
     * - Attempt 2 → 4 seconds
     * - Attempt 3 → 8 seconds
     * - ...
     */
    public function testExponentialBackoffCalculation(): void
    {
        // ⚙️ Load Redis environment configuration
        $redisHost = getenv('REDIS_HOST') ?: ($_ENV['REDIS_HOST'] ?? '127.0.0.1');
        $redisPort = getenv('REDIS_PORT') ?: ($_ENV['REDIS_PORT'] ?? '6379');
        $redisPassword = getenv('REDIS_PASSWORD') ?: ($_ENV['REDIS_PASSWORD'] ?? '');

        // 🧩 Resolve Redis driver instance
        $resolver = new RateLimiterResolver([
            'driver' => 'redis',
            'redis_host' => $redisHost,
            'redis_port' => $redisPort,
            'redis_password' => $redisPassword,
        ]);
        $limiter = $resolver->resolve();

        // 🔍 Access private `calculateBackoff()` method using reflection
        $method = new ReflectionMethod($limiter, 'calculateBackoff');
        $method->setAccessible(true);

        // ✅ Assert exponential pattern and cap value
        $this->assertSame(2, $method->invoke($limiter, 1));
        $this->assertSame(4, $method->invoke($limiter, 2));
        $this->assertSame(8, $method->invoke($limiter, 3));
        $this->assertSame(16, $method->invoke($limiter, 4));
        $this->assertLessThanOrEqual(3600, $method->invoke($limiter, 10));
    }

    /**
     * 🔍 Test format of `nextAllowedAt` field from backoff logic.
     *
     * Ensures that the generated timestamp from the private
     * `applyBackoff()` method matches a valid SQL datetime format (Y-m-d H:i:s).
     *
     * ✅ Expected:
     * - A non-empty string.
     * - Matches pattern: `YYYY-MM-DD HH:MM:SS`
     */
    public function testNextAllowedAtFormat(): void
    {
        // ⚙️ Load Redis configuration dynamically
        $redisHost = getenv('REDIS_HOST') ?: ($_ENV['REDIS_HOST'] ?? '127.0.0.1');
        $redisPort = getenv('REDIS_PORT') ?: ($_ENV['REDIS_PORT'] ?? '6379');
        $redisPassword = getenv('REDIS_PASSWORD') ?: ($_ENV['REDIS_PASSWORD'] ?? '');

        // 🧩 Initialize Redis driver via resolver
        $resolver = new RateLimiterResolver([
            'driver' => 'redis',
            'redis_host' => $redisHost,
            'redis_port' => $redisPort,
            'redis_password' => $redisPassword,
        ]);
        $limiter = $resolver->resolve();

        // 🧠 Invoke private method `applyBackoff()` to obtain DTO
        $dto = (new ReflectionMethod($limiter, 'applyBackoff'))
            ->invoke($limiter, 'rate:test', 3);

        // ✅ Assert the `nextAllowedAt` field is properly formatted
        $this->assertNotEmpty($dto->nextAllowedAt, 'Expected non-empty nextAllowedAt timestamp.');
        $this->assertMatchesRegularExpression(
            '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',
            $dto->nextAllowedAt,
            'Expected nextAllowedAt in Y-m-d H:i:s format.'
        );
    }
}
