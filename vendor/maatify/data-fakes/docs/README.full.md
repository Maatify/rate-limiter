# 📘 Maatify Data Fakes

**In-Memory Fake Adapters for MySQL, Redis, MongoDB & Repository Layer**
**Version:** **1.0.4**
**Project:** `maatify/data-fakes`
**Maintained by:** Maatify.dev

---

## 📌 TL;DR — Summary

`maatify/data-fakes` is the official **fully deterministic in-memory testing engine**
for the entire Maatify ecosystem. It simulates **MySQL, DBAL, Redis, and MongoDB adapters**
with **zero external services required** — perfect for PHPUnit & CI.

### ✨ Instant Highlights

* Fake MySQL Adapter (filters, sort, limit, snapshots)
* Fake DBAL Adapter (Doctrine-like API)
* Fake Mongo Adapter (operators & collections)
* Fake Redis Adapter (strings, lists, hashes, TTL)
* Shared deterministic memory engine
* Repository simulations (FakeRepository, FakeCollection)
* **Phase 6 — Snapshot Engine + Unit of Work**
* **Phase 7 — Fixtures Loader + FakeEnvironment**
* **Phase 8 — Advanced Simulation Layer (latency + failure injection)**
* Auto-reset per test
* 100% compatible with real adapters in `maatify/data-adapters`

> ⚡ Fast, deterministic, CI-friendly — no Docker, no databases needed.

---

## 🚀 Overview

`maatify/data-fakes` is a lightweight yet powerful **in-memory adapter simulation layer**.
It mirrors the behavior of the real MySQL/DBAL/Redis/Mongo adapters while keeping everything in RAM.

You get:

* Database-less testing
* Ultra-fast PHPUnit runs
* Deterministic state snapshots
* Automatic test isolation
* Multi-adapter testing
* Failure simulation (timeouts, random outages, deadlocks — Phase 8)

Its mission:
✔ Make testing in the Maatify ecosystem frictionless
✔ Guarantee 1:1 adapter behavior
✔ Run consistently in CI, GitHub Actions, and local environments

---

## 🔑 Core Dependencies

The library depends on:

1. **AdapterInterface**
   `Maatify\Common\Contracts\Adapter\AdapterInterface`

2. **ResolverInterface**
   `Maatify\DataAdapters\Contracts\ResolverInterface`

These guarantee full compatibility with real adapters.

---

## 🧩 Key Features

### 🔌 Fake Adapters

* **FakeMySQLAdapter**
  Select / Insert / Update / Delete, filtering, ordering, offset/limit

* **FakeMySQLDbalAdapter**
  Doctrine-style interface

* **FakeMongoAdapter**
  Operators: `$in`, `$gt`, `$ne`, `$lte`, nested paths

* **FakeRedisAdapter**
  Strings, lists, hashes, counters, TTL

---

### 🧱 Repository Support

* FakeRepository
* FakeCollection
* ArrayHydrator

---

### 🔄 Transaction System (Phase 6)

* Snapshot Manager
* Nested snapshots
* Rollback/Commit
* Transactional wrapper
* Deterministic state restoration

---

### 📦 Fixtures & Testing (Phase 7)

* JSON fixtures loader
* SQL/Mongo/Redis hydration
* FakeEnvironment with auto-reset
* Ideal for integration & repository tests

---

### ⚡ Advanced Simulation Layer (Phase 8)

Adds full **fault injection & latency simulation**:

#### **LatencySimulator**

* Per-operation latency
* Default latency
* Optional jitter
* Deterministic timings for CI

#### **ErrorSimulator**

* Register failure scenarios
* Probability-based failure
* Deterministic exceptions
* Operation-level hooks:

    * `mysql.select`
    * `redis.get`
    * `mongo.insert_one`
    * …etc

#### **SimulationAwareTrait**

Used inside adapters to apply:

* `guardOperation()`
* Error hooks
* Latency hooks

---

## ⚙ Adapter Lifecycle

All fake adapters implement:

* `connect()` / `disconnect()`
* `isConnected()`
* `healthCheck()`
* `getDriver()`

Fully compatible with real production adapters.

---

## 📦 Installation

```bash
composer require maatify/data-fakes --dev
```

