<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Drivers;

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
        $limiter = new RedisRateLimiter($redis);

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
        $limiter = new RedisRateLimiter($redis);

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
        $limiter = new RedisRateLimiter($redis);

        $redis->expects($this->once())->method('del')->willReturn(1);

        $result = $limiter->reset('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
        $this->assertTrue($result);
    }

    public function testStatusReturnsDTO(): void
    {
        $redis = $this->createMock(Redis::class);
        $limiter = new RedisRateLimiter($redis);

        $redis->method('get')->willReturn('2');
        $redis->method('ttl')->willReturn(30);

        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertInstanceOf(RateLimitStatusDTO::class, $status);
        $this->assertEquals(30, $status->resetAfter);
    }

    public function testStatusHandlesNonNumericValue(): void
    {
        $redis = $this->createMock(Redis::class);
        $limiter = new RedisRateLimiter($redis);

        $redis->method('get')->willReturn('invalid');
        $redis->method('ttl')->willReturn(30);

        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        // Count defaults to 0. Limit 5. Remaining 5.
        $this->assertEquals(5, $status->remaining);
    }

    public function testCalculateBackoffLogic(): void
    {
        $redis = $this->createMock(Redis::class);
        $limiter = new RedisRateLimiter($redis);

        $reflection = new \ReflectionClass($limiter);
        $method = $reflection->getMethod('calculateBackoff');
        $method->setAccessible(true);

        $backoff = $method->invoke($limiter, 3, 2, 3600);
        $this->assertEquals(8, $backoff);
    }

    public function testApplyBackoffLogic(): void
    {
        $redis = $this->createMock(Redis::class);
        $limiter = new RedisRateLimiter($redis);

        $redis->expects($this->once())->method('expire')->with($this->anything(), 8);

        $reflection = new \ReflectionClass($limiter);
        $method = $reflection->getMethod('applyBackoff');
        $method->setAccessible(true);

        $status = $method->invoke($limiter, 'user123', 3);
        $this->assertInstanceOf(RateLimitStatusDTO::class, $status);
        /** @var RateLimitStatusDTO $status */
        $this->assertEquals(8, $status->backoffSeconds);
    }
}
