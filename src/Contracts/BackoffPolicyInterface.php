<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Contracts;

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;

/**
 * Contract describing how backoff delays will be calculated.
 *
 * Implementations will be introduced in later phases; this interface only
 * reserves the integration point for future use.
 */
interface BackoffPolicyInterface
{
    public function calculateDelay(RateLimitStatusDTO $status): int;
}
