# Native PHP Examples (Phase 5)

[![Maatify Rate Limiter](https://img.shields.io/badge/Maatify-Rate--Limiter-blue?style=for-the-badge)](https://github.com/Maatify/rate-limiter)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

# Phase 5 — Conceptual & Behavioral Examples

⚠️ These examples are EDUCATIONAL.
They demonstrate behavior and contracts,
not full production wiring.

For production usage, see:
- Real Redis / MySQL / Mongo drivers
- Framework integrations

This document contains standalone, copy-paste runnable PHP examples for the Phase 5 Rate Limiter implementation.

**Requirements:**
- PHP >= 8.4
- `vendor/autoload.php` available (run `composer install`)

---

## 1. Action-based Rate Limit
**Purpose:** Shows how to configure and enforce rate limits for specific actions (e.g., login).
**File:** `examples/phase5/native/action_rate_limit.php`

/**
* ⚠️ EDUCATIONAL EXAMPLE
* This example demonstrates behavior only.
* Not intended for production usage.
  */
```php
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
```

---

## 2. Global IP Block
**Purpose:** Demonstrates the Global Limiter layer (EnforcingRateLimiter) catching excessive requests before they reach specific actions.
**File:** `examples/phase5/native/global_ip_block.php`

/**
* ⚠️ EDUCATIONAL EXAMPLE
* This example demonstrates behavior only.
* Not intended for production usage.
  */
```php
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
```

---

## 3. Exponential Backoff Evolution
**Purpose:** Visualizes how wait times increase (2^n) as repeated attempts are made over the limit.
**File:** `examples/phase5/native/exponential_backoff.php`

/**
* ⚠️ EDUCATIONAL EXAMPLE
* This example demonstrates behavior only.
* Not intended for production usage.
  */
```php
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
```

---

## 4. DTO Serialization
**Purpose:** Shows how to convert the status object to/from arrays, useful for API responses or session storage.
**File:** `examples/phase5/native/dto_serialization.php`

/**
* ⚠️ EDUCATIONAL EXAMPLE
* This example demonstrates behavior only.
* Not intended for production usage.
  */
```php
<?php

/**
 * 🎯 DTO Serialization Example
 *
 * Demonstrates:
 * - Creating a RateLimitStatusDTO.
 * - Converting it to an array for JSON responses.
 * - Recreating the DTO from an array (useful for caching/storage).
 * - Verifying data integrity.
 */

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;

echo "📦 RateLimitStatusDTO Serialization\n";
echo "-----------------------------------\n";

// 1. Create original DTO
$original = new RateLimitStatusDTO(
    limit: 100,
    remaining: 42,
    resetAfter: 300,
    retryAfter: null,
    blocked: false,
    backoffSeconds: null,
    nextAllowedAt: '2025-12-31 23:59:59',
    source: 'action'
);

// 2. Convert to Array
$asArray = $original->toArray();

echo "1. Serialized to JSON:\n";
echo json_encode($asArray, JSON_PRETTY_PRINT) . "\n\n";

// 3. Recreate from Array
$restored = RateLimitStatusDTO::fromArray($asArray);

echo "2. Restored DTO Verification:\n";
echo "Limit matches: " . ($original->limit === $restored->limit ? "✅" : "❌") . "\n";
echo "Remaining matches: " . ($original->remaining === $restored->remaining ? "✅" : "❌") . "\n";
echo "Next Allowed At matches: " . ($original->nextAllowedAt === $restored->nextAllowedAt ? "✅" : "❌") . "\n";

// 4. Equality Check
if ($original == $restored) {
    echo "\n🎉 Success: Objects are equivalent.\n";
} else {
    echo "\n⚠️ Objects differ.\n";
}
```

---

## 5. Custom Backoff Policy
**Purpose:** Demonstrates how to inject a custom BackoffPolicy (e.g., Linear) instead of the default Exponential one.
**File:** `examples/phase5/native/custom_backoff_policy.php`

/**
* ⚠️ EDUCATIONAL EXAMPLE
* This example demonstrates behavior only.
* Not intended for production usage.
  */
```php
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
```
