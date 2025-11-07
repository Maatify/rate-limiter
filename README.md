# Maatify Rate Limiter

A PSR-compliant Rate Limiter library supporting Redis, MongoDB, and MySQL.

<!-- PHASE_STATUS_START -->
## ✅ Completed Phases
- [x] Phase 1 – Environment Setup (Local)
- [x] Phase 2 – Core Architecture
- [x] Phase 3 – Storage Drivers
<!-- PHASE_STATUS_END -->

---

## ⚙️ Local Setup

```bash
composer install
cp .env.example .env
````

Then edit `.env` to match your local database configuration.

---

## 🧠 Description

The Maatify Rate Limiter provides a unified abstraction for distributed rate limiting
with smart backoff algorithms, driver-based storage (Redis, MongoDB, MySQL),
and full PSR-12 compliance.

---

## 📂 Project Structure

```
maatify-rate-limiter/
│
├── .env.example
├── composer.json
├── .github/
│   └── workflows/
│       └── ci.yml
├── src/
│   ├── Config/
│   │   └── RateLimitConfig.php
│   ├── Contracts/
│   │   └── RateLimiterInterface.php
│   ├── DTO/
│   │   └── RateLimitStatusDTO.php
│   ├── Drivers/
│   │   ├── RedisRateLimiter.php
│   │   ├── MongoRateLimiter.php
│   │   └── MySQLRateLimiter.php
│   ├── Enums/
│   │   ├── RateLimitActionEnum.php
│   │   └── PlatformEnum.php
│   └── Exceptions/
│       └── TooManyRequestsException.php
│
├── tests/
│   ├── bootstrap.php
│   ├── SampleTest.php
│   ├── CoreStructureTest.php
│   └── DriversTest.php
│
├── docs/
│   └── phases/
│       ├── README.phase1.md
│       ├── README.phase2.md
│       └── README.phase3.md
│
├── README.md
├── CHANGELOG.md
└── VERSION
```

---

## 🧩 Current Version

```
1.0.0-alpha-phase3
```

---

## 📜 Changelog Summary

### Phase 1 – Environment Setup

* Local environment initialized
* Composer, PHPUnit, and CI configured

### Phase 2 – Core Architecture

* Added `RateLimiterInterface`
* Added enums (`RateLimitActionEnum`, `PlatformEnum`)
* Added `RateLimitConfig`, `RateLimitStatusDTO`, and `TooManyRequestsException`

### Phase 3 – Storage Drivers

* Implemented Redis, MongoDB, and MySQL drivers
* Added corresponding unit tests
* Core logic ready for resolver integration

---

## 🚀 Next Phase

**Phase 4 – Resolver & Middleware**

* Dynamic driver resolver
* Middleware for Slim Framework and Laravel
* Functional integration tests

