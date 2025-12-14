<?php

/**
 * 🎯 Custom Backoff Policy Example
 *
 * Demonstrates:
 * - Implementing BackoffPolicyInterface.
 * - Injecting the custom policy into the Resolver.
 * - Observing different backoff behavior (e.g., Linear).
 */

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\Contracts\BackoffPolicyInterface;
use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;

// 1. Define Custom Linear Backoff (10s per attempt over limit)
class LinearBackoffPolicy implements BackoffPolicyInterface {
    public function calculateDelay(RateLimitStatusDTO $status): int {
        $over = ($status->limit - $status->remaining) - $status->limit;
        if ($over <= 0) return 0;
        return $over * 10; // 10, 20, 30...
    }
}

// 2. Dummy Driver that always fails
class FailingDriver implements RateLimiterInterface {
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO {
        // Always throw exception with "remaining: -1" (1 over limit)
        throw new TooManyRequestsException("Fail", 429, new RateLimitStatusDTO(5, -1, 60));
    }
    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool { return true; }
    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO {
         return new RateLimitStatusDTO(5, 0, 60);
    }
}

// 3. Inject Custom Policy into Resolver
$customPolicy = new LinearBackoffPolicy();
$resolver = new RateLimiterResolver(
    ['fail' => new FailingDriver()],
    'fail',
    $customPolicy
);

$limiter = $resolver->resolve();

$action = new class implements RateLimitActionInterface { public function value(): string { return 'test'; } };
$platform = new class implements PlatformInterface { public function value(): string { return 'cli'; } };

echo "🛠️ Custom Linear Backoff Policy\n";
echo "------------------------------\n";

try {
    $limiter->attempt('user1', $action, $platform);
} catch (TooManyRequestsException $e) {
    echo "Caught Exception!\n";
    $retryAfter = $e->status->retryAfter ?? 0;
    echo "Retry After: " . $retryAfter . " seconds\n";

    // Validate
    if ($retryAfter === 10) {
        echo "✅ Policy applied correctly (1 over * 10 = 10s)\n";
    } else {
        echo "❌ Unexpected delay: " . $retryAfter . "\n";
    }
}
