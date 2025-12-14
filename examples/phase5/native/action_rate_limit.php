<?php

/**
 * 🎯 Action-based Rate Limit Example
 *
 * Demonstrates:
 * - Setting up the rate limit configuration for specific actions.
 * - Using an in-memory config provider.
 * - Enforcing limits on a "login" action.
 * - Outputting remaining requests.
 */

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\Config\ActionRateLimitConfig;
use Maatify\RateLimiter\Config\GlobalRateLimitConfig;
use Maatify\RateLimiter\Config\InMemoryActionRateLimitConfigProvider;
use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Contracts\BackoffPolicyInterface;

// 1. Define a simple in-memory driver for demonstration (no Redis/DB required)
// In a real app, use RedisRateLimiter or MySQLRateLimiter
class InMemoryRateLimiter implements RateLimiterInterface {
    /** @var array<string, int> */
    private array $storage = [];

    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO {
        $storageKey = $key . ':' . $action->value() . ':' . $platform->value();
        $current = (int)($this->storage[$storageKey] ?? 0);
        $this->storage[$storageKey] = $current + 1;

        // Simulating a limit of 5
        $limit = 5;
        $remaining = (int)max(0, $limit - $this->storage[$storageKey]);

        // In a real driver, you'd throw TooManyRequestsException if remaining < 0
        // For this example, we just return the status

        return new RateLimitStatusDTO(
            limit: $limit,
            remaining: $remaining,
            resetAfter: 60,
            source: 'action'
        );
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool {
        $storageKey = $key . ':' . $action->value() . ':' . $platform->value();
        unset($this->storage[$storageKey]);
        return true;
    }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO {
         $storageKey = $key . ':' . $action->value() . ':' . $platform->value();
         $limit = 5;
         $current = (int)($this->storage[$storageKey] ?? 0);
         $remaining = (int)max(0, $limit - $current);

         return new RateLimitStatusDTO(
            limit: $limit,
            remaining: $remaining,
            resetAfter: 60,
            source: 'action'
        );
    }
}

// 2. Setup Configuration
$globalConfig = new GlobalRateLimitConfig(defaultLimit: 100, defaultInterval: 60, defaultBanTime: 300);
$actionConfig = new ActionRateLimitConfig(limit: 5, interval: 60, banTime: 600);

$configProvider = new InMemoryActionRateLimitConfigProvider(
    $globalConfig,
    ['login' => $actionConfig]
);

// 3. Setup Resolver with our in-memory driver
// We register 'memory' as a driver
$resolver = new RateLimiterResolver([
    'memory' => new InMemoryRateLimiter()
], 'memory');

$limiter = $resolver->resolve('memory');

// 4. Define Action and Platform
$action = new class implements RateLimitActionInterface {
    public function value(): string { return 'login'; }
};

$platform = new class implements PlatformInterface {
    public function value(): string { return 'web'; }
};

// 5. Simulate Requests
echo "🚀 Starting Login Attempts...\n";
echo "---------------------------------\n";

for ($i = 1; $i <= 3; $i++) {
    $status = $limiter->attempt('192.168.1.1', $action, $platform);
    echo "Attempt #{$i}: Allowed ✅ | Remaining: {$status->remaining}\n";
}

echo "---------------------------------\n";
echo "Done.\n";
