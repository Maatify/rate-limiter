# Phase 6 — Validation & Filtering Tools
**Version:** 1.0.0
**Status:** ✅ Completed
**Category:** Validation / Data Cleaning / Input Consistency
**Date:** 2025-11-09

---

## 🧩 Overview

Phase 6 introduces a powerful and highly reusable **Validation & Filtering Module**, providing a unified, framework-agnostic validation layer for every Maatify library.

This module ensures that all user input, API payloads, request data, and system-generated values follow a predictable, safe, and well-structured standard before being processed further.

It is designed to be:

- performant
- type-safe
- fully deterministic
- and deeply integrated with the ecosystem

This module later becomes a foundational building block for security guards, request handlers, DTOs, API core modules, and repository-level validation.

---

## 🎯 Goals

- Provide a consistent validation layer used across all maatify components.
- Handle complex input types: email, URL, IP, UUID, phone, numeric values, slugs, and slug paths.
- Introduce array-level filtering tools for preparing API payloads.
- Enable intelligent input type detection.
- Ensure strict validation rules compatible with multilingual systems.
- Prevent invalid or malformed input from reaching internal logic.

---

## 📦 Tasks Completed

### **Validator**
Implemented robust static validators:

- `email()`
- `url()`
- `phone()`
- `uuid()`
- `ip()`
- `integer()`
- `float()`
- `between()`
- `slug()`
- `slugPath()` (with multi-level validation)

Added smart detection:

- `detectType("test@domain.com") → email`
- `detectType("en/products/item") → slug_path`
- `detectType("42") → integer`
- `detectType("3.14") → float`

---

### **Filter**
Added array cleaning utilities:

- `trimArray()`
- `removeEmptyValues()`
- `sanitizeArray()`

Handles:

- whitespace trimming
- null removal
- removal of empty strings
- HTML-safe cleaning with integration from Phase 3

---

### **ArrayHelper**
Provides data manipulation helpers:

- `flatten()` — dot-notation flattening
- `only()` — include specific keys
- `except()` — exclude keys
- dot-path array extraction

This greatly simplifies DTO hydration and request data parsing.

---

## 🗂 Files Created / Updated

### **Source Files**

- `src/Validation/Validator.php`
- `src/Validation/Filter.php`
- `src/Validation/ArrayHelper.php`

### **Directory Structure**

```
src/Validation/
├── Validator.php
├── Filter.php
└── ArrayHelper.php
```

---

## 🧪 Tests Added

### **Test Files**

- `tests/Validation/ValidatorTest.php`
- `tests/Validation/FilterTest.php`
- `tests/Validation/ArrayHelperTest.php`

### **Coverage Highlights**

- Validation of correct & incorrect values for all validators.
- Slug vs slugPath differentiation (multilevel).
- Numeric detection and enforcement.
- Edge-case handling with invalid characters.
- Array filtering rules validated with nested structures.
- Flattening logic validated across multi-depth arrays.

### **Result**
- Coverage: **~100%**
- Zero false-positives for invalid input types.
- Fully deterministic behavior across PHP 8.4.x.

---

## 🧠 Technical Notes

- `detectType()` is intentionally conservative — it avoids guessing when ambiguous.
- Slug and slugPath validation follow multilingual-safe patterns.
- Array filtering integrates seamlessly with the sanitization logic from Phase 3.
- `flatten()` is crucial for logging systems, audit trails, and dynamic DTO mapping.
- The entire module is static and stateless — optimized for performance under heavy request loads.

---

## 🔗 Related Versions

- **Introduced in:** v1.0.0
- Dependency for future:
    - maatify/api-core
    - maatify/security-guard
    - maatify/data-repository
    - maatify/webhook-gateway

---

## 🔜 Next Phase

**Phase 7 — Enums & Constants Standardization**
Introduces global enums, constants, unified helpers, and JSON serialization trait across the entire Maatify ecosystem.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/common

---