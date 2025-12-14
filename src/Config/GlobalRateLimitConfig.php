<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Config;

/**
 * Immutable value object describing global default rate limit parameters.
 */
final class GlobalRateLimitConfig
{
    public function __construct(
        private readonly int $defaultLimit,
        private readonly int $defaultInterval,
        private readonly int $defaultBanTime,
    ) {
    }

    public function defaultLimit(): int
    {
        return $this->defaultLimit;
    }

    public function defaultInterval(): int
    {
        return $this->defaultInterval;
    }

    public function defaultBanTime(): int
    {
        return $this->defaultBanTime;
    }
}
