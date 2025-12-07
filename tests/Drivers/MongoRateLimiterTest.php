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
}
