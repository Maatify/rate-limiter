<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Config;

use Maatify\RateLimiter\Contracts\RateLimitActionInterface;

/**
 * Provides immutable rate limit configuration for specific actions.
 */
interface ActionRateLimitConfigProviderInterface
{
    public function getForAction(RateLimitActionInterface|string $action): ActionRateLimitConfig;
}
