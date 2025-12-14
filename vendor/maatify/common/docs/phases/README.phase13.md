# Phase 13 — Mutable ConnectionConfigDTO Update
**Version:** 1.0.5
**Status:** ✅ Completed
**Category:** Connectivity / DTO Enhancements / Runtime Flexibility
**Date:** 2025-11-13

---

## 🧩 Overview

Phase 13 enhances the `ConnectionConfigDTO` introduced earlier in Phase 11 by **removing the `readonly` modifier**, allowing the configuration to be updated at runtime.

This change supports more advanced workflows inside `maatify/data-adapters`, especially when:

- resolving DSNs dynamically
- injecting environment-driven overrides
- modifying configuration profiles
- supporting advanced connection switching (future failover routing)

In short:
**this phase transforms ConnectionConfigDTO from a static definition into a flexible runtime configuration model.**

---

## 🎯 Goals

- Allow `ConnectionConfigDTO` to support runtime mutation.
- Improve compatibility with complex adapter flows.
- Enable DSN parsing & rewriting after DTO creation.
- Remove rigid immutability to support future failover logic.
- Maintain full backward compatibility.

---

## 📦 Tasks Completed

### **Changes Implemented**

- Removed `readonly` from all properties of `ConnectionConfigDTO`
    - `driver`
    - `dsn`
    - `user`
    - `pass`
    - `options`
    - `profile`
- Ensured the modified DTO remains fully type-safe.
- Updated documentation to reflect the change.
- Confirmed no breaking changes for existing code.

---

## 🗂 Files Updated

```
src/DTO/ConnectionConfigDTO.php
```

---

## 🧪 Tests Updated

- Updated DTO tests to account for mutability.
- Added new test cases:
    - dynamic change of driver
    - dynamic change of DSN
    - merging modified configuration into adapters

### Coverage Result
- **100%** for DTO module
- Fully aligned with dynamic configuration usage patterns

---

## 🧠 Technical Notes

- This update was required because DSN parsing happens *after* the initial environment configuration.
- Many adapters (MySQL, Mongo, Redis) need to adjust final DSN parameters.
- Future features like:
    - profile switching
    - failover routing
    - auto-retry with modified DSN
    - connection downgrade/upgrade
      depend on this DTO being mutable.

- Despite removing `readonly`, the DTO still behaves predictably:
    - strictly typed
    - no side effects
    - controlled mutation

---

## 🔗 Related Versions

- Introduced in **v1.0.5**
- Extends Phase 11 (Connectivity Foundation)
- Required for:
    - maatify/data-adapters v1.1.0+
    - upcoming failover routing (Phase 16 of data-adapters)

---

## 🔜 Next Phase

**Phase 14 — Driver Contract Modernization (v1.0.6)**
Unifies driver return types across all adapters and removes strict type coupling.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/common

---