# 🧩 Phase 3 – Storage Drivers

**Goal:**  
Implement the three storage drivers for the Maatify Rate Limiter library:  
Redis, MongoDB, and MySQL — all following `RateLimiterInterface`.

---

## 🎯 Objective

To provide interchangeable, PSR-compliant storage backends for the rate limiting system.

---

## ✅ Tasks Completed

- Implemented **RedisRateLimiter**  
  → Uses INCR + EXPIRE logic for atomic request counting  
- Implemented **MongoRateLimiter**  
  → Uses TTL index and count tracking per unique key  
- Implemented **MySQLRateLimiter**  
  → Stores limits in `ip_rate_limits` table with ON DUPLICATE KEY logic  
- Added base **unit tests** for configuration and logic  
- Integrated `TooManyRequestsException` for overload protection

---

## 📂 Files Created

```

src/Drivers/
├── RedisRateLimiter.php
├── MongoRateLimiter.php
└── MySQLRateLimiter.php

tests/
└── DriversTest.php

````

---

## 🧪 Usage Example

```php
use Maatify\RateLimiter\Drivers\RedisRateLimiter;
use Redis;

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$limiter = new RedisRateLimiter($redis);
$status = $limiter->attempt('192.168.1.10', 'login', 'web');

print_r($status->toArray());
````

---

## 📊 Result Summary

| Driver  | Status     | Description                             |
|---------|------------|-----------------------------------------|
| Redis   | ✅ Complete | Atomic, Fast, TTL-based                 |
| MongoDB | ✅ Complete | Document-based storage with expiry      |
| MySQL   | ✅ Complete | Persistent relational store             |
| Tests   | ✅ Passed   | Configuration and driver logic verified |

---

## 🧩 Version

```
1.0.0-alpha-phase3
```

---

## 📜 Notes

This phase establishes the core persistence layer for the rate limiter.
Each driver implements `RateLimiterInterface` and returns consistent `RateLimitStatusDTO` output.
The next phase will handle the **Resolver & Middleware integration**.


---


