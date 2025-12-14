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

final class GlobalLimiterTest extends TestCase
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

    public function testGlobalLimiterExecutesBeforeActionLimiter(): void
    {
        $action = $this->createMock(RateLimitActionInterface::class);
        $platform = $this->createMock(PlatformInterface::class);

        $callCount = 0;

        $this->driver->expects($this->exactly(2))
            ->method('attempt')
            ->willReturnCallback(function (string $key, RateLimitActionInterface $act, PlatformInterface $plt) use (&$callCount, $action, $platform) {
                $callCount++;
                if ($callCount === 1) {
                    $this->assertSame('global', $act->value(), 'First call must be global action');
                    $this->assertSame('global', $plt->value(), 'First call must be global platform');
                    return new RateLimitStatusDTO(10, 9, 60, null, false, null, null, 'global');
                }

                $this->assertSame($action, $act, 'Second call must be the requested action');
                $this->assertSame($platform, $plt, 'Second call must be the requested platform');
                return new RateLimitStatusDTO(10, 9, 60, null, false, null, null, 'action');
            });

        $status = $this->resolver->attempt('key', $action, $platform);

        $this->assertSame('action', $status->source);
    }

    public function testGlobalLimiterBlockPreventsActionLimiter(): void
    {
        $action = $this->createMock(RateLimitActionInterface::class);
        $platform = $this->createMock(PlatformInterface::class);

        // Global attempt fails
        $exceptionDTO = new RateLimitStatusDTO(10, 0, 60, null, true, null, null, 'global');

        $this->driver->expects($this->once())
            ->method('attempt')
            ->with(
                'key',
                $this->callback(fn(RateLimitActionInterface $a) => $a->value() === 'global'),
                $this->callback(fn(PlatformInterface $p) => $p->value() === 'global')
            )
            ->willThrowException(new TooManyRequestsException('Global limit', 429, $exceptionDTO));

        $this->backoffPolicy->expects($this->once())
            ->method('calculateDelay')
            ->willReturn(30);

        try {
            $this->resolver->attempt('key', $action, $platform);
            $this->fail('Expected TooManyRequestsException was not thrown');
        } catch (TooManyRequestsException $e) {
            $status = $e->status;
            $this->assertNotNull($status);
            $this->assertSame('global', $status->source);
            $this->assertSame(30, $status->retryAfter);
            $this->assertTrue($status->blocked);
            $this->assertNotNull($status->nextAllowedAt);
        }
    }
}
