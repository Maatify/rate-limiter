<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Drivers;

use Maatify\RateLimiter\Config\ActionRateLimitConfig;
use Maatify\RateLimiter\Config\GlobalRateLimitConfig;
use Maatify\RateLimiter\Config\InMemoryActionRateLimitConfigProvider;
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
        $limiter = new MongoRateLimiter($collection, $this->configProvider());

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
        $limiter = new MongoRateLimiter($collection, $this->configProvider());

        $collection->method('findOne')->willReturn(['count' => 100]); // Exceeds limit

        $this->expectException(TooManyRequestsException::class);

        $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
    }

    public function testResetDeletesKey(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection, $this->configProvider());

        $deleteResult = $this->createMock(DeleteResult::class);
        $deleteResult->method('getDeletedCount')->willReturn(1);

        $collection->expects($this->once())->method('deleteOne')->willReturn($deleteResult);

        $result = $limiter->reset('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
        $this->assertTrue($result);
    }

    public function testStatusReturnsDTO(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection, $this->configProvider());

        $collection->method('findOne')->willReturn(['count' => 2]);

        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertInstanceOf(RateLimitStatusDTO::class, $status);
    }

    public function testStatusHandlesMissingOrNonNumericCount(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection, $this->configProvider());

        // Case 1: Missing count
        $collection->method('findOne')->willReturn(['other_field' => 'value']);
        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
        // Limit 5, default count 0 -> remaining 5
        $this->assertEquals(5, $status->remaining);

        // Case 2: Non-numeric count
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection, $this->configProvider());
        $collection->method('findOne')->willReturn(['count' => 'invalid']);
        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
        $this->assertEquals(5, $status->remaining);
    }

    public function testAttemptHandlesMissingOrNonNumericCount(): void
    {
        $collection = $this->createMock(Collection::class);
        $limiter = new MongoRateLimiter($collection, $this->configProvider());

        $collection->expects($this->once())->method('updateOne');

        // findOne returns record with missing count -> defaults to 1
        $collection->method('findOne')->willReturn(['other_field' => 'value']);

        $status = $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        // Limit 5, count 1 -> remaining 4
        $this->assertEquals(4, $status->remaining);
    }

    private function configProvider(): InMemoryActionRateLimitConfigProvider
    {
        return new InMemoryActionRateLimitConfigProvider(
            new GlobalRateLimitConfig(defaultLimit: 5, defaultInterval: 60, defaultBanTime: 300),
            [RateLimitActionEnum::LOGIN->value() => new ActionRateLimitConfig(5, 60, 600)],
        );
    }
}
