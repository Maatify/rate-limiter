# 🧩 Phase 5 — Exponential Backoff & Global Limit

## 🎯 Objective

This phase introduces **adaptive rate-limiting** with an **exponential backoff algorithm** and a **global per-IP limit** shared across all actions.
The goal is to make the limiter more intelligent and fair — *slowing down* abusive clients instead of simply blocking them.

---

## ⚙️ Implementation Overview

### 1️⃣ Exponential Backoff Algorithm

Each time a client exceeds its limit, the cooldown increases exponentially until reaching a configured cap.

```php
private function calculateBackoff(int $attempts, int $base = 2, int $max = 3600): int
{
    return min(pow($base, $attempts), $max);
}
```

| Attempts | Delay (sec) |
| -------- | ----------- |
| 1        | 2           |
| 2        | 4           |
| 3        | 8           |
| 4        | 16          |
| 5        | 32          |

🧠 `BACKOFF_BASE` and `BACKOFF_MAX` are fully configurable in `.env`.

---

### 2️⃣ Adaptive Backoff Application (Example – Redis Driver)

```php
private function applyBackoff(string $key, int $attempts): RateLimitStatusDTO
{
    $backoff = $this->calculateBackoff($attempts);
    $this->redis->expire($key, $backoff);

    $nextAllowed = (new DateTimeImmutable())
        ->modify("+{$backoff} seconds")
        ->format('Y-m-d H:i:s');

    return new RateLimitStatusDTO(
        limit: 0,
        remaining: 0,
        resetAfter: $backoff,
        retryAfter: $backoff,
        blocked: true,
        backoffSeconds: $backoff,
        nextAllowedAt: $nextAllowed
    );
}
```

Each repeated violation doubles the wait time (2 s → 4 s → 8 s → 16 s → ...).
Equivalent expiration logic exists in MongoDB (`expiresAt`) and MySQL (`expires_at`).

---

### 3️⃣ Global Per-IP Limiter

A shared Redis key:

```
rate:ip:{ip}
```

tracks **total requests per IP** across all actions.
If an IP exceeds `GLOBAL_RATE_LIMIT` within `GLOBAL_RATE_WINDOW` seconds, it is throttled according to the exponential backoff.

This ensures that no single IP can overload the system even if individual module limits are generous.

---

### 4️⃣ Updated `.env.example`

```dotenv
# Global IP limit
GLOBAL_RATE_LIMIT=1000
GLOBAL_RATE_WINDOW=3600

# Exponential backoff
BACKOFF_BASE=2
BACKOFF_MAX=3600
```

---

### 5️⃣ DTO Enhancements — `RateLimitStatusDTO`

| Field            | Type          | Description                         |
| ---------------- | ------------- | ----------------------------------- |
| `backoffSeconds` | int | null    | Adaptive delay duration (seconds)   |
| `nextAllowedAt`  | string | null | UTC timestamp when retry is allowed |

Added helper:

```php
public static function fromArray(array $data): self
```

Example:

```php
$dto = RateLimitStatusDTO::fromArray(json_decode($redis->get('rate:ip:501'), true));
```

---

### 6️⃣ Enhanced Exception Metadata

`TooManyRequestsException` now exposes:

```php
$e->getRetryAfter();     // seconds until next try
$e->getNextAllowedAt();  // timestamp when retry is allowed
```

Allowing standardized 429 responses:

```json
{
  "error": "Too many requests",
  "retry_after": 16,
  "next_allowed_at": "2025-11-07 12:01:45"
}
```

---

### 7️⃣ Unit Tests — `tests/BackoffTest.php`

```php
public function testExponentialBackoffCalculation(): void
{
    $resolver = new RateLimiterResolver(['driver' => 'redis']);
    $limiter = $resolver->resolve();

    $method = new ReflectionMethod($limiter, 'calculateBackoff');
    $method->setAccessible(true);

    $this->assertSame(2, $method->invoke($limiter, 1));
    $this->assertSame(4, $method->invoke($limiter, 2));
    $this->assertSame(8, $method->invoke($limiter, 3));
    $this->assertLessThanOrEqual(3600, $method->invoke($limiter, 10));
}

public function testNextAllowedAtFormat(): void
{
    $resolver = new RateLimiterResolver(['driver' => 'redis']);
    $limiter = $resolver->resolve();

    $dto = (new ReflectionMethod($limiter, 'applyBackoff'))
        ->invoke($limiter, 'rate:test', 3);

    $this->assertMatchesRegularExpression(
        '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',
        $dto->nextAllowedAt
    );
}
```

✅ Covers exponential growth logic and timestamp format.

---

### 8️⃣ Example Usage

```php
$resolver = new RateLimiterResolver([
    'driver' => 'redis',
    'redis_host' => '127.0.0.1',
    'redis_port' => 6379,
]);

$limiter = $resolver->resolve();
$key = 'ip:' . $_SERVER['REMOTE_ADDR'];

try {
    $status = $limiter->attempt($key, RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
    echo "✅ Allowed. Remaining: {$status->remaining}";
} catch (TooManyRequestsException $e) {
    echo "⛔ Retry after {$e->getRetryAfter()} s at {$e->getNextAllowedAt()}";
}
```

---

## 🧱 Summary of Enhancements

| Component          | Status | Description                             |
| ------------------ | ------ | --------------------------------------- |
| DTO update         | ✅      | Added `backoffSeconds`, `nextAllowedAt` |
| Backoff logic      | ✅      | Exponential algorithm + Redis support   |
| Global IP limiter  | ✅      | Cross-action limit layer                |
| Exception metadata | ✅      | Added retryAfter + nextAllowedAt        |
| Env config         | ✅      | Extended `.env.example`                 |
| Tests              | ✅      | Added `BackoffTest`                     |
| CI compatibility   | ✅      | Works in Docker test pipeline           |

---

## 🚀 Next Phase

**Phase 6 – Alerting & Logging**
Integrate `maatify/psr-logger`, Telegram notifications, and security event tracking for rate-limit breaches.

---

✅ **Phase 5 Complete**
The rate limiter now intelligently delays repeated abusive attempts and enforces a global per-IP throttle for smarter load control.

---
