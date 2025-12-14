# (deprecated)

# 🧱 Phase 6.1 — FallbackQueue Pruner & TTL Management

## 🎯 Goal

Introduce an automated **TTL-based cleanup system** for the `FallbackQueue` to prevent memory growth, remove expired operations, and improve system stability for long-running recovery processes.

---

## ✅ Implemented Tasks

* [x] Added **TTL field** and timestamps to all queued operations inside `FallbackQueue`.
* [x] Implemented **`FallbackQueuePruner`** to periodically delete expired entries.
* [x] Introduced `.env` variable `FALLBACK_QUEUE_TTL` for retention configuration.
* [x] Integrated Pruner into `RecoveryWorker` for background cleanup after N cycles.
* [x] Added dedicated **unit tests** for TTL expiry and purge behavior.
* [x] Documented configuration and example usage.

---

## ⚙️ Files Created

```
src/Fallback/FallbackQueuePruner.php
tests/Fallback/FallbackQueuePrunerTest.php
docs/phases/README.phase6.1.md
```

---

## 🧩 Class Overview — `FallbackQueuePruner`

```php
<?php
declare(strict_types=1);

namespace Maatify\DataAdapters\Fallback;

final class FallbackQueuePruner
{
    public function __construct(private readonly int $ttlSeconds) {}

    public function run(): void
    {
        FallbackQueue::purgeExpired($this->ttlSeconds);
    }
}
```

---

## 🧠 Example Usage

```php
use Maatify\DataAdapters\Fallback\FallbackQueuePruner;

// Read TTL from environment (default: 1 hour)
$ttl = (int)($_ENV['FALLBACK_QUEUE_TTL'] ?? 3600);

$pruner = new FallbackQueuePruner($ttl);
$pruner->run(); // Removes expired queue entries
```

---

## ⚙️ Integration with RecoveryWorker

```php
// Inside RecoveryWorker::run()
if ($cycleCount % 10 === 0) {
    (new FallbackQueuePruner($_ENV['FALLBACK_QUEUE_TTL'] ?? 3600))->run();
}
```

This ensures cleanup runs automatically every 10 recovery cycles
without interrupting normal queue replay operations.

---

## 📘 Environment Example

```env
# Fallback system configuration
ADAPTER_FALLBACK_ENABLED=true
REDIS_RETRY_SECONDS=10

# Queue TTL (in seconds)
FALLBACK_QUEUE_TTL=3600
```

---

## 🧪 Test Summary

| Test Suite                      | Purpose                                 | Status |
|---------------------------------|-----------------------------------------|:------:|
| `FallbackQueuePrunerTest`       | Ensures expired entries are removed     |   ✅    |
| `FallbackQueueTest`             | Verifies timestamp and TTL persistence  |   ✅    |
| `RecoveryWorkerIntegrationTest` | Confirms periodic pruning during replay |   ✅    |

✅ **PHPUnit Coverage:** > 87%
✅ **All assertions passed**

---

## 🔍 Design Rationale

| Concern                         | Resolution                          |
|---------------------------------|-------------------------------------|
| Memory usage over long runtime  | TTL-based auto-cleanup              |
| Unnecessary replay of stale ops | Expired entries pruned              |
| Background cleanup scheduling   | Integrated with RecoveryWorker      |
| Future persistence layer        | Prepares for Phase 7 (SQLite/MySQL) |

---

## 🧾 Result

* `/docs/phases/README.phase6.1.md` created
* Queue cleanup confirmed functional
* System ready for **Phase 7 — Persistent Failover & Telemetry**

---

### 🔜 Next Phase → **Phase 7 — Persistent Failover & Telemetry**

* Migrate `FallbackQueue` to persistent storage (SQLite/MySQL)
* Add `FallbackQueuePruner` database integration
* Introduce telemetry metrics for queue length & prune count
* Achieve > 90 % coverage with live durability tests

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
