<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Resolver;

use InvalidArgumentException;
use Maatify\RateLimiter\Contracts\BackoffPolicyInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Resolver\EnforcingRateLimiter;
use Maatify\RateLimiter\Resolver\ExponentialBackoffPolicy;

/**
 * Pure resolver that selects a pre-wired rate limiter driver by name.
 */
final class RateLimiterResolver
{
    private readonly BackoffPolicyInterface $backoffPolicy;

    /**
     * @param array<string, RateLimiterInterface> $drivers
     * @param string $defaultDriver
     * @param BackoffPolicyInterface|null $backoffPolicy
     */
    public function __construct(
        private readonly array $drivers,
        private readonly string $defaultDriver = 'redis',
        ?BackoffPolicyInterface $backoffPolicy = null,
    ) {
        $this->backoffPolicy = $backoffPolicy ?? new ExponentialBackoffPolicy();
    }

    public function resolve(?string $driver = null): RateLimiterInterface
    {
        $driverKey = strtolower($driver ?? $this->defaultDriver);

        if (!array_key_exists($driverKey, $this->drivers)) {
            throw new InvalidArgumentException("Unsupported rate limiter driver: {$driverKey}");
        }

        $driver = $this->drivers[$driverKey];

        return new EnforcingRateLimiter($driver, $this->backoffPolicy);
    }
}
