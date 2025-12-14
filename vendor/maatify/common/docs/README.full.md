# 🧩 Maatify Common — Full Documentation

[![Maatify Common](https://img.shields.io/badge/Maatify-Common-blue?style=for-the-badge)](../README.md)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

**Core Foundation Library for the Maatify Ecosystem**

This document provides the **full combined technical documentation** for all phases implemented in `maatify/common`, including architecture, modules, utilities, enums, validation, sanitization, date helpers, text processing, connectivity layers, and now **RepositoryInterface Foundation (Phase 16)**.

Each development phase is documented separately and linked below.

---

# 📑 Table of Contents

1. [Introduction](#introduction)

2. [Core Modules](#core-modules)

3. [System Design & Architecture](#system-design--architecture)

4. [Full Phase Documentation](#full-phase-documentation)

    * [Phase 1 — Pagination Module](./phases/README.phase1.md)
    * [Phase 2 — Locking System](./phases/README.phase2.md)
    * [Phase 3 — Security & Input Sanitization](./phases/README.phase3.md)
    * [Phase 3B — Singleton System](./phases/README.phase3b.md)
    * [Phase 4 — Text & Placeholder Utilities](./phases/README.phase4.md)
    * [Phase 5 — Date & Time Utilities](./phases/README.phase5.md)
    * [Phase 6 — Validation & Filtering Tools](./phases/README.phase6.md)
    * [Phase 7 — Enums & Constants Standardization](./phases/README.phase7.md)
    * [Phase 8 — Testing & Stable Release](./phases/README.phase8.md)
    * [Phase 9 — Logger Stability Update](./phases/README.phase9.md)
    * [Phase 10 — TapHelper Utility](./phases/README.phase10.md)
    * [Phase 11 — Connectivity Foundation](./phases/README.phase11.md)
    * [Phase 12 — VERSION File Fix](./phases/README.phase12.md)
    * [Phase 13 — Mutable ConnectionConfigDTO](./phases/README.phase13.md)
    * [Phase 14 — Driver Contract Modernization](./phases/README.phase14.md)
    * [Phase 15 — Redis Lock Testing Stability Update](./phases/README.phase15.md)
    * [**Phase 16 — RepositoryInterface Foundation (new)**](./phases/README.phase16.md)
    * [Phase 17 — RedisClientInterface Contract](./phases/README.phase17.md)
    * [**Phase 18 — KeyValueAdapterInterface Foundation (new)**](./phases/README.phase18.md)

5. [Directory Structure](#directory-structure)

6. [Testing & Coverage](#testing--coverage)

7. [Release Notes](#release-notes)

8. [License](#license)

---

# 🧭 Introduction

`maatify/common` is the **core foundational library** of the entire Maatify backend ecosystem.
It provides:

* standardized DTOs
* functional and text utilities
* sanitization and validation
* date/time localization
* enums & constants
* connection DTOs
* locking mechanisms
* repository contract (Phase 16)
* helper abstractions used by all other Maatify packages
* redis client contract (Phase 17)

This library guarantees **consistent behavior**, **predictable patterns**, and **secure, reusable tools** for all backend services.

---

# 🧩 Core Modules

### ✔ Pagination Module

Unified DTOs & helpers for API and repository pagination.

### ✔ Locking System

Distributed, hybrid, Redis, and file-based mutex operations.
→ **Phase 15 adds realistic Redis TTL simulation for queue-mode tests.**

### ✔ Security & Sanitization

XSS-safe sanitization powered by HTMLPurifier + mixed-type cleaning.

### ✔ Traits & Core Patterns

Reusable SingletonTrait and sanitization traits.

### ✔ Text Utilities

Placeholder rendering, slug normalization, regex tools, secure compare.

### ✔ Date & Time Helpers

Localized formatting (EN/AR/FR), timezone conversion, humanized differences.

### ✔ Validation & Filtering

Email/URL/UUID/Slug validation + array cleanup + type detection.

### ✔ Enums & Constants

Global standardized enums for all Maatify components.

### ✔ Connectivity Foundation

Standardized configuration for MySQL / Mongo / Redis drivers.

### ✔ Helper Utilities

TapHelper for fluent initialization and functional pipelines.

### ✔ Repository Contract (Phase 16)

Universal repository interface shared across:

* data-adapters
* data-fakes
* data-repository

Provides unified CRUD + filters and adapter injection pattern.

### ✔ Redis Client Contract (Phase 17)

Minimal key–value Redis client abstraction compatible with:
* phpredis
* Predis
* FakeRedisConnection

Provides: `get`, `set`, `del`, `keys` with strict PSR-12 typing.

### ✔ Generic Key–Value Storage Contract (Phase 18)

High-level, storage-agnostic KV abstraction used by:

* Security Guard (brute-force protection & IP blocks)
* Rate Limiter
* OTP & temporary tokens
* Sessions & cache layers

Provides: `get`, `set`, `del` with optional TTL support.

Decouples all KV-based systems from Redis protocol specifics.

---

# 🏗 System Design & Architecture

This library sits at the **root level** of the Maatify ecosystem.

```
┌──────────────────────────────┐
│         maatify/common       │  ← Core Level
└───────────────┬──────────────┘
                │
                ▼
        Shared Infrastructure
        - data-adapters
        - psr-logger
        - security-guard
        - i18n / localization
        - repository layer
        - messaging-core
                │
                ▼
        Application Services
        - ecommerce
        - dashboards
        - otp systems
        - admin portals
                │
                ▼
       End-user Applications
```

Every higher module depends on this library for consistency, abstraction, and high reusability.

---

# 📚 Full Phase Documentation

Updated phase table including **Phase 16**:

| Phase        | Description                             | Link                                            |
|--------------|-----------------------------------------|-------------------------------------------------|
| Phase 1      | Pagination Module                       | [README.phase1.md](./phases/README.phase1.md)   |
| Phase 2      | Locking System                          | [README.phase2.md](./phases/README.phase2.md)   |
| Phase 3      | Input Sanitization                      | [README.phase3.md](./phases/README.phase3.md)   |
| Phase 3B     | Singleton System                        | [README.phase3b.md](./phases/README.phase3b.md) |
| Phase 4      | Text Utilities                          | [README.phase4.md](./phases/README.phase4.md)   |
| Phase 5      | Date Utilities                          | [README.phase5.md](./phases/README.phase5.md)   |
| Phase 6      | Validation & Filtering                  | [README.phase6.md](./phases/README.phase6.md)   |
| Phase 7      | Enums & Constants                       | [README.phase7.md](./phases/README.phase7.md)   |
| Phase 8      | Testing & Release                       | [README.phase8.md](./phases/README.phase8.md)   |
| Phase 9      | Logger Stability Update                 | [README.phase9.md](./phases/README.phase9.md)   |
| Phase 10     | TapHelper Utility                       | [README.phase10.md](./phases/README.phase10.md) |
| Phase 11     | Connectivity Foundation                 | [README.phase11.md](./phases/README.phase11.md) |
| Phase 12     | VERSION File Fix                        | [README.phase12.md](./phases/README.phase12.md) |
| Phase 13     | Mutable ConnectionConfigDTO             | [README.phase13.md](./phases/README.phase13.md) |
| Phase 14     | Driver Contract Modernization           | [README.phase14.md](./phases/README.phase14.md) |
| Phase 15     | Redis Lock Testing Stability Update     | [README.phase15.md](./phases/README.phase15.md) |
| Phase 16     | RepositoryInterface Foundation          | [README.phase16.md](./phases/README.phase16.md) |
| Phase 17     | RedisClientInterface Contract           | [README.phase17.md](./phases/README.phase17.md) |
| **Phase 18** | **KeyValueAdapterInterface Foundation** | [README.phase18.md](./phases/README.phase18.md) |
---

# 🗂 Directory Structure

```
src/
├── Pagination/
├── Lock/
├── Security/
├── Traits/
├── Text/
├── Date/
├── Validation/
├── DTO/
├── Enums/
├── Constants/
└── Contracts/
    ├── Repository/
    │   └── RepositoryInterface.php
    ├── Redis/
    │   └── RedisClientInterface.php
    └── Adapter/
        └── KeyValueAdapterInterface.php

tests/
└── complete test suite
```

---

# 🧪 Testing & Coverage

### ✔ Current Status (v1.0.10)

* **66+ automated tests**
* **150+ assertions**
* **≈98% code coverage**
* Phase 15: Stable deterministic Redis TTL simulation
* Phase 16: RepositoryInterface fully validated via static analysis
* Phase 17: RedisClientInterface validated across phpredis, Predis mocks, and FakeRedisConnection
* Phase 18: KeyValueAdapterInterface validated through Security Guard & Rate Limiter integration
---

# 🧾 Release Notes

### **v1.0.10 — Phase 18: KeyValueAdapterInterface Foundation**

* Added: `KeyValueAdapterInterface` under `src/Contracts/Adapter/`.
* Introduced generic, storage-agnostic KV abstraction.
* Decoupled security-guard and rate-limiter from Redis protocol.
* Enabled strict PHPStan typing for all KV-based drivers.
* Updated roadmap.json, README.full.md, and documentation links.


### **v1.0.9 — Phase 17: RedisClientInterface Contract**

* Added: `RedisClientInterface` under `src/Contracts/Redis/`.
* Introduced unified minimal KV Redis API for phpredis, Predis, and FakeRedis.
* Updated roadmap.json, README.full.md, and documentation links.
* Strengthened compatibility between lock managers and Redis fakes.

### **v1.0.8 — Phase 16: RepositoryInterface Foundation**

* Added new folder: `src/Contracts/Repository/`
* Added: `RepositoryInterface.php`
* Unified CRUD + filter contract across the entire Maatify ecosystem.
* Added adapter injection support for repositories.
* Updated `README.full.md`, roadmap.json, and documentation links.

### **v1.0.7 — Phase 15: Redis Lock Testing Stability Update**

* Added `FakeRedisConnection` with TTL simulation.
* Updated locking tests for reliability.
* Improved detection logic in RedisLockManager.

---

# 🪪 License

Released under the MIT License — © 2025 Maatify.dev

---
**© 2025 Maatify.dev**

Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev
Released under the [MIT license](../LICENSE).
