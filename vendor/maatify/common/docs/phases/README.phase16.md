# Phase 16 — RepositoryInterface Foundation

**Project:** maatify/common
**Version:** 1.0.8
**Status:** Completed
**Date:** 2025-11-22

---

## 🎯 Overview

Phase 16 introduces the **RepositoryInterface**, the universal repository contract used across all Maatify libraries.

This interface unifies CRUD operations, filtering, and adapter injection into a single shared contract. It ensures that **data-adapters**, **data-fakes**, and **data-repository** all operate with a consistent API.

This phase finalizes the foundational layer required for the upcoming repository system in the Maatify ecosystem.

---

## 📌 Goals

* Define a universal repository contract for all data-driven libraries.
* Standardize CRUD + filter behavior across MySQL, DBAL, Redis, and Mongo repositories.
* Ensure full compatibility with FakeRepositoryInterface used in `maatify/data-fakes`.
* Provide a consistent interface for future BaseRepository in `maatify/data-repository`.
* Support adapter injection using `AdapterInterface`.

---

## 📁 Deliverables

### **New File Added:**

```
src/Contracts/Repository/RepositoryInterface.php
```

### **Includes:**

* `find(id)`
* `findBy(filters)`
* `findAll()`
* `insert(data)`
* `update(id, data)`
* `delete(id)`
* `setAdapter(adapter)`

All documented with strict typing and PSR‑12 compliance.

---

## 🧩 Contract Specification

The RepositoryInterface defines:

### **Lookup Methods**

* **find:** Get a single row by its ID.
* **findBy:** Query rows using key/value filters.
* **findAll:** Retrieve all rows.

### **Mutation Methods**

* **insert:** Create and return the inserted ID.
* **update:** Edit an existing record.
* **delete:** Remove a record.

### **Adapter Injection**

* **setAdapter:** Assign the underlying adapter instance.

---

## 🔗 Integration

This interface is a shared foundation for the following libraries:

### **maatify/data-fakes**

* Implements `FakeRepositoryInterface` based on this contract.

### **maatify/data-repository** *(upcoming)*

* Will introduce `BaseRepository` and `GenericRepository` implementations.

### **maatify/data-adapters**

* Ensures consistent mapping between adapter-level operations and repository-level logic.

---

## 🧪 Testing

* Structural validation via PHPStan (Level: max)
* Interface signature validation
* Compatibility confirmed with FakeRedis, FakeMySQL, FakeDBAL, and FakeMongo adapters

All tests passed under PHPUnit 11.

---

## 📦 Outputs

* `RepositoryInterface.php`
* Updated roadmap.json (Phase 16)
* Updated version: **1.0.8**

---

## ✅ Phase Completion Summary

Phase 16 establishes the final missing core contract required for a fully modular data ecosystem. With this phase completed, all Maatify libraries now share a consistent and predictable repository API.

The ecosystem is now ready for:

* Advanced Repository architecture
* DTO hydration layers
* Fake repository simulation
* Generic repository factories

---

**Phase 16 — Completed Successfully**

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/common

---