# (deprecated)

---
![**Maatify.dev**](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)
---
## ⚙️ Maatify Data-Adapters
**Phase ID:** 6
**Title:** Fallback Intelligence & Recovery
**Version:** 1.0.0-alpha
**Maintainer:** Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))
**Date:** 2025-11-11
**Status:** ✅ Completed (Tests Passed & Integration Ready)

---

### 🧩 Objective
To introduce a **robust automatic recovery mechanism** across all adapters (Redis, Mongo, MySQL).
This phase ensures that transient connection failures are gracefully handled through
a shared `FallbackManager` and `FallbackQueue` architecture.

---

### 🧱 Core Components

| Component          | Responsibility                                                                |
|--------------------|-------------------------------------------------------------------------------|
| `BaseAdapter`      | Centralized fallback handling via `handleFailure()` method.                   |
| `FallbackQueue`    | Temporary in-memory queue for failed operations (extendable to SQLite/MySQL). |
| `FallbackManager`  | Monitors adapter health and switches between primary ↔ fallback modes.        |
| `RecoveryWorker`   | Background worker that replays queued operations once the primary recovers.   |
| `DatabaseResolver` | Factory responsible for adapter instantiation and active resolution.          |

---

### 🧪 Testing Summary

| Test Suite                              | Purpose                                                                           | Status   |
|-----------------------------------------|-----------------------------------------------------------------------------------|----------|
| **Core → BaseAdapterTest**              | Validates protected `handleFailure()` behavior & queue integration                | ✅ Passed |
| **Adapters → RedisAdapterFallbackTest** | Ensures Redis fails gracefully and activates fallback without throwing exceptions | ✅ Passed |
| **Fallback → RecoveryWorkerTest**       | Confirms automatic replay of queued operations after recovery                     | ✅ Passed |

**PHPUnit Coverage:** > 85%
**Assertions:** All passing
**No exceptions thrown during stress tests**

---

### 🔍 Design Highlights

- Protected fallback logic to enforce encapsulation (`handleFailure()` tested via Reflection).
- Reflection-based unit testing pattern for non-public methods to preserve API integrity.
- Unified queue lifecycle (`enqueue → drain → purge → clear`).
- Adapter-agnostic recovery workflow with future SQLite/MySQL support.
- Separation of concerns between resolvers, workers and diagnostics.

---

### 📦 Artifacts Generated

| File                                          | Description                            |
|-----------------------------------------------|----------------------------------------|
| `src/Fallback/FallbackQueue.php`              | In-memory queue implementation         |
| `src/Fallback/FallbackManager.php`            | Health monitor & activation controller |
| `src/Fallback/RecoveryWorker.php`             | Continuous queue replayer worker       |
| `tests/Core/BaseAdapterTest.php`              | Reflection-based unit test             |
| `tests/Fallback/RecoveryWorkerTest.php`       | Recovery simulation test               |
| `tests/Adapters/RedisAdapterFallbackTest.php` | Redis connection fallback test case    |

---

## 🗂 File Structure

```
src/
 ├─ Core/
 │   └─ DatabaseResolver.php
 ├─ Adapters/
 │   ├─ RedisAdapter.php
 │   └─ PredisAdapter.php
 ├─ Fallback/
 │   ├─ FallbackManager.php
 │   ├─ FallbackQueue.php
 │   └─ RecoveryWorker.php
 └─ Diagnostics/
     └─ AdapterFailoverLog.php
```

---

## 📘 .env Example

```env
REDIS_PRIMARY_HOST=127.0.0.1
REDIS_FALLBACK_DRIVER=predis
REDIS_RETRY_SECONDS=10
FALLBACK_QUEUE_DRIVER=sqlite
ADAPTER_LOG_PATH=/var/logs/maatify/adapters/
```
---
> *See detailed example in [docs/examples/README.fallback.md](../examples/README.fallback.md)”*

---

### 📜 Next Step → **Phase 7 — Persistent Failover & Telemetry**

In the next phase:
* Extend `FallbackQueue` to persistent storage (SQLite/MySQL).
* Add `FallbackQueuePruner` for TTL-based cleanup.
* Integrate real-time telemetry with maatify/psr-logger and maatify/mongo-activity.
* Target coverage → **> 90%** with stress test metrics and load simulation.

---

🧱 **Maatify Ecosystem Integration:**
This phase completes the reliability layer within `maatify/data-adapters`,
ready for direct use by `maatify/rate-Limiter`, `maatify/security-guard`, and `maatify/bootstrap`.


---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
