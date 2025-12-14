# Phase 6 — Fake Unit of Work + Snapshot Engine
**Version:** 1.0.2
**Project:** maatify/data-fakes
**Status:** Completed
**Date:** 2025-11-22T12:00:00+00:00

---

## 🎯 Goals
- Implement `FakeUnitOfWork` for atomic transactional grouping.
- Provide `SnapshotManager` + `SnapshotState` for full save/restore cycles.
- Support rollback/commit for any adapter sharing `FakeStorageLayer`.
- Support **nested transactions** using stacked snapshots.
- Ensure UoW is adapter-agnostic and storage-driven.
- Add PHPUnit coverage for:
    - Successful commits
    - Rollbacks
    - Nested begin/commit/rollback
    - `transactional()` callback behavior

---

## 📁 Deliverables
- `src/Repository/FakeUnitOfWork.php`
- `src/Storage/Snapshots/SnapshotManager.php`
- `src/Storage/Snapshots/SnapshotState.php`
- `tests/Repository/FakeUnitOfWorkTest.php`
- `tests/Storage/SnapshotManagerTest.php`

---

## 🧠 Architecture Summary
### 🔹 SnapshotManager
Responsible for:
- Creating immutable snapshots of:
    - All FakeStorageLayer tables
    - Auto-increment counters
- Restoring full state on rollback

### 🔹 SnapshotState
Immutable DTO containing:
- `tables: array<string, array<int|string, array<string,mixed>>>`
- `autoIds: array<string,int>`

### 🔹 FakeUnitOfWork
- Maintains **snapshot stack** for nested transactions
- `begin()` → pushes snapshot
- `commit()` → pops snapshot without restoring
- `rollback()` → restores snapshot then pops it
- `transactional()` → helper wrapper with automatic commit/rollback

### 🔹 Key Features
- No adapter-specific logic
- Instant rollback support
- Deterministic storage behavior
- Fully compatible with FakeMySQL, FakeMongo, FakeRedis adapters

---

## 🔌 Integration Notes
- All adapters using the same `FakeStorageLayer` instance naturally fall under the same UoW.
- No interface changes required for adapters.
- Works seamlessly with repository layer.

---

## 🧪 Tests
Run individual test suites:

```bash
composer run-script test -- --filter FakeUnitOfWorkTest
composer run-script test -- --filter SnapshotManagerTest
```

Both suites validate:
- Snapshot creation
- Snapshot restoration
- Transaction nesting
- Exception handling during `transactional()`

---

## 📜 Commit Message
```
feat(phase6): add unit of work and snapshot engine with rollback support
```
