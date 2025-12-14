# Phase 3B — Core Traits: Singleton System
**Version:** 1.0.0
**Status:** ✅ Completed
**Category:** Core Architecture / Traits
**Date:** 2025-11-09

---

## 🧩 Overview

Phase 3B introduces the **Singleton System**, implemented as a reusable trait shared across multiple components in the Maatify ecosystem.

The goal of this phase is to eliminate repetitive singleton boilerplate code and guarantee a predictable, controlled, and test-friendly implementation for all services that require a global shared instance.

By providing a standard `SingletonTrait`, the architecture becomes cleaner, safer, and far more maintainable — especially for configuration managers, environment loaders, helper registries, and adapters that are designed to operate as a single instance across the application lifecycle.

---

## 🎯 Goals

- Provide a universal singleton implementation compatible with strict typing.
- Prevent duplicated instances across multiple calls.
- Remove boilerplate code from classes requiring a singleton behavior.
- Ensure the singleton mechanism remains compatible with:
    - dependency injection
    - service locators
    - static factory patterns
- Support test environments by allowing controlled resets.
- Encourage consistent behavior across all maatify libraries.

---

## 📦 Tasks Completed

- Implemented `SingletonTrait` with:
    - `obj()` — retrieves the singleton instance.
    - `reset()` — resets the instance (testing/debugging).
    - Private constructor, clone, and wakeup prevention.
- Ensured full strict typing and PSR-12 compatibility.
- Added default type enforcement to allow only one instance per class.
- Integrated the trait with other core classes within the library.

---

## 🗂 Files Created / Updated

### **Source Files**
- `src/Traits/SingletonTrait.php`

### **Directory Structure**

```
src/Traits/
└── SingletonTrait.php
```

---

## 🧪 Tests Added

### **Test Files**
- `tests/Traits/SingletonTraitTest.php`

### **Coverage Highlights**
- Ensures exactly one instance is created per class.
- Verifies prevention of:
    - manual instantiation
    - cloning
    - unserialization
- Confirms `reset()` clears the singleton cleanly.
- Stress-tested under concurrent calls.

### **Result**
- Coverage: **100%**

---

## 🧠 Technical Notes

- The design intentionally avoids magic statics used by many PHP frameworks to keep the system predictable and dependency-injection-friendly.
- `reset()` is **only** intended for:
    - PHPUnit testing
    - controlled reinitialization during bootstrapping
- All classes using the SingletonTrait should implement their business logic normally; only instantiation is being managed.
- Trait can be safely used on:
    - configuration managers
    - adapters
    - helper registries
    - factories
- Guarantees no accidental recursive instantiation.

---

## 🔗 Related Versions

- **Introduced in:** v1.0.0
- Forms part of the core architectural backbone of the library.

---

## 🔜 Next Phase

**Phase 4 — Text & Placeholder Utilities**
Adds formatting utilities, placeholder rendering, regex tools, and timing-safe comparison helpers.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/common

---