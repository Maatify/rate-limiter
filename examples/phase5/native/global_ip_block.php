<?php

/**
 * 🎯 Global IP Block Example
 *
 * Demonstrates:
 * - How the Global Limiter intercepts requests before the Action Limiter.
 * - Handling `TooManyRequestsException`.
 * - Outputting `retry_after` and `next_allowed_at` timestamps.
 */

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;

// Simple In-Memory Driver that tracks counts
class GlobalBlockDriver implements RateLimiterInterface {
    private int $count = 0;

    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO {
        // Global limit is hardcoded to 2 for this demo
        if ($action->value() === 'global') {
            $this->count++;
            if ($this->count > 2) {
                 throw new TooManyRequestsException(
                    "Global limit exceeded",
                    429,
                    new RateLimitStatusDTO(2, 0, 60, 30, true, null, null, 'global')
                );
            }
            return new RateLimitStatusDTO(2, 2 - $this->count, 60, null, false, null, null, 'global');
        }

        // Action limit (ignored if global fails)
        return new RateLimitStatusDTO(10, 10, 60, null, false, null, null, 'action');
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool { return true; }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO {
        return new RateLimitStatusDTO(2, 2, 60);
    }
}

$resolver = new RateLimiterResolver(['memory' => new GlobalBlockDriver()], 'memory');
$limiter = $resolver->resolve();

$action = new class implements RateLimitActionInterface { public function value(): string { return 'api_call'; } };
$platform = new class implements PlatformInterface { public function value(): string { return 'api'; } };

echo "🌍 Global IP Limit Check (Limit: 2)\n";
echo "-----------------------------------\n";

try {
    for ($i = 1; $i <= 4; $i++) {
        echo "Request #{$i}: ";
        $status = $limiter->attempt('10.0.0.5', $action, $platform);
        echo "Allowed ✅ (Source: {$status->source})\n";
    }
} catch (TooManyRequestsException $e) {
    echo "BLOCKED 🛑\n";
    echo "Reason: " . $e->getMessage() . "\n";
    $retryAfter = $e->status->retryAfter ?? 0;
    echo "Retry After: " . $retryAfter . " seconds\n";
    echo "Next Allowed At: " . ($e->status->nextAllowedAt ?? 'Unknown') . "\n";
}
