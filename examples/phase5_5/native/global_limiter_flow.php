<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;

// 1. Define Dummy Classes for Demonstration
$action = new class implements RateLimitActionInterface {
    public function value(): string
    {
        return 'login';
    }
};

$platform = new class implements PlatformInterface {
    public function value(): string
    {
        return 'web';
    }
};

// 2. Define a Dummy Driver that fails on Global Check
// This simulates a scenario where the Global Rate Limit is exhausted.
$driver = new class implements RateLimiterInterface {
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // EnforcingRateLimiter calls attempt('key', globalAction, globalPlatform) first.
        if ($action->value() === 'global') {
            throw new TooManyRequestsException('Global limit exceeded', 429);
        }

        // Should not be reached in this example
        return new RateLimitStatusDTO(10, 9, 60);
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        return true;
    }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        return new RateLimitStatusDTO(100, 0, 60);
    }
};

// 3. Setup Resolver
$resolver = new RateLimiterResolver(['memory' => $driver], 'memory');

// 4. Resolve and Attempt
$limiter = $resolver->resolve();

try {
    echo "Attempting action...\n";
    $limiter->attempt('user_123', $action, $platform);
} catch (TooManyRequestsException $e) {
    echo "Caught expected exception: " . $e->getMessage() . "\n";

    // Use property access for status, not getStatus()
    $status = $e->status;
    if ($status) {
        echo "Source of block: " . $status->source . "\n"; // Expected: global
    }
}
