# Phase 2 — Locking System
**Version:** 1.0.0
**Status:** ✅ Completed
**Category:** Core Concurrency & Safe Execution
**Date:** 2025-11-09

---

## 🧩 Overview

Phase 2 introduces the **Locking System**, one of the most critical foundational pillars in the Maatify ecosystem.
It provides safe, predictable, and cross-platform locking mechanisms to ensure:

- Cron jobs do not overlap
- Queue workers do not execute the same job concurrently
- Distributed tasks across multiple servers stay synchronized
- Commands and scheduled tasks maintain consistency
- Race conditions are prevented in API-sensitive flows

The system is designed to be **smart**, **resilient**, and **environment-agnostic**, supporting local development (file locks), production/load-balanced environments (Redis locks), and hybrid fallback logic.

---

## 🎯 Goals

- Provide a unified lock interface for all services.
- Support both **local locking (file-based)** and **distributed locking (Redis)**.
- Implement automatic fallback logic through `HybridLockManager`.
- Offer safe queue mode (`QUEUE`) and execution mode (`EXECUTION`).
- Prevent race conditions in:
  - cron jobs
  - queue consumers
  - long-running tasks
  - sensitive API flows
- Ensure compatibility with PSR-3 logging and error reporting.
- Achieve strong test coverage across all lock modes.

---

## 📦 Tasks Completed

- Implemented `LockInterface` defining the universal lock contract.
- Added `FileLockManager` including stale lock cleanup and TTL handling.
- Added `RedisLockManager` compatible with both:
  - php-redis extension
  - Predis client
- Implemented `HybridLockManager` with:
  - Redis-first strategy
  - Graceful fallback to file locking
  - Queue mode retry logic
- Added `LockCleaner` to remove orphan `.lock` files.
- Implemented enum `LockModeEnum` for explicit lock mode selection.
- Full integration tests ensuring expected behavior.

---

## 🗂 Files Created / Updated

### **Source Files**
- `src/Lock/LockInterface.php`
- `src/Lock/LockModeEnum.php`
- `src/Lock/FileLockManager.php`
- `src/Lock/RedisLockManager.php`
- `src/Lock/HybridLockManager.php`
- `src/Lock/LockCleaner.php`

### **Directory Structure**

```
src/Lock/
├── LockInterface.php
├── LockModeEnum.php
├── FileLockManager.php
├── RedisLockManager.php
├── HybridLockManager.php
└── LockCleaner.php
```

---

## 🧪 Tests Added

### **Test Files**
- `tests/Lock/FileLockManagerTest.php`
- `tests/Lock/RedisLockManagerTest.php`
- `tests/Lock/HybridLockManagerTest.php`
- `tests/Lock/LockCleanerTest.php`

### **Coverage Highlights**
- File lock acquisition & release
- Handling stale locks
- TTL expiration behavior
- Redis locking (with mock clients)
- Fallback behavior when Redis fails
- Queue mode retry logic
- Log entries generated for lock failures

### **Result**
- Coverage: **~98% for Locking module**
- Fully stable under PHP 8.4+

---

## 🧠 Technical Notes

- `HybridLockManager` automatically detects Redis availability without throwing fatal errors.
- All lock managers generate structured log messages through `maatify/psr-logger`.
- File locks use a secure `.lock` file pattern with embedded metadata.
- Redis lock implementation uses the safe atomic `SET NX EX` pattern.
- Queue mode introduces small controlled sleep intervals to avoid CPU spikes.
- TTL defaults to 300s but can be customized per lock instance.
- File locks are located in a safe temp directory by default but can be overridden.

---

## 🔗 Related Versions

- **Introduced in:** `v1.0.0`
- **Improved in:** `v1.0.1` (PSR logger fallback)
- **No breaking changes** since introduction.

---

## 🔜 Next Phase

**Phase 3 — Security & Input Sanitization**
Adds secure HTML sanitization, safe input handling, and purification tools compatible with array, string, and mixed data types.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/common

---