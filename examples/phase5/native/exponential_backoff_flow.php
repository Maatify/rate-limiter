<?php

/**
 * Exponential Backoff Flow Example
 *
 * Simulates: BackoffPolicyTest
 *
 * This example demonstrates:
 * 1. Exponential growth of backoff delay based on attempts over limit
 * 2. Capping by reset window
 * 3. Capping by max delay
 * 4. Zero delay if not over limit
 */

require __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Resolver\ExponentialBackoffPolicy;

// 1. Instantiate Policy (Base 2, Max Delay 100s)
$policy = new ExponentialBackoffPolicy(base: 2, maxDelay: 100);

echo "=== Scenario 1: Exponential Growth (Base 2) ===\n";

// Attempts over limit = abs(remaining) when remaining < 0
// Remaining: -1 -> Over: 1 -> Delay: 2^1 = 2
$status1 = new RateLimitStatusDTO(10, -1, 60);
$delay1 = $policy->calculateDelay($status1);
echo "Over by 1 -> Delay: {$delay1}s (Expected: 2)\n";

// Remaining: -2 -> Over: 2 -> Delay: 2^2 = 4
$status2 = new RateLimitStatusDTO(10, -2, 60);
$delay2 = $policy->calculateDelay($status2);
echo "Over by 2 -> Delay: {$delay2}s (Expected: 4)\n";

// Remaining: -3 -> Over: 3 -> Delay: 2^3 = 8
$status3 = new RateLimitStatusDTO(10, -3, 60);
$delay3 = $policy->calculateDelay($status3);
echo "Over by 3 -> Delay: {$delay3}s (Expected: 8)\n";


echo "\n=== Scenario 2: Capping by Reset Window ===\n";

// Remaining: -10 -> Over: 10 -> 2^10 = 1024s
// Reset Window: 60s
// Should be capped at 60s
// NOTE: With maxDelay=100 set in constructor, logic might pick min(1024, max(100, 60)) or similar?
// Let's re-read the policy logic.
// $cap = $this->maxDelay ?? $status->resetAfter;
// return min($delay, $cap);
// If maxDelay is 100, cap is 100. So it returns 100.
// To test Reset Window capping, we need a policy WITHOUT maxDelay.

$policyNoMax = new ExponentialBackoffPolicy(base: 2);
$statusCapReset = new RateLimitStatusDTO(10, -10, 60);
$delayCapReset = $policyNoMax->calculateDelay($statusCapReset);
echo "Calculated: 1024s, Reset Window: 60s -> Result: {$delayCapReset}s (Expected: 60)\n";


echo "\n=== Scenario 3: Capping by Max Delay ===\n";

// Remaining: -10 -> Over: 10 -> 2^10 = 1024s
// Reset Window: 2000s
// Max Delay: 100s
// Should be capped at 100s
$statusCapMax = new RateLimitStatusDTO(10, -10, 2000);
$delayCapMax = $policy->calculateDelay($statusCapMax);
echo "Calculated: 1024s, Reset Window: 2000s, Max: 100s -> Result: {$delayCapMax}s (Expected: 100)\n";


echo "\n=== Scenario 4: No Backoff if Not Over Limit ===\n";

// Remaining: 0 (At limit, but not over)
$statusOk = new RateLimitStatusDTO(10, 0, 60);
$delayOk = $policy->calculateDelay($statusOk);
echo "Remaining: 0 -> Delay: {$delayOk}s (Expected: 0)\n";

// Remaining: 5
$statusOk2 = new RateLimitStatusDTO(10, 5, 60);
$delayOk2 = $policy->calculateDelay($statusOk2);
echo "Remaining: 5 -> Delay: {$delayOk2}s (Expected: 0)\n";
