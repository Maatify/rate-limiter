<?php

/**
 * 🎯 Exponential Backoff Evolution Example
 *
 * Demonstrates:
 * - How repeated failures increase the wait time.
 * - Simulates a scenario where the user keeps hitting the limit.
 * - Shows the calculated backoff delay growing (2^n).
 */

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\Resolver\ExponentialBackoffPolicy;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;

$policy = new ExponentialBackoffPolicy(base: 2); // 2^n growth
$limit = 10;

echo "📈 Exponential Backoff Simulation (Base 2)\n";
echo "Limit: {$limit} requests\n";
echo "--------------------------------------------------------\n";
echo "| Attempts Over Limit | Calculation (2^n) | Backoff Delay |\n";
echo "--------------------------------------------------------\n";

// Simulate requests going over the limit
// Remaining goes negative: -1, -2, -3...
// Over limit = (Used - Limit)
// Used = Limit - Remaining

for ($over = 1; $over <= 5; $over++) {
    // Construct a status where we are 'over' the limit
    // If over = 1, remaining = -1.
    $remaining = -$over;

    $status = new RateLimitStatusDTO(
        limit: $limit,
        remaining: $remaining,
        resetAfter: 3600,
        blocked: true
    );

    $delay = $policy->calculateDelay($status);

    printf(
        "| %-19d | 2^%-15d | %-13d |\n",
        $over,
        $over,
        $delay
    );
}

echo "--------------------------------------------------------\n";
