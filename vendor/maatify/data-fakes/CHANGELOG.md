# Changelog

All notable changes to **maatify/data-fakes** will be documented in this file.
The format is based on **Keep a Changelog**, and this project adheres to **Semantic Versioning**.

---

## [1.0.4] — 2025-11-22

### 🚀 Added — Phase 8: Advanced Simulation Layer (Latency + Failure Injection)

This release introduces a complete simulation layer for simulating latency, jitter,
and controlled deterministic failures across all fake adapters.
It is designed for **CI pipelines, stress testing, and advanced repository validation**.

#### ⚡ Simulation Core

* Added `ErrorSimulator`
    * Registers operation-level failure scenarios
    * Supports probability-based deterministic failures
    * Throws configured exceptions when triggered
    * Useful for retry testing and resilience logic

* Added `LatencySimulator`
    * Simulates per-operation latency
    * Supports default latency and operation-specific overrides
    * Adds optional jitter for pseudo-realistic slowdowns
    * Returns final applied latency (in ms) for verification

* Added `FailureScenario`
    * Immutable failure definition
    * Includes: name, probability, exception class, message, code

#### 🧩 Adapter Integration

* Added `SimulationAwareTrait`
    * Provides `guardOperation()` hook
    * Integrates error and latency simulators into adapters
    * Used by FakeMySQLAdapter, FakeMongoAdapter, FakeRedisAdapter

* Updated Fake Adapters:
    * FakeMySQLAdapter now supports per-operation failure simulation
    * FakeMongoAdapter integrated with latency hooks
    * FakeRedisAdapter updated to support deterministic delay

* Updated `FakeStorageLayer`
    * Accepts latency simulation for slow-storage emulation

#### 🧪 Tests

* Added `ErrorSimulatorTest`
    * Ensures deterministic failure triggering
    * Validates non-triggering scenarios
    * Tests probability handling

* Added latency simulation tests inside adapter suites

#### 📝 Documentation

* Added `README.phase8.md`
* Updated main README with new Simulation Layer features
* Updated full documentation in [`README.full.md`](docs/README.full.md)

---
## [1.0.3] — 2025-11-22

### 🚀 Added — Phase 7: Fixtures Loader & FakeEnvironment

This release adds a complete fixtures and test-environment system on top of the
existing in-memory adapters, enabling deterministic test data loading and full
environment resets.

#### 📦 Fixtures Loader

* Added `FakeFixturesLoader`
    * Supports loading fixtures from JSON files or arrays
    * Hydrates SQL tables, Mongo collections, and Redis keys
    * Deterministic load order (SQL → Mongo → Redis)

* Added `JsonFixtureParser`
    * Strict JSON parsing with validation
    * Helpful exception messages for malformed files

#### 🧪 Fake Test Environment

* Added `FakeEnvironment`
    * Coordinates FakeMySQL, FakeDBAL, FakeMongo, and FakeRedis in one environment
    * Optional auto-reset before each test
    * Fixture loading helpers

* Added `ResetState`
    * Enables/disables auto-reset mode
    * Ensures reproducible test state

#### 🗄 Adapter Enhancements

* FakeRedisAdapter updated with reset hooks for fixture hydration
* FakeMongoAdapter aligned with environment-level resets

#### 🧪 Tests

* Added `FakeFixturesLoaderTest`
* Added `FakeEnvironmentTest`
* Added sample fixture file: `tests/Fixtures/sample-fixtures.json`

#### 📝 Documentation

* Added `README.phase7.md`
* Updated [`README.full.md`](docs/README.full.md) with Phase 7 details
* Updated component list and basic usage examples

---

## [1.0.2] — 2025-11-22

### 🚀 Added — Phase 6: Unit of Work + Snapshot Engine

This release introduces a full transactional layer on top of the in-memory storage system,
enabling nested transactions, rollback support, and deterministic state recovery.

#### 🧱 Unit of Work

* Added `FakeUnitOfWork`
    * `begin()` / `commit()` / `rollback()`
    * Nested snapshot stacks
    * `transactional()` callback wrapper for atomic execution
    * Adapter-agnostic design

#### 📸 Snapshot Engine

* Added `SnapshotManager`
* Added `SnapshotState`
* Full export/import of:
    * Storage tables
    * Auto-increment counters
* Enables deterministic rollback flows

#### 🗄 Storage Layer Enhancements

* Extended `FakeStorageLayer` with:
    * `exportState()`
    * `importState()`
* Improved state consistency across adapters
* Added deep copy protection for immutable snapshots

#### 🧪 Tests

* Added `FakeUnitOfWorkTest`
* Added `SnapshotManagerTest`
* Verified:
    * Commit propagation
    * Rollback correctness
    * Nested transactions
    * Exception-safe transactional execution

#### 📝 Documentation

* Updated [`README.full.md`](docs/README.full.md)
* Added `README.phase6.md`

---


## [1.0.0] — 2025-11-22

### 🎉 First Stable Release

This release provides the complete in-memory data simulation system used across the Maatify ecosystem for testing repositories, services, and adapters without real databases.

### 🚀 Added

#### 🔌 Core Architecture

* Implemented `FakeStorageLayer` — deterministic table/collection memory engine
* Added contracts:

    * `FakeAdapterInterface`
    * `FakeRepositoryInterface`
    * `FakeResolverInterface`
* Added base class: `AbstractFakeAdapter`
* Added full project bootstrap: composer config, PHPUnit bootstrap, root README

#### 🗄️ Fake MySQL Adapter

* Full CRUD operations
* Filtering: eq, ne, in, contains
* Ordering ASC/DESC
* LIMIT / OFFSET
* AdapterInterface lifecycle: connect, disconnect, healthCheck, isConnected
* Added traits:

    * `NormalizesInputTrait`
    * `QueryFilterTrait`

#### 🧱 Fake MySQL DBAL Adapter

* Doctrine-style wrapper
* Delegates lifecycle to FakeMySQLAdapter
* Added:

    * `fetchOne()`
    * `fetchAll()`
    * prepared-like filtering

#### 🧰 Fake Redis Adapter

* String operations (`get`, `set`, `del`)
* Hashes: `hset`, `hget`, `hdel`
* Lists: `lpush`, `rpush`, `lrange`
* Counters: `incr`, `decr`
* TTL support using monotonic timestamps
* Full AdapterInterface lifecycle

#### 🍃 Fake MongoDB Adapter

* CRUD operations:

    * `insertOne`, `insertMany`
    * `find`, `findOne`
    * `updateOne`
    * `deleteOne`
* Query operators:

    * `$eq`, `$ne`
    * `$in`, `$nin`
    * `$gt`, `$gte`, `$lt`, `$lte`
* Deterministic collection storage
* AdapterInterface lifecycle support

#### 🧪 Tests

* Added full PHPUnit coverage for:

    * Fake MySQL
    * Fake DBAL
    * Fake Redis
    * Fake Mongo
    * Storage layer
* Achieved **92% coverage**
* PHPStan level 6 (clean)

#### 🔧 Tooling

* Added testing bootstrap
* Added project header policy
* Fully typed code with no mixed types

---

## 💡 Notes

This is the initial stable release.
All future releases will follow **semantic versioning**:

* Patch updates → 1.0.x
* New features → 1.1.0, 1.2.0
* Breaking changes → 2.0.0

---


## 🪪 License

**[MIT license](LICENSE)** © [Maatify.dev](https://www.maatify.dev)
You’re free to use, modify, and distribute this library with attribution.

---
## 👤 Author
**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** - https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-fakes

## 🤝 Contributors
Special thanks to the Maatify.dev engineering team and open-source contributors.

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>
