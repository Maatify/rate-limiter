# **Phase 17 — RedisClientInterface Contract**

[![Maatify Common](https://img.shields.io/badge/Maatify-Common-blue?style=for-the-badge)](../../README.md)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

**Project:** maatify/common
**Version:** 1.0.9
**Status:** Completed
**Date:** 2025-11-26

---

## 🎯 Overview

Phase 17 introduces the **RedisClientInterface**, the new unified Redis contract used across the entire Maatify ecosystem.

This interface standardizes a **minimal, predictable key–value API** that works seamlessly across:

* **phpredis** (Redis extension)
* **Predis** (pure PHP client)
* **FakeRedisConnection** (testing layer)

The goal is to ensure every Redis-capable component behaves consistently—whether in production, staging, or testing environments.

---

## 📌 Goals

* Define a lightweight, universal Redis contract.
* Unify operations across different Redis drivers.
* Ensure compatibility with FakeRedis for deterministic tests.
* Reduce adapter-specific handling across locking, caching, and repository layers.
* Provide a consistent API surface for future Redis utilities.

---

## 📁 Deliverables

### **New File Added:**

```
src/Contracts/Redis/RedisClientInterface.php
```

### **Includes:**

* `get(key)`
* `set(key, value)`
* `del(...keys)`
* `keys(pattern)`

All implemented using **strict typing**, **PSR-12**, and **minimal KV semantics** suitable for both real and fake Redis adapters.

---

## 🧩 Contract Specification

The RedisClientInterface defines:

### **Lookup / Retrieval**

* **get(string $key): string|false|null**
  Retrieve a value from Redis. Returns string on success, false on failure, or null when key does not exist.

### **Mutation / Write Operations**

* **set(string $key, string $value): bool**
  Set a key’s value with strict string semantics.

* **del(string ...$keys): int**
  Delete multiple keys at once and return the number of deleted entries.

### **Wildcard Matching**

* **keys(string $pattern): array<int, string>**
  Pattern-based key search using Redis glob syntax.

---

## 🔗 Integration

This interface serves as the Redis foundation for:

### **maatify/common**

* Used internally by RedisLockManager and HybridLockManager.

### **maatify/data-adapters**

* Upcoming RedisAdapter will fully implement this contract.

### **maatify/data-fakes**

* FakeRedisConnection already conforms to this contract.

### **maatify/data-repository**

* Future caching decorators will rely on this minimal Redis API.

---

## 🧪 Testing

* Verified compatibility with:

    * FakeRedisConnection
    * phpredis mock
    * Predis mock

* PHPStan validation (max level)

* Signature verification against lock managers

* Consistency tests across real/fake Redis environments

All tests passed under PHPUnit 11.

---

## 📦 Outputs

* `RedisClientInterface.php`
* Updated roadmap.json (Phase 17)
* Updated VERSION → **1.0.9**

---

## ✅ Phase Completion Summary

Phase 17 establishes the **unified Redis client layer** required for deterministic testing and driver-agnostic Redis operations.

This phase unlocks:

* More stable Redis support across all libraries
* Clean abstraction for upcoming caching layers
* Driver-agnostic locking and key-value utilities
* Simplified fake/real driver switching in tests

With this phase completed, Redis is now fully normalized across the Maatify ecosystem.

---

**Phase 17 — Completed Successfully**

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — [https://www.maatify.dev](https://www.maatify.dev)

📘 Full documentation & source code:
[https://github.com/Maatify/common](https://github.com/Maatify/common)

---
