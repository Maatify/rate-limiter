<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 02:54
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;

/**
 * 🧩 Class MiddlewareTest
 *
 * 🎯 Purpose:
 * Validates key middleware dependencies and the resolver’s ability
 * to correctly instantiate rate limiter drivers and enum behaviors.
 *
 * ⚙️ Focus:
 * - Ensures `RateLimiterResolver` creates a valid driver instance.
 * - Verifies enum `value()` method consistency.
 *
 * ✅ Example execution:
 * ```bash
 * ./vendor/bin/phpunit --filter MiddlewareTest
 * ```
 *
 * @package Maatify\RateLimiter\Tests
 */
final class MiddlewareTest extends TestCase
{
    /**
     * 🧠 Test that the resolver creates a valid Redis driver instance.
     *
     * 🎯 Ensures:
     * - The resolver successfully returns an object implementing {@see \Maatify\RateLimiter\Contracts\RateLimiterInterface}.
     * - Default behavior with `driver => redis` works as expected.
     *
     * ✅ Example:
     * ```php
     * $resolver = new RateLimiterResolver(['driver' => 'redis']);
     * $driver = $resolver->resolve();
     * ```
     */
    public function testResolverCreatesRedisDriver(): void
    {
        $redisHost = getenv('REDIS_HOST') ?: ($_ENV['REDIS_HOST'] ?? '127.0.0.1');
        $redisPort = getenv('REDIS_PORT') ?: ($_ENV['REDIS_PORT'] ?? '6379');

        $resolver = new RateLimiterResolver(
            ['driver' => 'redis',
                 'redis_host' => $redisHost,
                 'redis_port' => $redisPort,

             ]);
        $driver = $resolver->resolve();

        $this->assertInstanceOf(
            \Maatify\RateLimiter\Contracts\RateLimiterInterface::class,
            $driver,
            'Resolver must return a valid RateLimiterInterface implementation for Redis.'
        );
    }

    /**
     * 🔍 Test that enums return correct string values.
     *
     * 🎯 Verifies that `RateLimitActionEnum` and `PlatformEnum`
     * correctly return their string identifiers through `value()`.
     */
    public function testActionEnums(): void
    {
        $this->assertSame(
            'login',
            RateLimitActionEnum::LOGIN->value(),
            'RateLimitActionEnum::LOGIN must return "login".'
        );

        $this->assertSame(
            'web',
            PlatformEnum::WEB->value(),
            'PlatformEnum::WEB must return "web".'
        );
    }
}
