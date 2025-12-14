# (deprecated)

# 🧱 Phase 6.1.1 — RecoveryWorker ↔ Pruner Integration Verification

## 🎯 Goal

Validate that the `FallbackQueuePruner` is automatically triggered by `RecoveryWorker` after every N (= 10) cycles, confirming end-to-end cleanup reliability under live recovery loops.

---

## ✅ Implemented Tasks

* [x] Integrated `FallbackQueuePruner` inside `RecoveryWorker::run()` triggered every 10 cycles.
* [x] Added integration test `RecoveryWorkerIntegrationTest`.
* [x] Verified that expired entries are deleted while valid entries remain intact.
* [x] Ensured TTL priority is per-item (`item['ttl']` > override).

---

## ⚙️ Files Created / Updated

```
src/Fallback/FallbackQueue.php          (TTL priority fix)
tests/Fallback/RecoveryWorkerIntegrationTest.php
docs/phases/README.phase6.1.1.md
```

---

## 🧩 Implementation Highlights

| Component             | Responsibility                                    |
|-----------------------|---------------------------------------------------|
| `FallbackQueue`       | Uses per-item TTL first → global override second. |
| `RecoveryWorker`      | Runs pruner every 10 cycles without blocking.     |
| `FallbackQueuePruner` | Executes `purgeExpired()` with safe TTL fallback. |

---

## 🧪 Integration Test Summary

| Test                            | Purpose                                               | Status |
|---------------------------------|-------------------------------------------------------|:------:|
| `RecoveryWorkerIntegrationTest` | Ensures only fresh queue items remain after 10 cycles |   ✅    |

✅ All assertions passed
✅ Per-item TTL respected
✅ Automatic cleanup confirmed under real loop simulation

---

### 🧩 Example Usage Preview

For practical examples of manual and automatic pruning in action,
see full examples in:

➡️ [`docs/examples/README.fallback.md`](../examples/README.fallback.md)

```php
use Maatify\DataAdapters\Fallback\FallbackQueuePruner;

// Manual run example
$ttl = (int)($_ENV['FALLBACK_QUEUE_TTL'] ?? 3600);
(new FallbackQueuePruner($ttl))->run();
```

Or automatic cleanup inside `RecoveryWorker` every 10 cycles:

```php
if ($cycleCount % 10 === 0) {
    (new FallbackQueuePruner($_ENV['FALLBACK_QUEUE_TTL'] ?? 3600))->run();
}
```

✅ Ensures old fallback operations are removed without manual intervention.
See the full reference and test examples in `README.fallback.md`.

---

## 🧾 Result

* Full integration between `RecoveryWorker` and `FallbackQueuePruner` verified.
* System is now stable for 24/7 operation without memory bloat.
* Phase 6.1.1 ready to merge into `main`.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
