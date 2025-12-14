# 🧩 Phase 5 — Global Enforcement & Backoff (Resolver-Level)

[![Maatify Rate Limiter](https://img.shields.io/badge/Maatify-Rate--Limiter-blue?style=for-the-badge)](https://github.com/Maatify/rate-limiter)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

---

## 🎯 Objective

This phase introduces **resolver-level enforcement** for rate limiting, combining:

* **Global identifier-based limiting** (applied before any action)
* **Centralized exponential backoff**
* **Strict separation of concerns** between drivers and enforcement logic

The goal is to make rate limiting **predictable, testable, and extensible**
without leaking behavioral logic into storage drivers.

---

## 🧠 Architectural Shift (Important)

> **No driver implements backoff or blocking escalation logic.**
> **All enforcement happens at resolver level.**

This phase finalizes the following rules:

* Drivers = counting + TTL only
* Resolver = orchestration + enforcement
* Backoff = strategy owned by resolver
* Environment variables are **NOT accessed** inside core logic

---

## ⚙️ Implementation Overview

### 1️⃣ Centralized Exponential Backoff

Backoff is implemented as a **stateless policy**:

* Owned by the resolver
* Applied only after a limit is exceeded
* Configurable via constructor (not env)

**Default implementation:** `ExponentialBackoffPolicy`

Characteristics:

* Exponential growth
* Upper bound capped by reset window
* No persistence inside drivers

---

### 2️⃣ Global Limiter (Overlay Layer)

A **global limiter** is enforced **before** any action-based limiter.

* Identifier-only (e.g. IP, client id)
* Independent of action or platform
* Uses the same underlying drivers

Execution order (mandatory):

```
Global limiter → Action limiter → Backoff
```

If the global limiter blocks:

* Action limiter is **not executed**
* Result source = `global`

---

### 3️⃣ Action Limiter (Unchanged Responsibility)

Action-based limiters:

* Track requests per `(identifier + action)`
* Do **not** know about:

    * Global limits
    * Backoff logic
    * Escalation rules

Result source = `action`

---

## 📦 RateLimitStatusDTO — Active Fields

`RateLimitStatusDTO` now carries **full enforcement context**:

| Field            | Description                          |
|------------------|--------------------------------------|
| `limit`          | Configured limit                     |
| `remaining`      | Remaining attempts (may be negative) |
| `resetAfter`     | Seconds until counter reset          |
| `retryAfter`     | Seconds until retry is allowed       |
| `blocked`        | Final enforcement decision           |
| `backoffSeconds` | Applied backoff delay                |
| `nextAllowedAt`  | UTC timestamp                        |
| `source`         | `global` or `action`                 |

> ⚠️ `remaining` may be negative by design
> Consumers MUST rely on `blocked`, not `remaining`.

---

## 🚨 Exception Propagation

`TooManyRequestsException` now carries enforcement metadata:

* Attached `RateLimitStatusDTO`
* Helper accessors:

    * `getRetryAfter()`
    * `getNextAllowedAt()`

This allows consistent 429 responses across all drivers.

---

## 🧪 Testing Status

* **No tests are included in this phase**
* Tests are delegated to **Jules** as a separate execution step
* Coverage is **pending**

This is intentional and aligned with the Quad-AI workflow.

---

## 🧱 Summary of Changes

| Area           | Status | Notes                  |
|----------------|--------|------------------------|
| Global limiter | ✅      | Resolver-level overlay |
| Backoff logic  | ✅      | Centralized, stateless |
| Drivers        | ✅      | Storage-only           |
| DTO            | ✅      | Phase 6–ready          |
| Env usage      | ❌      | Removed from core      |
| Tests          | ⏳      | Pending (Jules)        |

---

## 🚀 Next Step

**Phase 5 (Tests)**
Behavioral tests, cross-driver invariants, and coverage enforcement.

**Phase 6 (API Freeze)**
Finalize DTO and public contracts.

---

> ⚠️ **Phase 5 is NOT complete until tests are finalized.**
> Current status: **in progress (src completed, tests pending)**

---