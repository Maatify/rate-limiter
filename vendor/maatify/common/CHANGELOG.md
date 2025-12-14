# 🧾 CHANGELOG

All notable changes to **maatify/common** will be documented in this file.
This project follows [Semantic Versioning](https://semver.org/).

---

## **[1.0.10] – 2025-12-09**

### 🧠 **KeyValueAdapterInterface — Generic Storage-Agnostic KV Contract**

This release introduces the **KeyValueAdapterInterface**, a **high-level, storage-agnostic key–value abstraction** designed to decouple all KV-based systems from Redis protocol specifics.

It serves as the **behavioral KV layer** used by:

* Security Guard (brute-force protection, IP blocks)
* Rate Limiter
* OTP & temporary tokens
* Sessions & cache engines
* In-memory & fake storage drivers

This completes the architectural separation between:

* **Redis protocol layer** → `RedisClientInterface` (Phase 17)
* **Generic storage behavior layer** → `KeyValueAdapterInterface` (Phase 18)

---

### 🧩 **Added**

#### ✔ `KeyValueAdapterInterface`

File location:

```
src/Contracts/Adapter/KeyValueAdapterInterface.php
```

Standardized generic KV operations:

* `get(string $key): mixed`
* `set(string $key, mixed $value, ?int $ttl = null): void`
* `del(string $key): void`

Designed for compatibility with:

* Redis adapters
* FakeRedisConnection
* In-memory KV drivers
* Future cache engines

---

### 🛠 **Changed**

* Security Guard now depends on **typed KV behavior** instead of Redis-style calls.
* Rate Limiter KV logic migrated to the new generic contract.
* Redis adapters now implement:

  * `RedisClientInterface` (Phase 17 — protocol)
  * `KeyValueAdapterInterface` (Phase 18 — behavior)
* Updated `README.md` & `README.full.md` with new Generic KV documentation.
* Updated `roadmap.json` (added Phase 18).
* Updated version file (`VERSION → 1.0.10`).
* Added new phase documentation:
  `/docs/phases/README.phase18.md`.

---

### 🧪 **Tests**

* FakeRedis adapter conformance tests.
* Security Guard KV behavior verification.
* Rate Limiter TTL + counter-coverage.
* PHPStan validation (max level).
* No regressions in Locking System.

---

### 📘 **Documentation**

* Added new **Generic Key–Value Storage Contract** section under “Core Modules”.
* Updated Phase Summary Table to include **Phase 18**.
* Linked cross-package usage in:

  * `security-guard`
  * `rate-limiter`
  * `data-adapters`
  * `data-fakes`

---

### ✔ **Notes**

* No breaking changes.
* Fully backward-compatible.
* Complements — does not replace — `RedisClientInterface`.
* Finalizes clean separation between **protocol** and **storage behavior** layers.
* Required for deterministic KV behavior across real and fake drivers.

---

### 🧾 **Changelog Snapshot**

**v1.0.10 — 2025-12-09**

* Added: KeyValueAdapterInterface (Phase 18)
* Updated: KV abstraction across security-guard & rate-limiter
* Updated: README files, roadmap, VERSION, phase docs
* Ensured compatibility with Redis, FakeRedis, and in-memory KV drivers

---

## **[1.0.9] – 2025-11-26**

### 🔌 **RedisClientInterface — Unified Redis Key/Value Contract**

This release introduces a **minimal, driver-agnostic Redis client interface** shared across:

* phpredis (Redis extension)
* Predis (pure PHP)
* FakeRedisConnection (test environment)

The goal is to establish a **consistent KV API layer** used by all Maatify libraries.

---

### 🧩 **Added**

#### ✔ `RedisClientInterface`

File location:

```
src/Contracts/Redis/RedisClientInterface.php
```

Standardized KV operations:

* `get(string $key): string|false|null`
* `set(string $key, string $value): bool`
* `del(string ...$keys): int`
* `keys(string $pattern): array`

Designed for compatibility with:

* Redis (phpredis)
* Predis\Client
* internal FakeRedisConnection (used in maatify/data-fakes & lock tests)

---

### 🛠 **Changed**

* Updated `README.md` & `README.full.md` with new Redis Client documentation.
* Updated `roadmap.json` (added Phase 17).
* Updated version file (`VERSION → 1.0.9`).
* Added new phase documentation:
  `/docs/phases/README.phase17.md`.

---

### 🧪 **Tests**

* Verified compatibility with FakeRedisConnection.
* Mock-based signature testing for Predis + phpredis equivalents.
* No breaking changes to Locking System (RedisLockManager/HybirdLockManager remain fully compatible).

---

### 📘 **Documentation**

* Added new Redis Contract section under “Core Modules”.
* Included examples for `get`, `set`, `del`, `keys`.
* Updated Phase Summary Table to include Phase 17.

---

### ✔ **Notes**

* No breaking changes.
* Fully backward compatible.
* Required for future caching decorators in `maatify/data-repository`.
* Unifies Redis behavior across the entire Maatify ecosystem.

---

### 🧾 **Changelog Snapshot**

**v1.0.9 — 2025-11-26**

* Added: RedisClientInterface (Phase 17)
* Updated: README files, roadmap, VERSION, phase docs
* Ensured compatibility with FakeRedis, Predis, and phpredis

---

## **[1.0.8] – 2025-11-22**

### 🧩 **RepositoryInterface Foundation — Unified CRUD Contract**

This release introduces the **universal repository contract** used across the entire Maatify data ecosystem, ensuring consistent CRUD behavior for:

* maatify/data-adapters
* maatify/data-fakes
* maatify/data-repository

The new `RepositoryInterface` establishes a stable foundation for all repository logic.

---

### 🧩 **Added**

#### ✔ `RepositoryInterface`

Located at:

```
src/Contracts/Repository/RepositoryInterface.php
```

Provides the following standardized methods:

* `find(id): ?array`
* `findBy(filters): array`
* `findAll(): array`
* `insert(data): int|string`
* `update(id, data): bool`
* `delete(id): bool`
* `setAdapter(AdapterInterface $adapter): static`

Fully typed, adapter-agnostic, and shared across multiple libraries.

---

### 🛠 **Changed**

* Documentation updated to include **Phase 16**.
* `README.full.md` updated with new Repository Contract module.
* Roadmap updated (`phase16`) with contract specifications.

---

### 🧪 **Tests**

* Static analysis validation (PHPStan level: max).
* Compatibility tests with:

    * FakeMySQLAdapter
    * FakeMongoAdapter
    * FakeRedisConnection
    * FakeMySQLDbalAdapter

No runtime tests required as this phase introduces only interface-level contracts.

---

### 📘 **Documentation**

* Added new file: `/phases/README.phase16.md`
* Updated: Architecture section (Repository Layer)
* Updated: Phase table in README.full.md

---

### ✔ **Notes**

* No breaking changes.
* Fully backward compatible with all prior releases.
* Mandatory dependency for phase1–phase5 in `maatify/data-fakes`.

---

### 🧾 **Changelog Snapshot**

**v1.0.8 — 2025-11-22**

* Added RepositoryInterface contract
* Updated roadmap + full documentation
* Ensured compatibility with data-fakes and data-adapters
* No breaking changes — stable foundation for repository system

---

## **[1.0.7] – 2025-11-18**

### 🧪 **Improved Locking Stability — Redis Simulation Layer & Deterministic Queue Tests**

This release introduces a **fully simulated Redis environment** for testing and brings significant stability improvements to the locking subsystem—especially for the HybridLockManager queue-mode.

---

### 🧩 **Added**

#### ✔ **`FakeRedisConnection` (Redis-Compatible In-Memory Driver)**

A complete simulation of Redis lock behavior used in tests:

* Supports `SET NX EX` semantics
* TTL expiration measured in real time
* Accurate `exists()` and atomic `del()`
* Behaves exactly like a minimal Redis instance
* Fully deterministic and requires **no real Redis server**

#### ✔ **Healthy Adapter Enhancements**

* `FakeHealthyAdapter` now returns `FakeRedisConnection`
* Test suite uses real lock expiration + NX contention simulation

---

### 🛠 **Changed**

#### ✔ Queue-mode lock behavior now deterministic

* `HybridLockManager::waitAndAcquire()` fully respects TTL-based expiration
* Eliminated timing flakiness in concurrent-resource tests
* Queue tests now properly wait until lock1 TTL expires

#### ✔ Updated RedisLockManager validation

* Switched to **method-based detection** (`set`, `exists`, `del`)
* Avoids strict instance checks (Redis / Predis)
* Allows seamless testing with any mock object implementing Redis-like behavior

---

### 🧪 **Tests**

* Updated: `HybridLockManagerTest`
* Added: Timing-based TTL assertion logic
* Improved: Consistency across PHP versions and test runners
* Result: **100% stable queue-mode behavior**

---

### 📘 **Documentation**

* Updated Phase Summary Table (Phase 15)
* Added "Redis Simulation Layer" descriptions to README.full.md
* Noted test improvements and lock-flow behavior under "Locking System"

---

### ✔ **Notes**

* Fully backward compatible
* No public API changes
* No breaking modifications
* Prepares the locking system for future distributed workload features

---

### 🧾 **Changelog Snapshot**

**v1.0.7 — 2025-11-18**

* Added: Redis simulation layer for testing (`FakeRedisConnection`)
* Enhanced: HybridLock queue-mode timing & TTL logic
* Improved: RedisLockManager compatibility checks
* Updated: Test suite stability and lock expiration timing

---

## [1.0.6] – 2025-11-17
### 🛠 Changed
- Updated the raw-driver contract to allow flexible return types for
  `getDriver()` across all implementing adapters.
- Replaced strict return typing with an untyped signature to support
  multiple native drivers (PDO, Doctrine DBAL, MongoDB Database,
  Redis, Predis).
- Added a unified DocBlock annotation documenting all expected driver
  types for improved IDE support and static analysis passes.
- Ensured full backward compatibility with all existing adapters and
  repositories relying on the common interface.

### ✔ Notes
- No breaking changes introduced.
- This update improves cross-library compatibility with maatify/data-adapters
  and prepares the ecosystem for future multi-database integrations.

---

## [1.0.5] - 2025-11-13
### 🛠 Changed
- Removed the `readonly` modifier from `ConnectionConfigDTO` to allow flexible configuration mutation.
- Enhanced compatibility with dynamic runtime configuration (e.g., DSN parsing, environment-driven overrides).
- Improved internal behavior of adapters relying on mutable configuration during resolution.

### ⚠️ Notes
- No breaking changes introduced.
- Existing constructor-based initialization remains fully compatible.

---

## [1.0.4] – 2025-11-13

Release 1.0.4 (fix missing VERSION update)

---

## [1.0.3] – 2025-11-13

### 🧩 Connectivity Foundation — Introducing `ConnectionConfigDTO` & `ConnectionTypeEnum`

**Release Date:** 2025-11-13
**Author:** [Mohamed Abdulalim (megyptm)](mailto:mohamed@maatify.dev)
**License:** MIT
**Organization:** [Maatify.dev](https://www.maatify.dev)

---

### ⚙️ Overview

This update introduces two new standardized components that will be used across all Maatify backend libraries:

* **`ConnectionConfigDTO`** — a readonly DTO that encapsulates driver-specific connection settings.
* **`ConnectionTypeEnum`** — a unified enum for all supported connection types (`mysql`, `mongo`, `redis`).

These additions support the new DSN-based workflow planned in `maatify/data-adapters` (Phase 10)
and enforce consistency across the Maatify ecosystem.

> “One connection model — shared across all libraries.”

---

### 🧩 Added

#### ✔ `Maatify\Common\DTO\ConnectionConfigDTO`

* Readonly object representing:

    * `driver`
    * `dsn`
    * `user`
    * `pass`
    * `options`
    * `profile` (supports multiple DSN profiles in future releases)
* Basis for profile-based DSN resolution in data-adapters.

#### ✔ `Maatify\Common\Enums\ConnectionTypeEnum`

* Defines consistent adapter identifiers:

    * `MYSQL`
    * `MONGO`
    * `REDIS`

#### ✔ New Tests

* Added test suite:

    * `tests/DTO/ConnectionConfigDTOTest.php`
    * `tests/Enums/ConnectionTypeEnumTest.php`
* Verified:

    * DTO immutability
    * Enum integrity
    * PSR-12 compliance

#### ✔ Documentation Update

* Updated `/docs/core/helpers.md`
  → Added new section: **Connectivity Foundation**
* Linked from main README under **Core Modules**

---

### 🧱 Architectural Impact

* Establishes a **shared foundation** for database configurations.
* Required for **Phase 10** in `maatify/data-adapters` (string-based driver selection + DSN builder).
* Ensures **cross-library consistency** for all future adapters and DI containers.

---

### 🧾 Changelog Snapshot

**v1.0.3 — 2025-11-13**

* Added: `ConnectionConfigDTO`
* Added: `ConnectionTypeEnum`
* Added: Tests for DTO & Enum
* Updated: Docs with new Connectivity Foundation section
* No breaking changes — Fully backward compatible

---

## [1.0.2] – 2025-11-10

### ✨ Helper Utilities Expansion — Introducing `TapHelper`

**Release Date:** 2025-11-10
**Author:** [Mohamed Abdulalim (megyptm)](mailto:mohamed@maatify.dev)
**License:** MIT
**Organization:** [Maatify.dev](https://www.maatify.dev)

---

### ⚙️ Overview

This release introduces **`TapHelper`**, a new functional-style helper utility
that simplifies object initialization and enhances code fluency across all Maatify libraries.

It allows developers to execute a callback on any object or value and return that same value unchanged —
making adapter or service setup more expressive, readable, and concise.

> “Cleaner initialization, consistent patterns, zero boilerplate.”

---

### 🧩 Added

* **New Helper:** `Maatify\Common\Helpers\TapHelper`

    * Provides a static `tap()` method to execute a callback on any object or value.
    * Returns the same instance unchanged.
    * Fully PSR-12 compliant and functional in style.

* **Unit Tests:**

    * Added `tests/Helpers/TapHelperTest.php` to verify:

        * Object reference equality and immutability.
        * Proper callback execution.
        * Scalar and array handling.

* **Documentation:**

    * Updated `README.md`:

        * Added a new **🧩 Helper Utilities** section.
        * Included example usage, functional philosophy, and architectural benefits.
        * Linked reference from the **Core Modules** section.

---

### 🧱 Architectural Impact

* Promotes **fluent initialization** patterns across all Maatify libraries (`bootstrap`, `data-adapters`, `rate-limiter`, `redis-cache`, etc.).
* Enhances developer ergonomics and readability during service setup.
* Ensures **ecosystem-wide consistency** with other helpers (`PathHelper`, `EnumHelper`, `TimeHelper`).
* Backward compatible with v1.0.1 — no breaking changes introduced.

---

### 🧾 Changelog Snapshot

**v1.0.2 — 2025-11-10**

* Added: `TapHelper` utility under `Maatify\Common\Helpers`.
* Added: Full PHPUnit test coverage for `TapHelper`.
* Updated: `README.md` with new Helper Utilities documentation section.
* Improved: Developer experience for fluent adapter and service initialization.

---

## [1.0.1] – 2025-11-10

### 🧷 Maintenance & Logger Stability Update

**Release Date:** 2025-11-10
**Author:** [Mohamed Abdulalim (megyptm)](mailto:mohamed@maatify.dev)
**License:** MIT
**Organization:** [Maatify.dev](https://www.maatify.dev)

---

### ⚙️ Overview

This maintenance release improves internal logging reliability within the **HybridLockManager**.
A PSR-3 compliant fallback logger is now automatically initialized to prevent `TypeError` exceptions
when no logger instance is injected.

> “Resilient by design — no silent logs, no nulls.”

---

### ✨ Highlights

* **Fixed:** Added PSR-3 `LoggerInterface` fallback in `HybridLockManager`.
* **Improved:** Unified logger initialization using `LoggerContextTrait`.
* **Verified:** All lock-related tests pass on PHP 8.4.4 (98% coverage).
* **Maintained:** Fully backward-compatible with v1.0.0 — no breaking changes.

---

### 🧾 Changelog Snapshot

**v1.0.1 — 2025-11-10**

* Fixed: Logger fallback initialization in `HybridLockManager`.
* Improved: Logging consistency between Redis and File lock drivers.
* Verified: Full test suite passing under PHP 8.4.4.

---

## [1.0.0] – 2025-11-10

### 🎉 First Stable Release

This marks the first official stable release of the **Maatify Common Library**,
serving as the foundation for all other Maatify components.

---

### 🧩 Added

* **Pagination Module** — unified DTOs and helpers for paginated API results.
* **Locking System** — file-based, Redis, and hybrid lock managers for safe task execution.
* **Security & Input Sanitization** — universal sanitization and HTMLPurifier integration.
* **Core Traits** — singleton pattern and input sanitization traits.
* **Text & Placeholder Utilities** — placeholder rendering, text formatting, regex helpers, and secure comparison tools.
* **Date & Time Utilities** — humanized differences, timezone conversions, and locale-aware date rendering.
* **Validation & Filtering Tools** — robust validator, filter, and array helper for clean data handling.
* **Enums & Constants Standardization** — centralized enums, constants, EnumHelper, and JSON serialization trait.
* **Documentation** — detailed Markdown docs for all modules under `/docs/`.
* **Unit Tests** — comprehensive PHPUnit coverage (>95%) across all components.

---

### ⚙️ Internal

* Full **PSR-12** compliance and strict typing (`declare(strict_types=1);`).
* Integrated CI workflow for GitHub Actions (`tests.yml`).
* Composer autoload and version tracking finalized.

---

### 🧠 Notes

This release establishes the **maatify/common** library as the central core dependency
for all future Maatify modules such as `maatify/psr-logger`, `maatify/rate-limiter`, and `maatify/mongo-activity`.

> 📦 Next planned version: **v1.1.0** — introducing performance optimizations and additional helper utilities.

---

**© 2025 Maatify.dev** — Engineered by [Mohamed Abdulalim](https://www.maatify.dev)
Distributed under the [MIT License](LICENSE)

---