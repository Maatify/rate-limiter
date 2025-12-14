# Phase 10 — TapHelper Utility Introduction
**Version:** 1.0.2
**Status:** ✅ Completed
**Category:** Helpers / Developer Experience / Fluent APIs

---

## 🧩 Overview

Phase 10 introduces `TapHelper`, a lightweight functional-style helper designed to improve the fluidity and readability of object initialization across the entire Maatify ecosystem.

Inspired by modern functional programming patterns, this helper enables developers to apply an inline callback function to any value or object — while returning the original value unchanged.

This drastically reduces boilerplate during service setup, adapter configuration, and chained initialization flows.

---

## 🎯 Goals

- Enhance developer experience (DX) across all maatify libraries.
- Introduce functional-style initialization without side effects.
- Allow expressive object bootstrapping in one line.
- Replace common multi-step setup sequences with a single clean statement.
- Ensure strict typing and PSR-12 compliance.

---

## 📦 Tasks Completed

### **Key Features of TapHelper**

- Added `TapHelper::tap(mixed $value, callable $callback): mixed`
- Executes a callback with the given value.
- Returns the value unchanged (supports objects, arrays, scalars).
- Provides uniform fluent initialization across adapters, services, and factories.

### **Improvements**

- Added internal documentation describing functional philosophy.
- Ensured compatibility with:
    - MySQL / Mongo / Redis Adapters
    - DTO pipelines
    - Configuration loaders
    - Bootstrap operations

---

## 🗂 Files Added

```
src/Helpers/TapHelper.php
```

---

## 🧪 Tests Added

### Test Files
```
tests/Helpers/TapHelperTest.php
```

### Test Coverage Highlights

- Ensures the returned value is identical to the original instance.
- Verifies callback execution.
- Validates scalar & array support.
- Confirms immutability (tap does not alter the returned object).
- Confirms compatibility with adapter connection setup flows.

### Coverage Result
- **100%** coverage
- Fully stable across PHP 8.4.x

---

## 🧠 Technical Notes

- `TapHelper` is purely functional — it returns the value, not the callback result.
- Especially useful when initializing adapters:

```php
$mysql = TapHelper::tap(
    new MySQLAdapter($config),
    fn($a) => $a->connect()
);
```

- Helps avoid unused temporary variable assignments.
- Provides a standard initialization pattern across all Maatify libraries.

---

## 🔗 Related Versions

- Introduced in **v1.0.2**
- Fully backward compatible with earlier releases.
- Frequently used in:
    - data-adapters
    - messaging-core
    - repository layer
    - bootstrap initialization

---

## 🔜 Next Phase

**Phase 11 — Connectivity Foundation (ConnectionConfigDTO + ConnectionTypeEnum) (v1.0.3)**
A standardized configuration and driver abstraction layer shared across all ecosystem libraries.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/common

---