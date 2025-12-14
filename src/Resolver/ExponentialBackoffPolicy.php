<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Resolver;

use Maatify\RateLimiter\Contracts\BackoffPolicyInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;

final class ExponentialBackoffPolicy implements BackoffPolicyInterface
{
    public function __construct(
        private readonly int $base = 2,
        private readonly ?int $maxDelay = null,
    ) {
    }

    public function calculateDelay(RateLimitStatusDTO $status): int
    {
        $attemptsOverLimit = $this->extractAttemptsOverLimit($status);
        if ($attemptsOverLimit <= 0) {
            return 0;
        }

        $delay = (int) pow($this->base, $attemptsOverLimit);
        $cap = $this->maxDelay ?? $status->resetAfter;

        return (int) min($delay, $cap > 0 ? $cap : $delay);
    }

    private function extractAttemptsOverLimit(RateLimitStatusDTO $status): int
    {
        $used = $status->limit - $status->remaining;
        $over = $used - $status->limit;

        return max(0, $over);
    }
}
