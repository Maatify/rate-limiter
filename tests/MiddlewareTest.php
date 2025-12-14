<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests;

use PHPUnit\Framework\TestCase;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Resolver\EnforcingRateLimiter;

final class MiddlewareTest extends TestCase
{
    public function testResolverSelectsDriver(): void
    {
        $mockDriver = $this->createMock(RateLimiterInterface::class);

        $resolver = new RateLimiterResolver([
            'redis' => $mockDriver,
        ], 'redis');

        $driver = $resolver->resolve();

        $this->assertInstanceOf(EnforcingRateLimiter::class, $driver);
    }

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
