<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Drivers;

use Maatify\RateLimiter\Config\ActionRateLimitConfig;
use Maatify\RateLimiter\Config\GlobalRateLimitConfig;
use Maatify\RateLimiter\Config\InMemoryActionRateLimitConfigProvider;
use Maatify\RateLimiter\Drivers\MySQLRateLimiter;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

final class MySQLRateLimiterTest extends TestCase
{
    public function testAttemptIncrementsCounter(): void
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $limiter = new MySQLRateLimiter($pdo, $this->configProvider());

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchColumn')->willReturn(1);

        $status = $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertInstanceOf(RateLimitStatusDTO::class, $status);
    }

    public function testAttemptThrowsExceptionWhenLimitExceeded(): void
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $limiter = new MySQLRateLimiter($pdo, $this->configProvider());

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('fetchColumn')->willReturn(100);

        $this->expectException(TooManyRequestsException::class);

        $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
    }

    public function testResetDeletesKey(): void
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $limiter = new MySQLRateLimiter($pdo, $this->configProvider());

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->expects($this->once())->method('execute')->willReturn(true);

        $result = $limiter->reset('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
        $this->assertTrue($result);
    }

    public function testStatusReturnsDTO(): void
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $limiter = new MySQLRateLimiter($pdo, $this->configProvider());

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('fetchColumn')->willReturn(2);

        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertInstanceOf(RateLimitStatusDTO::class, $status);
    }

    public function testAttemptHandlesPrepareFailure(): void
    {
        $pdo = $this->createMock(PDO::class);
        $limiter = new MySQLRateLimiter($pdo, $this->configProvider());

        // First prepare (INSERT) fails
        $pdo->method('prepare')->willReturn(false);

        // attempt should proceed to second prepare (SELECT) which also fails
        // Code: if ($stmt) { ... } then select ... if ($stmt) { ... }
        // If both fail, count is 0.

        $status = $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertEquals(0, 5 - $status->remaining); // Limit 5 - 0 = 5 remaining
    }

    public function testAttemptHandlesSecondPrepareFailure(): void
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $limiter = new MySQLRateLimiter($pdo, $this->configProvider());

        // First prepare (INSERT) succeeds, Second (SELECT) fails
        $pdo->method('prepare')->willReturnOnConsecutiveCalls($stmt, false);

        $stmt->method('execute')->willReturn(true);

        // Logic: Insert happens. Select fails -> count is 0.
        $status = $limiter->attempt('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertEquals(5, $status->remaining);
    }

    public function testStatusHandlesPrepareFailure(): void
    {
        $pdo = $this->createMock(PDO::class);
        $limiter = new MySQLRateLimiter($pdo, $this->configProvider());

        $pdo->method('prepare')->willReturn(false);

        $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);

        $this->assertEquals(5, $status->remaining);
    }

    public function testResetHandlesPrepareFailure(): void
    {
        $pdo = $this->createMock(PDO::class);
        $limiter = new MySQLRateLimiter($pdo, $this->configProvider());

        $pdo->method('prepare')->willReturn(false);

        $result = $limiter->reset('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
        $this->assertFalse($result);
    }

    private function configProvider(): InMemoryActionRateLimitConfigProvider
    {
        return new InMemoryActionRateLimitConfigProvider(
            new GlobalRateLimitConfig(defaultLimit: 5, defaultInterval: 60, defaultBanTime: 300),
            [RateLimitActionEnum::LOGIN->value() => new ActionRateLimitConfig(5, 60, 600)],
        );
    }
}