✔ Required for testing & CI
✘ Not used in production

---

## 🧪 Basic Usage

### Resolve a fake adapter

```php
$resolver = new FakeResolver();
$db = $resolver->resolve('mysql:main', true);
$rows = $db->select('users', ['id' => 1]);
```

---

### Reset state

```php
FakeStorageLayer::reset();
```

---

### Load fixtures

```php
$env->loadFixturesFromFile(__DIR__.'/fixtures.json');
```

---

## 📁 Included Components

### 🔹 Adapters

* FakeMySQLAdapter
* FakeMySQLDbalAdapter
* FakeRedisAdapter
* FakeMongoAdapter

---

### 🔹 Repository Layer

* FakeRepository
* FakeCollection
* ArrayHydrator

---

### 🔹 Routing

* FakeResolver

---

### 🔹 Snapshot System (Phase 6)

* SnapshotManager
* SnapshotState
* FakeUnitOfWork

---

### 🔹 Fixtures & Test Environment (Phase 7)

* FakeEnvironment
* FakeFixturesLoader
* JsonFixtureParser
* ResetState

---

### 🔹 Simulation Layer (Phase 8)

* ErrorSimulator
* LatencySimulator
* FailureScenario
* SimulationAwareTrait

---

## 🧩 Architectural Highlights

### FakeStorageLayer

* Deterministic storage engine
* Shared across all FakeAdapters
* Auto-increment ID system
* Snapshot export/import support
* Phase 8 latency integration

---

### Snapshot System (Phase 6)

* Immutable snapshots
* Global state capture
* Nested transactions
* Guaranteed repeatable behavior

---

### Unit of Work (Phase 6)

* begin() / commit() / rollback()
* Stacked snapshots
* Atomic transactional wrapper

---

### Fixtures System (Phase 7)

* JSON or array hydration
* Multi-adapter ingestion
* Auto reset between tests

---

### Simulation Layer (Phase 8)

Adds CI-safe deterministic chaos engineering:

* Latency simulation
* Fault injection
* Randomized edge-case generation
* Operation-level configuration

---

## 📚 Development Phases

* **Phase 1:** Project Bootstrap & Core Architecture
* **Phase 2:** Fake MySQL & DBAL Adapter
* **Phase 3:** Fake Redis Adapter
* **Phase 4:** Fake Mongo Adapter
* **Phase 5:** Repository Layer
* **Phase 6:** Snapshot Engine + Unit of Work
* **Phase 7:** Fixtures Loader + FakeEnvironment
* **Phase 8:** Advanced Simulation Layer (Latency + Failure Injection)

---

## 📚 Development Phases & Documentation Links

- **Phase 1 — Project Bootstrap & Core Architecture**
  [`phases/README.phase1.md`](phases/README.phase1.md)

- **Phase 2 — Fake MySQL & DBAL Adapter**
  [`phases/README.phase2.md`](phases/README.phase2.md)

- **Phase 3 — Fake Redis Adapter**
  [`phases/README.phase3.md`](phases/README.phase3.md)

- **Phase 4 — Fake Mongo Adapter**
  [`phases/README.phase4.md`](phases/README.phase4.md)

- **Phase 5 — Repository Layer**
  [`phases/README.phase5.md`](phases/README.phase5.md)

- **Phase 6 — Snapshot Engine + Unit of Work**
  [`phases/README.phase6.md`](phases/README.phase6.md)

- **Phase 7 — Fixtures Loader + FakeEnvironment**
  [`phases/README.phase7.md`](phases/README.phase7.md)

- **Phase 8 — Advanced Simulation Layer (Latency + Failure Injection)**
  [`phases/README.phase8.md`](phases/README.phase8.md)

---

## 📘 Full Documentation

Includes:

* Architecture overview
* Class reference
* Phases 1→8
* API maps
* Repository usage
* Adapter lifecycles
* Test isolation rules

---

## 🪪 License

**[MIT license](../LICENSE)** © [Maatify.dev](https://www.maatify.dev)

---

## 👤 Author

Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))**
[https://www.maatify.dev](https://www.maatify.dev)

📘 Full source:
https://github.com/Maatify/data-fakes

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>
