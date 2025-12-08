<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Drivers;

use Maatify\RateLimiter\Drivers\MongoRateLimiter;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use MongoDB\Collection;
use MongoDB\DeleteResult;
use PHPUnit\Framework\TestCase;

final class MongoRateLimiterTest extends TestCase
{
    public function testAttemptIncrementsCounter(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection);

        // Expect updateOne to be called
        $collection->expects($this->once())->method('updateOne');

        // Mock findOne to return count within limit
        $collection->method('findOne')->willReturn(['count' => 1]);

        $status = $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertInstanceOf(RateLimitStatusDTO::class, $status);
    }

    public function testAttemptThrowsExceptionWhenLimitExceeded(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection);

        $collection->method('findOne')->willReturn(['count' => 100]); // Exceeds limit

        $this->expectException(TooManyRequestsException::class);

        $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
    }

    public function testResetDeletesKey(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection);

        $deleteResult = $this->createMock(DeleteResult::class);
        $deleteResult->method('getDeletedCount')->willReturn(1);

        $collection->expects($this->once())->method('deleteOne')->willReturn($deleteResult);

        $result = $limiter->reset('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
        $this->assertTrue($result);
    }

    public function testStatusReturnsDTO(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection);

        $collection->method('findOne')->willReturn(['count' => 2]);

        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertInstanceOf(RateLimitStatusDTO::class, $status);
    }

    public function testStatusHandlesMissingOrNonNumericCount(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection);

        // Case 1: Missing count
        $collection->method('findOne')->willReturn(['other_field' => 'value']);
        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
        // Limit 5, default count 0 -> remaining 5
        $this->assertEquals(5, $status->remaining);

        // Case 2: Non-numeric count
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection);
        $collection->method('findOne')->willReturn(['count' => 'invalid']);
        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
        $this->assertEquals(5, $status->remaining);
    }

    public function testAttemptHandlesMissingOrNonNumericCount(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection);

        $collection->expects($this->once())->method('updateOne');

        // findOne returns record with missing count -> defaults to 1
        $collection->method('findOne')->willReturn(['other_field' => 'value']);

        $status = $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        // Limit 5, count 1 -> remaining 4
        $this->assertEquals(4, $status->remaining);
    }

    public function testApplyBackoffLogic(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection);

        $collection->expects($this->once())->method('updateOne');

        $reflection = new \ReflectionClass($limiter);
        $method = $reflection->getMethod('applyBackoff');
        $method->setAccessible(true);

        // attempt 3 -> 2^3 = 8 seconds backoff
        $status = $method->invoke($limiter, 'user123', 3);

        $this->assertInstanceOf(RateLimitStatusDTO::class, $status);
        $this->assertEquals(8, $status->backoffSeconds);
        $this->assertTrue($status->blocked);
    }

    public function testCalculateBackoffLogic(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection);

        $reflection = new \ReflectionClass($limiter);
        $method = $reflection->getMethod('calculateBackoff');
        $method->setAccessible(true);

        $backoff = $method->invoke($limiter, 3, 2, 3600);
        $this->assertEquals(8, $backoff);

        // Test max cap
        $backoff = $method->invoke($limiter, 20, 2, 100);
        $this->assertEquals(100, $backoff);
    }
}
