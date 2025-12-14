# 🧩 Phase 16 — MySQL DBAL Stability Patch + DSN Hardening
**Version:** 1.2.1
**Status:** ✅ Completed
**Scope:** Internal stability, DSN resilience, CI reliability
**Date:** 2025-11-17

---

## 🎯 Overview

Phase 16 delivers a critical stabilization layer applied after `v1.2.0`.
It ensures **predictable MySQL DBAL initialization**, enforces **TCP-only
communication**, resolves **CI environment failures**, and hardens the **DSN
parsing pipeline**—especially for complex passwords and unescaped symbols.

This patch guarantees the same behavior across:

- Local development
- Docker
- GitHub Actions CI
- Production servers

All DBAL, PDO, and Doctrine DSN paths now unify into a **single consistent,
fully validated normalization layer**.

---

## 🥅 Goals

- Enforce **TCP mode** for all DBAL MySQL connections
- Disable **unix_socket fallback** everywhere
- Fix **CI MySQL connection failure** (`SQLSTATE[HY000] [2002]`)
- Harden DSN parsing for **complex passwords** & special characters
- Standardize DSN → DTO normalization for both **DBAL** and **PDO** adapters
- Stabilize **RawAccessTest** behavior for all adapters across CI

---

## 🛠️ Tasks Completed

### 🔧 DBAL / MySQL Connection Stability
- Added `"unix_socket" => null` to all DBAL connection parameter structures
- Forced `host=127.0.0.1` (never `"localhost"`) in DBAL mode
- Patched `MySQLDbalAdapter` connection builder for CI-safe initialization

### 🧠 DSN Parsing & Sanitization
- Rewrote DSN sanitizer to prevent stripping of `?` inside passwords
- Improved Doctrine URL DSN parser to support **unescaped special symbols**
- Normalized DSN field inheritance inside `MySqlConfigBuilder`
- Unified DSN → DTO resolution for PDO/DBAL consistency

### 🧪 Testing & CI Reliability
- Updated `RawAccessTest` to respect CI DSN overrides
- Ensured stable execution for:
    - PDO adapter
    - DBAL adapter
    - Mongo adapter
    - Redis adapter

- Improved `DatabaseResolver` routing order prior to `getDriver()`

---

## ✅ Changes Summary

### 🐞 Fixed
- DBAL socket fallback causing:
  `SQLSTATE[HY000] [2002] No such file or directory`
- Doctrine DSN failures with passwords containing symbols
- PDO ↔ DBAL DSN mismatches
- Legacy DSN parser truncating values after `?`
- Missing host/user/pass during legacy DSN fallback

### 🔄 Updated
- `MySQLDbalAdapter` connection parameter logic
- `MySqlConfigBuilder` merging algorithm
- `MysqlDsnParser` sanitization pipeline
- `RawAccessTest` CI behavior and DSN override support

---

## 📌 Outcome

Phase 16 ensures:

- **Total DBAL/PDO/Doctrine consistency**
- **Bulletproof DSN parsing**
- **Stable CI without intermittent MySQL failures**
- **Predictable behavior across all environments**
- A fully hardened foundation before Phase 17+

This completes the stabilization milestone required before expanding the adapter system further.

---

## 📁 Files Impacted

- `Core/Config/MySqlConfigBuilder.php`
- `Core/Parser/MysqlDsnParser.php`
- `Adapters/MySQLDbalAdapter.php`
- `Core/DatabaseResolver.php`
- `tests/RawAccessTest.php`
- Supporting DSN normalization layers and DTO mapping paths

---

## 🏁 Phase Status
**✔ Completed successfully**

---

# 🔚 End of Phase 16

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
