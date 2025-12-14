<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Phase5;

use Maatify\RateLimiter\Contracts\BackoffPolicyInterface;
use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Resolver\EnforcingRateLimiter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ActionLimiterTest extends TestCase
{
    /** @var RateLimiterInterface&MockObject */
    private RateLimiterInterface $driver;

    /** @var BackoffPolicyInterface&MockObject */
    private BackoffPolicyInterface $backoffPolicy;

    private EnforcingRateLimiter $resolver;

    protected function setUp(): void
    {
        $this->driver = $this->createMock(RateLimiterInterface::class);
        $this->backoffPolicy = $this->createMock(BackoffPolicyInterface::class);
        $this->resolver = new EnforcingRateLimiter($this->driver, $this->backoffPolicy);
    }

    public function testActionLimiterExecutesIfGlobalPasses(): void
    {
        $action = $this->createMock(RateLimitActionInterface::class);
        $platform = $this->createMock(PlatformInterface::class);

        $callCount = 0;

        $this->driver->expects($this->exactly(2))
            ->method('attempt')
            ->willReturnCallback(function (string $key, RateLimitActionInterface $act, PlatformInterface $plt) use (&$callCount, $action, $platform) {
                $callCount++;
                if ($callCount === 1) {
                    // Global passes
                    return new RateLimitStatusDTO(100, 99, 60, null, false, null, null, 'global');
                }

                // Action passes
                return new RateLimitStatusDTO(10, 5, 60, null, false, null, null, 'action');
            });

        $status = $this->resolver->attempt('key', $action, $platform);

        $this->assertSame('action', $status->source);
        $this->assertFalse($status->blocked);
    }

    public function testActionLimiterBlock(): void
    {
        $action = $this->createMock(RateLimitActionInterface::class);
        $platform = $this->createMock(PlatformInterface::class);

        $callCount = 0;

        $this->driver->expects($this->exactly(2))
            ->method('attempt')
            ->willReturnCallback(function (string $key, RateLimitActionInterface $act, PlatformInterface $plt) use (&$callCount, $action, $platform) {
                $callCount++;
                if ($callCount === 1) {
                    // Global passes
                    return new RateLimitStatusDTO(100, 99, 60, null, false, null, null, 'global');
                }

                // Action blocks
                $dto = new RateLimitStatusDTO(10, 0, 60, null, true, null, null, 'action');
                throw new TooManyRequestsException('Action limit', 429, $dto);
            });

        $this->backoffPolicy->expects($this->once())
            ->method('calculateDelay')
            ->willReturn(15);

        try {
            $this->resolver->attempt('key', $action, $platform);
            $this->fail('Expected TooManyRequestsException was not thrown');
        } catch (TooManyRequestsException $e) {
            $status = $e->status;
            $this->assertNotNull($status);
            $this->assertSame('action', $status->source);
            $this->assertSame(15, $status->retryAfter);
            $this->assertTrue($status->blocked);
            $this->assertNotNull($status->nextAllowedAt);
        }
    }

    public function testSourceIsSetIfNotProvidedByDriver(): void
    {
        $action = $this->createMock(RateLimitActionInterface::class);
        $platform = $this->createMock(PlatformInterface::class);

        $callCount = 0;

        $this->driver->expects($this->exactly(2))
            ->method('attempt')
            ->willReturnCallback(function (string $key, RateLimitActionInterface $act, PlatformInterface $plt) use (&$callCount) {
                $callCount++;
                if ($callCount === 1) {
                    return new RateLimitStatusDTO(100, 99, 60); // No source
                }
                return new RateLimitStatusDTO(10, 5, 60); // No source
            });

        $status = $this->resolver->attempt('key', $action, $platform);

        $this->assertSame('action', $status->source);
    }

    public function testResetResetsGlobalAndAction(): void
    {
        $action = $this->createMock(RateLimitActionInterface::class);
        $platform = $this->createMock(PlatformInterface::class);

        $callCount = 0;

        $this->driver->expects($this->exactly(2))
            ->method('reset')
            ->willReturnCallback(function (string $key, RateLimitActionInterface $act, PlatformInterface $plt) use (&$callCount) {
                $callCount++;
                if ($callCount === 1) {
                    $this->assertSame('global', $act->value());
                    return true;
                }
                return true;
            });

        $this->assertTrue($this->resolver->reset('key', $action, $platform));
    }

    public function testStatusProxiesDriverAndEnrichesSource(): void
    {
        $action = $this->createMock(RateLimitActionInterface::class);
        $platform = $this->createMock(PlatformInterface::class);

        $this->driver->expects($this->once())
            ->method('status')
            ->willReturn(new RateLimitStatusDTO(10, 5, 60));

        $status = $this->resolver->status('key', $action, $platform);

        $this->assertSame('action', $status->source);
    }
}
