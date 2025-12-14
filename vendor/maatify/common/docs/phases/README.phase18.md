تمام ✅ — دي **وثيقة Phase 18** بنفس الأسلوب والشكل الاحترافي اللي بعتّه لِـ Phase 17، ومهيّأة مباشرة للإضافة في `docs/` أو الربط من `README.full.md`:

---

# **Phase 18 — KeyValueAdapterInterface Foundation**

[![Maatify Common](https://img.shields.io/badge/Maatify-Common-blue?style=for-the-badge)](../../README.md)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

**Project:** maatify/common
**Version:** 1.0.10
**Status:** Pending
**Date:** 2025-12-09

---

## 🎯 Overview

Phase 18 introduces the **KeyValueAdapterInterface**, a **generic, storage-agnostic key–value contract** that operates at a **higher abstraction level than Redis itself**.

While Phase 17 normalized the **Redis protocol layer**, this phase normalizes the **behavioral storage layer** used by:

* Brute-force protection (Security Guard)
* Rate limiting
* OTP systems
* Sessions & temporary tokens
* Cache engines
* In-memory and fake storage layers

This interface decouples all higher-level systems from **Redis-specific semantics**, enabling true driver-agnostic KV storage.

---

## 📌 Goals

* Define a **generic key–value storage contract** independent of Redis protocol details.
* Provide a unified API for all KV-based components.
* Enable **strict PHPStan max-level typing** for KV drivers.
* Decouple `security-guard` and `rate-limiter` from Redis client specifics.
* Support Redis, FakeRedis, in-memory cache, and future KV engines.
* Complement — not replace — `RedisClientInterface`.

---

## 📁 Deliverables

### **New File Added:**

```
src/Contracts/Adapter/KeyValueAdapterInterface.php
```

### **Core Methods:**

* `get(key)`
* `set(key, value, ttl)`
* `del(key)`

All implemented with:

* ✅ Strict typing
* ✅ PSR-12
* ✅ Storage-agnostic semantics
* ✅ PHPStan level max compatibility

---

## 🧩 Contract Specification

The **KeyValueAdapterInterface** defines:

### **Lookup / Retrieval**

* **get(string $key): mixed**
  Retrieve any value from the underlying KV storage engine.

---

### **Mutation / Write Operations**

* **set(string $key, mixed $value, ?int $ttl = null): void**
  Store a value with optional TTL in seconds.

* **del(string $key): void**
  Delete a single key from the KV store.

---

## 🔗 Integration

This interface becomes the **foundation KV contract** for:

### **maatify/common**

* Shared KV abstraction for all Maatify packages.

### **maatify/security-guard**

* Used for:

  * Failure counters
  * IP blocks
  * Temporary blacklists
    Replaces direct Redis-style `get/set/del` calls with typed KV storage.

### **maatify/rate-limiter**

* Used for:

  * Hit counters
  * Window tracking
  * Backoff TTL storage

### **maatify/data-adapters**

* Redis adapters will implement:

  * `RedisClientInterface` (protocol)
  * `KeyValueAdapterInterface` (behavior)

### **maatify/data-fakes**

* FakeRedis & in-memory adapters will implement this interface for:

  * Deterministic unit testing
  * Full TTL simulation
  * No-driver test isolation

---

## 🧪 Testing

Planned validation includes:

* ✅ FakeRedis adapter conformance
* ✅ In-memory KV driver tests
* ✅ Security Guard KV behavior tests
* ✅ Rate Limiter KV integration tests
* ✅ PHPStan (max level) contract enforcement
* ✅ Signature validation against all KV consumers

Target test runner: **PHPUnit 11**

---

## 📦 Outputs

* `KeyValueAdapterInterface.php`
* Updated roadmap.json (Phase 18)
* Updated VERSION → **1.0.10**

---

## ✅ Phase Completion Summary (Upon Completion)

Phase 18 establishes the **universal behavioral KV storage contract** for the entire Maatify ecosystem.

This phase unlocks:

* Clean decoupling between business logic and Redis protocol
* Strict static typing for KV drivers
* Unified Fake/Real behavior across all KV-based systems
* Seamless future expansion for:

  * Cache
  * Tokens
  * Queues
  * Session stores

With this phase completed, **all KV operations in Maatify become driver-agnostic and test-safe**.

---

**Phase 18 — Completed Successfully**

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — [https://www.maatify.dev](https://www.maatify.dev)

📘 Full documentation & source code:
[https://github.com/Maatify/common](https://github.com/Maatify/common)

---
