<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Resolver;

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Resolver\EnforcingRateLimiter;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

final class RateLimiterResolverTest extends TestCase
{
    public function testResolveRedis(): void
    {
        $redisLimiter = $this->createMock(RateLimiterInterface::class);
        $resolver = new RateLimiterResolver(['redis' => $redisLimiter]);

        $this->assertInstanceOf(EnforcingRateLimiter::class, $resolver->resolve());
    }

    public function testResolveMongo(): void
    {
        $mongoLimiter = $this->createMock(RateLimiterInterface::class);
        $resolver = new RateLimiterResolver(['mongo' => $mongoLimiter], 'mongo');

        $this->assertInstanceOf(EnforcingRateLimiter::class, $resolver->resolve());
    }

    public function testResolveMySQL(): void
    {
        $mysqlLimiter = $this->createMock(RateLimiterInterface::class);
        $resolver = new RateLimiterResolver(['mysql' => $mysqlLimiter], 'mysql');

        $this->assertInstanceOf(EnforcingRateLimiter::class, $resolver->resolve());
    }

    public function testResolveThrowsExceptionForUnknownDriver(): void
    {
        $resolver = new RateLimiterResolver([]);
        $this->expectException(InvalidArgumentException::class);
        $resolver->resolve();
    }
}
