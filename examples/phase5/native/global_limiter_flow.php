<?php

/**
 * Global Limiter Flow Example
 *
 * Simulates: GlobalLimiterTest::testGlobalLimiterBlockPreventsActionLimiter
 *
 * This example demonstrates:
 * 1. Global limiter is checked first
 * 2. If global limiter fails, action limiter is NOT checked
 * 3. EnforcingRateLimiter throws TooManyRequestsException with enriched DTO
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

// 1. Setup Mock Dependencies (In real app, these would be Redis/MySQL drivers)
class MockGlobalFailingDriver implements RateLimiterInterface
{
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // Simulate Global Limiter Check
        if ($action->value() === 'global' && $platform->value() === 'global') {
            echo "-> Driver: Checking Global Limit... BLOCKED!\n";
            // Return a blocked DTO (remaining = 0, blocked = true)
            return new RateLimitStatusDTO(
                limit: 10,
                remaining: 0,
                resetAfter: 60,
                retryAfter: null, // Driver doesn't calculate backoff
                blocked: true,
                backoffSeconds: null,
                nextAllowedAt: null,
                source: 'global' // Explicitly set source as global
            );
        }

        // This should NOT be reached in this scenario
        echo "-> Driver: Checking Action Limit... (Should not happen!)\n";
        return new RateLimitStatusDTO(10, 5, 60, null, false, null, null, 'action');
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

class MockBackoffPolicy implements BackoffPolicyInterface
{
    public function calculateDelay(RateLimitStatusDTO $status): int
    {
        echo "-> Backoff: Calculating delay for blocked request...\n";
        return 30; // Return 30s delay
    }
}

// 2. Instantiate EnforcingRateLimiter
$driver = new MockGlobalFailingDriver();
$backoff = new MockBackoffPolicy();
$limiter = new EnforcingRateLimiter($driver, $backoff);

// 3. Define Context
$key = 'user_123';
$action = RateLimitActionEnum::LOGIN; // Any action
$platform = PlatformEnum::WEB; // Any platform

echo "=== Scenario: Global Limiter Blocks Request ===\n";

try {
    // 4. Attempt Request
    $limiter->attempt($key, $action, $platform);
    echo "ERROR: Request should have been blocked!\n";
} catch (TooManyRequestsException $e) {
    // 5. Inspect Result
    echo "SUCCESS: Caught TooManyRequestsException\n";

    $status = $e->status;
    echo "Source: " . $status->source . "\n"; // Should be 'global'
    echo "Retry After: " . $status->retryAfter . "s\n"; // Should be 30
    echo "Blocked: " . ($status->blocked ? 'Yes' : 'No') . "\n";
}
