<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Resolver;

use DateTimeImmutable;
use DateTimeZone;
use Maatify\RateLimiter\Contracts\BackoffPolicyInterface;
use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

final class EnforcingRateLimiter implements RateLimiterInterface
{
    private readonly RateLimitActionInterface $globalAction;

    private readonly PlatformInterface $globalPlatform;

    public function __construct(
        private readonly RateLimiterInterface $driver,
        private readonly BackoffPolicyInterface $backoffPolicy,
    ) {
        $this->globalAction = new class implements RateLimitActionInterface {
            public function value(): string
            {
                return 'global';
            }
        };

        $this->globalPlatform = new class implements PlatformInterface {
            public function value(): string
            {
                return 'global';
            }
        };
    }

    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $this->enforceGlobalLimit($key);

        try {
            $status = $this->driver->attempt($key, $action, $platform);
            $status->source = $status->source ?? 'action';

            return $status;
        } catch (TooManyRequestsException $exception) {
            $status = $exception->status ?? $this->driver->status($key, $action, $platform);
            $enhancedStatus = $this->applyBackoff($status, 'action');

            throw new TooManyRequestsException($exception->getMessage(), $exception->getCode(), $enhancedStatus);
        }
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        $globalReset = $this->driver->reset($key, $this->globalAction, $this->globalPlatform);
        $actionReset = $this->driver->reset($key, $action, $platform);

        return $globalReset && $actionReset;
    }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $status = $this->driver->status($key, $action, $platform);
        $status->source = $status->source ?? 'action';

        return $status;
    }

    private function enforceGlobalLimit(string $key): void
    {
        try {
            $status = $this->driver->attempt($key, $this->globalAction, $this->globalPlatform);
            $status->source = $status->source ?? 'global';
        } catch (TooManyRequestsException $exception) {
            $status = $exception->status ?? $this->driver->status($key, $this->globalAction, $this->globalPlatform);
            $enhancedStatus = $this->applyBackoff($status, 'global');

            throw new TooManyRequestsException($exception->getMessage(), $exception->getCode(), $enhancedStatus);
        }
    }

    private function applyBackoff(RateLimitStatusDTO $status, string $source): RateLimitStatusDTO
    {
        $delay = $this->backoffPolicy->calculateDelay($status);
        $nextAllowedAt = (new DateTimeImmutable('now', new DateTimeZone('UTC')))
            ->modify("+{$delay} seconds")
            ->format('Y-m-d H:i:s');

        return new RateLimitStatusDTO(
            limit: $status->limit,
            remaining: $status->remaining,
            resetAfter: $status->resetAfter,
            retryAfter: $delay,
            blocked: true,
            backoffSeconds: $delay,
            nextAllowedAt: $nextAllowedAt,
            source: $source,
        );
    }
}
