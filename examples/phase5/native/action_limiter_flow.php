<?php

/**
 * Action Limiter Flow Example
 *
 * Simulates: ActionLimiterTest::testActionLimiterExecutesIfGlobalPasses
 *            ActionLimiterTest::testActionLimiterBlock
 *
 * This example demonstrates:
 * 1. Global limiter checks first (and passes)
 * 2. Action limiter checks second
 * 3. EnforcingRateLimiter returns success if both pass
 * 4. EnforcingRateLimiter throws if Action fails
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

class MockActionDriver implements RateLimiterInterface
{
    private bool $shouldFailAction = false;

    public function setFailAction(bool $fail): void
    {
        $this->shouldFailAction = $fail;
    }

    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // 1. Global Check (Always passes in this example)
        if ($action->value() === 'global') {
            echo "-> Driver: Checking Global Limit... PASS\n";
            return new RateLimitStatusDTO(100, 99, 60, null, false, null, null, 'global');
        }

        // 2. Action Check
        echo "-> Driver: Checking Action Limit... ";
        if ($this->shouldFailAction) {
            echo "BLOCKED!\n";
            // Simulate driver throwing exception on limit reached
            $dto = new RateLimitStatusDTO(10, 0, 60, null, true, null, null, 'action');
            throw new TooManyRequestsException("Rate limit exceeded", 429, $dto);
        }

        echo "PASS\n";
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

class MockBackoffPolicyForAction implements BackoffPolicyInterface
{
    public function calculateDelay(RateLimitStatusDTO $status): int
    {
        return 15; // 15s delay
    }
}

$driver = new MockActionDriver();
$backoff = new MockBackoffPolicyForAction();
$limiter = new EnforcingRateLimiter($driver, $backoff);

$key = 'user_123';
$action = RateLimitActionEnum::LOGIN;
$platform = PlatformEnum::WEB;

// Scenario A: Both Pass
echo "=== Scenario A: Global & Action Pass ===\n";
$driver->setFailAction(false);
$status = $limiter->attempt($key, $action, $platform);
echo "SUCCESS: Request allowed\n";
echo "Source: " . $status->source . "\n"; // Should be 'action'
echo "Remaining: " . $status->remaining . "\n";
echo "\n";

// Scenario B: Global Pass, Action Fail
echo "=== Scenario B: Global Pass, Action Fail ===\n";
$driver->setFailAction(true);
try {
    $limiter->attempt($key, $action, $platform);
    echo "ERROR: Should have blocked!\n";
} catch (TooManyRequestsException $e) {
    echo "SUCCESS: Caught TooManyRequestsException\n";
    $status = $e->status;
    echo "Source: " . $status->source . "\n"; // Should be 'action'
    echo "Retry After: " . $status->retryAfter . "s\n"; // Should be 15
}
