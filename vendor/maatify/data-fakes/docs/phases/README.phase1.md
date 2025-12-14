# Phase 1 — Project Bootstrap & Core Architecture
**Version:** 1.0.0
**Project:** maatify/data-fakes

---

## 🎯 Objective
This phase establishes the foundational architecture for the entire `maatify/data-fakes` library, ensuring that all Fake Adapters and Fake Repositories follow the exact same contract expectations used by real adapters in the Maatify ecosystem.

The architecture is fully dependent on two core interfaces:

1. **AdapterInterface**
   `Maatify\Common\Contracts\Adapter\AdapterInterface`

2. **ResolverInterface**
   `Maatify\DataAdapters\Contracts\ResolverInterface`

These interfaces guarantee compatibility between fake and real adapters, allowing repository tests to run identically on both environments.

---

## 📦 Deliverables Created
### **Contracts**
- `src/Contracts/FakeAdapterInterface.php`
- `src/Contracts/FakeRepositoryInterface.php`
- `src/Contracts/FakeResolverInterface.php`

### **Core Storage Layer**
- `src/Storage/FakeStorageLayer.php`

### **Base Adapter**
- `src/Adapters/Base/AbstractFakeAdapter.php`

### **Bootstrap Files**
- `composer.json`
- `phpunit.xml`
- `README.md` (root)

### **Tests**
- `tests/bootstrap.php`
- `tests/Storage/FakeStorageLayerTest.php`

---

## 🧩 Architecture Summary
Phase 1 introduces the **FakeStorageLayer**, a central in-memory engine that powers all fake adapters.
Every Fake Adapter must:

- Fully implement `AdapterInterface`
- Be resolvable using `ResolverInterface`
- Use FakeStorageLayer for deterministic storage behavior
- Support isolation & reset between tests

---

## 🧪 Testing Setup
The testing system is prepared for full fake+real dual-environment validation:

- PHPUnit configured
- `tests/bootstrap.php` loads autoloader + fake environment
- `FakeStorageLayerTest` validates insert/select/update/delete

This ensures future phases (Fake MySQL, Fake Redis, Fake Mongo…) remain deterministic.

---

## 📚 Notes
This phase does **not** yet implement real logic inside adapters—only the structure, contracts, and bootstrap foundation.

---

## 👤 Author
**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-fakes

## 🤝 Contributors
Special thanks to the Maatify.dev engineering team and open-source contributors.

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>
