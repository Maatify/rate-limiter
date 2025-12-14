<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Drivers;

use Maatify\RateLimiter\Config\ActionRateLimitConfig;
use Maatify\RateLimiter\Config\GlobalRateLimitConfig;
use Maatify\RateLimiter\Config\InMemoryActionRateLimitConfigProvider;
use Maatify\RateLimiter\Drivers\RedisRateLimiter;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use PHPUnit\Framework\TestCase;
use Redis;

final class RedisRateLimiterTest extends TestCase
{
    public function testAttemptIncrementsCounter(): void
    {
        $redis = $this->createMock(Redis::class);
        $limiter = new RedisRateLimiter($redis, $this->configProvider());

        // Redis mock expectations
        $redis->method('incr')->willReturn(1);
        $redis->method('ttl')->willReturn(60);
        $redis->expects($this->once())->method('expire')->with($this->anything(), 60);

        $status = $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertInstanceOf(RateLimitStatusDTO::class, $status);
        $this->assertEquals(60, $status->resetAfter);
    }

    public function testAttemptThrowsExceptionWhenLimitExceeded(): void
    {
        $redis = $this->createMock(Redis::class);
        $limiter = new RedisRateLimiter($redis, $this->configProvider());

        // Assume limit is small (e.g. 5 for LOGIN)
        // Simulate counter exceeding limit
        $redis->method('incr')->willReturn(10);
        $redis->method('ttl')->willReturn(50);

        $this->expectException(TooManyRequestsException::class);

        $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
    }

    public function testResetDeletesKey(): void
    {
        $redis = $this->createMock(Redis::class);
        $limiter = new RedisRateLimiter($redis, $this->configProvider());

        $redis->expects($this->once())->method('del')->willReturn(1);

        $result = $limiter->reset('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
        $this->assertTrue($result);
    }

    public function testStatusReturnsDTO(): void
    {
        $redis = $this->createMock(Redis::class);
        $limiter = new RedisRateLimiter($redis, $this->configProvider());

        $redis->method('get')->willReturn('2');
        $redis->method('ttl')->willReturn(30);

        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertInstanceOf(RateLimitStatusDTO::class, $status);
        $this->assertEquals(30, $status->resetAfter);
    }

    public function testStatusHandlesNonNumericValue(): void
    {
        $redis = $this->createMock(Redis::class);
        $limiter = new RedisRateLimiter($redis, $this->configProvider());

        $redis->method('get')->willReturn('invalid');
        $redis->method('ttl')->willReturn(30);

        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        // Count defaults to 0. Limit 5. Remaining 5.
        $this->assertEquals(5, $status->remaining);
    }

    private function configProvider(): InMemoryActionRateLimitConfigProvider
    {
        return new InMemoryActionRateLimitConfigProvider(
            new GlobalRateLimitConfig(defaultLimit: 5, defaultInterval: 60, defaultBanTime: 300),
            [RateLimitActionEnum::LOGIN->value() => new ActionRateLimitConfig(5, 60, 600)],
        );
    }
}
