<?php

/**
 * Custom Backoff Policy Example
 *
 * This example demonstrates:
 * 1. Implementing BackoffPolicyInterface for custom logic
 * 2. Using the custom policy with EnforcingRateLimiter
 */

require __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\Contracts\BackoffPolicyInterface;
use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Resolver\EnforcingRateLimiter;

// 1. Define Custom Policy (e.g., Linear Backoff)
class LinearBackoffPolicy implements BackoffPolicyInterface
{
    public function __construct(private int $step = 10) {}

    public function calculateDelay(RateLimitStatusDTO $status): int
    {
        $over = max(0, ($status->limit - $status->remaining) - $status->limit);
        if ($over <= 0) return 0;

        // Linear: 10s, 20s, 30s...
        return $over * $this->step;
    }
}

// 2. Mock Driver (Always Fails)
class MockFailingDriver implements RateLimiterInterface
{
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        if ($action->value() === 'global') {
            return new RateLimitStatusDTO(100, 99, 60, null, false, null, null, 'global');
        }

        // Action fails with remaining -3 (3 over limit)
        $dto = new RateLimitStatusDTO(10, -3, 60, null, true, null, null, 'action');
        throw new TooManyRequestsException("Limit exceeded", 429, $dto);
    }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        return new RateLimitStatusDTO(10, 10, 60);
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        return true;
    }
}

// 3. Setup
echo "=== Scenario: Linear Backoff (Step 10s) ===\n";
$policy = new LinearBackoffPolicy(step: 10);
$driver = new MockFailingDriver();
$limiter = new EnforcingRateLimiter($driver, $policy);

try {
    // Action failing with -3 remaining (3 over limit)
    // Expected Delay: 3 * 10 = 30s
    $limiter->attempt('user_123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
} catch (TooManyRequestsException $e) {
    $status = $e->status;
    echo "Remaining: " . $status->remaining . "\n";
    echo "Retry After: " . $status->retryAfter . "s (Expected: 30)\n";

    if ($status->retryAfter === 30) {
        echo "SUCCESS: Custom policy applied correctly.\n";
    } else {
        echo "FAILURE: Incorrect delay.\n";
    }
}
