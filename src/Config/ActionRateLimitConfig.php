<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Config;

/**
 * Immutable value object describing per-action rate limit parameters.
 */
final class ActionRateLimitConfig
{
    public function __construct(
        private readonly int $limit,
        private readonly int $interval,
        private readonly int $banTime,
    ) {
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function interval(): int
    {
        return $this->interval;
    }

    public function banTime(): int
    {
        return $this->banTime;
    }
}
