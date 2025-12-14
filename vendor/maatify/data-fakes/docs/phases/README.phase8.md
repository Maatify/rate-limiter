# Phase 8 — Advanced Simulation Layer
**Version:** 1.0.4
**Project:** maatify/data-fakes
**Status:** Completed
**Date:** 2025-11-22T14:00:00+00:00

---

## 🎯 Goals
- Simulate latency, timeouts, and deterministic slow operations.
- Inject controlled failures (connection errors, deadlocks, random outages).
- Provide failure scenarios reusable across adapters.
- Keep simulations CI-friendly and deterministic.

---

## 📁 Deliverables

### Simulation Layer
```
src/Simulation/ErrorSimulator.php
src/Simulation/LatencySimulator.php
src/Simulation/FailureScenario.php
src/Adapters/Base/Traits/SimulationAwareTrait.php
```

### Adapter Hooks
```
src/Adapters/MySQL/FakeMySQLAdapter.php
src/Adapters/Mongo/FakeMongoAdapter.php
src/Adapters/Redis/FakeRedisAdapter.php
src/Storage/FakeStorageLayer.php
```

### Tests
```
tests/Simulation/ErrorSimulatorTest.php
```

---

## 🧠 Architecture Summary

**FailureScenario** encapsulates operation name, probability, and exception metadata. **ErrorSimulator** registers scenarios per operation and throws when a scenario triggers. **LatencySimulator** applies deterministic sleep durations per operation with optional jitter. Adapters opt into simulation via **SimulationAwareTrait**, invoking `guardOperation()` at the start of each lifecycle and CRUD method. **FakeStorageLayer** accepts a latency simulator to model slow storage access.

---

## 🔌 Integration
- Attach `ErrorSimulator` or `LatencySimulator` to any fake adapter via the new setters from `SimulationAwareTrait`.
- Configure per-operation scenario keys such as `mysql.select`, `redis.get`, or `mongo.insert_one` for granular control.
- Storage-level latency hooks can be applied directly to `FakeStorageLayer` when adapters share the same engine.

---

## 🧪 Tests Summary
- `tests/Simulation/ErrorSimulatorTest.php` covers deterministic failures, non-triggering scenarios, and latency application at the storage layer.

Suggested command: `composer run-script test`.

---

## 📜 Commit Message
```
feat(phase8): add simulation layer with failure and latency hooks
```

---

## 📦 Files Generated
- README.phase8.md
- src/Simulation/ErrorSimulator.php
- src/Simulation/LatencySimulator.php
- src/Simulation/FailureScenario.php
- src/Adapters/Base/Traits/SimulationAwareTrait.php
- tests/Simulation/ErrorSimulatorTest.php
- adapter/storage hook updates
