# Phase 5 — Fake Repository Layer

**Version:** 1.0.1
**Project:** maatify/data-fakes
**Status:** Completed
**Date:** 2025-11-22T08:00:00+02:00

---

## 🎯 Goals

* Implement a FakeRepository compatible with the shared `RepositoryInterface`
* Provide a lazy, iterable `FakeCollection` structure
* Implement `ArrayHydrator` for array → DTO hydration
* Support CRUD operations: `insert`, `find`, `findBy`, `findAll`, `update`, `delete`
* Ensure behavior matches repository conventions used across Maatify libraries
* Achieve full PHPUnit coverage and PHPStan compliance

---

## 📁 Deliverables

### Repository Layer

```
src/Repository/FakeRepository.php
src/Repository/Collections/FakeCollection.php
src/Repository/Hydration/ArrayHydrator.php
```

### Tests

```
tests/Repository/FakeRepositoryTest.php
```

---

## 🧠 Architecture Overview

### **FakeRepository**

* Implements `RepositoryInterface`
* Provides consistent CRUD API:

    * `insert()`
    * `find()`
    * `findBy()`
    * `findAll()`
    * `update()`
    * `delete()`
* Uses `FakeStorageLayer` for in-memory persistence
* Supports adapter injection via `setAdapter()` / `getAdapter()`
* Behaves like real repositories from `maatify/data-repository`

### **FakeCollection**

* Lazy iterable wrapper for query results
* Implements:

    * `IteratorAggregate`
    * `Countable`
    * `ArrayAccess`
* Immutable (offsetSet/offsetUnset throw exceptions)
* Supports DTO hydration when paired with `ArrayHydrator`

### **ArrayHydrator**

* Hydrates associative arrays into DTO objects
* Accepts `class-string` and array data
* Uses reflection to construct DTO-like objects
* Compatible with Maatify naming conventions: all DTOs end with `DTO`

---

## 🔌 Integration Notes

### Repository & Storage Integration

* Uses `FakeStorageLayer` from Phase 1 for deterministic in-memory storage
* Supports mixed numeric/string identifiers (auto-increment or Mongo-style `_id`)
* Filters normalized for consistent behavior across MySQL/DBAL/Mongo fake adapters

### Resolver Integration

* Works with a `FakeResolver` implementing `ResolverInterface`
* Allows routing to FakeMySQL, FakeDBAL, FakeRedis, or FakeMongo adapters

---

## 📌 Core Dependencies

* `RepositoryInterface` (maatify/data-repository)
* `AdapterInterface` (maatify/common)
* `ResolverInterface` (maatify/data-adapters)
* `FakeStorageLayer` (maatify/data-fakes phase1 foundation)

---

## 🧪 Testing Summary

* Tests cover:

    * Full CRUD operations
    * Collection behavior
    * DTO hydration
    * Filter logic
* Command:

  ```
  composer run-script test
  ```
* Coverage: **100%**
* PHPStan Level: **0 errors**

---

## 📜 Commit Message

```
feat(phase5): implement FakeRepository layer with FakeCollection, ArrayHydrator, resolver integration, and full CRUD behavior
```

---

## 📦 Files Generated

* README.phase5.md
* phase-output.json
* src/Repository/FakeRepository.php
* src/Repository/Collections/FakeCollection.php
* src/Repository/Hydration/ArrayHydrator.php
* tests/Repository/FakeRepositoryTest.php

---
