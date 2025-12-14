<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Resolver\ExponentialBackoffPolicy;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;

// 1. Define Dummy Classes
$action = new class implements RateLimitActionInterface {
    public function value(): string
    {
        return 'sms_send';
    }
};

$platform = new class implements PlatformInterface {
    public function value(): string
    {
        return 'api';
    }
};

// 2. Define Driver that throws Exception with negative remaining to simulate overage
// In RedisRateLimiter, remaining = limit - current. If current > limit, remaining is negative.
// This matches real driver behavior seen in src/Drivers/RedisRateLimiter.php.
$driver = new class implements RateLimiterInterface {
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        if ($action->value() === 'global') {
            return new RateLimitStatusDTO(1000, 999, 3600);
        }

        // Simulate exhaustion
        // Limit 10, Current 12 -> Remaining -2
        // Used = Limit - Remaining = 10 - (-2) = 12
        // Over = Used - Limit = 12 - 10 = 2
        // Backoff Base 2 => 2^2 = 4 seconds delay
        $status = new RateLimitStatusDTO(10, -2, 60);
        throw new TooManyRequestsException('Rate limit exceeded', 429, $status);
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        return true;
    }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        return new RateLimitStatusDTO(10, -2, 60);
    }
};

// 3. Setup Resolver with ExponentialBackoffPolicy
// Base 2 seconds, Max 60 seconds
$backoffPolicy = new ExponentialBackoffPolicy(2, 60);
$resolver = new RateLimiterResolver(['memory' => $driver], 'memory', $backoffPolicy);
$limiter = $resolver->resolve();

// 4. Trigger Backoff
try {
    echo "Attempting SMS send (simulating 12th attempt on limit 10)...\n";
    $limiter->attempt('user_789', $action, $platform);
} catch (TooManyRequestsException $e) {
    $status = $e->getStatus();

    echo "Blocked: " . ($status->blocked ? 'Yes' : 'No') . "\n";
    echo "Retry After: " . $status->retryAfter . " seconds\n"; // Expected: 4
    echo "Backoff Seconds: " . $status->backoffSeconds . "\n";
    echo "Next Allowed At: " . $status->nextAllowedAt . "\n";
}
