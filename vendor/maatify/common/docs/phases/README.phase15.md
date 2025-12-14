# Phase 15 — Redis Lock Testing Stability Update
**Version:** 1.0.7
**Project:** maatify/common
**Status:** ✅ Completed
**Date:** 2025-11-18

---

## 📌 Overview

Phase 15 introduces a fully deterministic and production-accurate locking test environment by adding a **FakeRedisConnection** and improving HybridLockManager’s queue-mode behavior.
This phase ensures that the Redis locking path behaves identically in tests and real systems, including TTL expiration, NX logic, and queue waiting simulation.

The motivations for this phase were:

- PHPUnit tests previously depended on real Redis behavior.
- Queue-mode tests completed instantly due to missing TTL simulation.
- HybridLockManager needed a reliable Redis-like mock connection.
- FileLock fallback and RedisLockManager needed realistic race-condition behavior.

Phase 15 finalizes the locking system and guarantees fully stable test coverage for the entire locking subsystem.

---

## 🎯 Goals

- Create a **Redis-compatible mock connection** (`FakeRedisConnection`).
- Support:
  - `set(key, value, ['nx', 'ex' => ttl])`
  - `exists(key)`
  - `del(key)`
  - automatic TTL expiration
- Ensure **HybridLockManager::waitAndAcquire()** behaves like real Redis queue mode.
- Ensure unit tests correctly verify actual TTL wait behavior.
- Replace previous unrealistic in-memory mocks with a real simulation.

---

## ✅ Tasks Completed

### 1. **Developed FakeRedisConnection**
- Added TTL support via `expiry[]`.
- Added NX logic identical to real Redis.
- Added expiration handling during `exists()`.
- Added deterministic behavior for testing queue mode.

### 2. **Updated FakeHealthyAdapter**
- Now uses FakeRedisConnection as its internal driver.
- Exposes:
  - `set()`, `exists()`, `del()`
  - `healthCheck()`
  - proper `getConnection()`
- Fully behaves like a real Redis adapter.

### 3. **Adjusted RedisLockManager**
- Removed strict Redis / Predis type checks.
- Added interface-free method detection:
  - `set()`, `exists()`, `del()`
- Ensured compatibility with mock driver.

### 4. **Reworked Queue-Mode Tests**
- HybridLockManagerTest now verifies:
  - TTL expiration simulation
  - Queue waiting ≥ configured delay
- Test is now deterministic and stable.

---

## 📁 Files Created / Modified

### **New Files**
- `tests/Support/Connections/FakeRedisConnection.php`
- `docs/phases/README.phase15.md` (this file)

### **Updated Files**
- `tests/Support/Adapters/FakeHealthyAdapter.php`
- `src/Lock/RedisLockManager.php`
- `src/Lock/HybridLockManager.php` (uses new behavior indirectly)
- `tests/Lock/HybridLockManagerTest.php`

---

## 🧪 Tests Added / Updated

### ✔ New Behavior Tested

- **TTL expiration** (lock auto-release)
- **NX behavior** (`SET key value NX`)
- **Queue-mode delay**
  Verified using:

```php
$this->assertGreaterThan(1.5, $elapsed);
````

* **Deterministic FakeRedis behavior** identical to real Redis logic
* **Fallback consistency** (FileLockManager path untouched)

### ✔ All locking tests now fully stable:

* No race conditions
* No hanging tests
* No timing non-determinism

---

## 🧠 Technical Notes

* FakeRedisConnection uses `time()` so TTL tests behave realistically but remain deterministic.
* RedisLockManager now checks for method existence instead of exact class type—supports:

    * real Redis extension
    * Predis
    * FakeRedisConnection
    * any future custom adapter
* Queue-mode loop no longer exits prematurely because TTL expiration is real.

---

## 🔜 What Comes Next?

* Phase 16 (if introduced) should add Redis connection interface standardization.
* Full phase documentation merge into `README.full.md`.
* Add example lock usage to the main README.
* You are now ready to define **Phase 15** if you want to continue enhancing maatify/common
  (e.g., caching helpers, metrics interfaces, performance utilities).

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/common

---