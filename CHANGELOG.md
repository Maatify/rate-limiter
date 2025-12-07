# 📜 CHANGELOG

All notable changes to **maatify/rate-limiter** will be documented in this file.  
This project follows **Semantic Versioning (SemVer)**.

---

## [1.0.0-alpha – Phase 1] – Local Environment Bootstrap

### ✅ Added
- Initialized project structure without Docker.
- Added local `.env.example` configuration.
- Enabled local connectivity for:
    - Redis
    - MongoDB
    - MySQL
- Created Composer configuration.
- Set up initial Continuous Integration skeleton.
- Prepared foundation for **Phase 2 – Core Architecture**.

---

## [1.0.0-alpha – Phase 2] – Core Architecture

### ✅ Added
- `RateLimiterInterface` as the core contract.
- `RateLimitActionEnum` and `PlatformEnum`.
- `RateLimitConfig` for default action configuration.
- `RateLimitStatusDTO` for structured rate-limit responses.
- `TooManyRequestsException` for standardized blocking behavior.
- `CoreStructureTest.php` for architecture validation.

---

## [1.0.0-alpha – Phase 3] – Storage Drivers

### ✅ Added
- Full implementation of:
    - `RedisRateLimiter`
    - `MongoRateLimiter`
    - `MySQLRateLimiter`
- Added driver integration test:
    - `DriversTest.php`

---

## [1.0.0-alpha – Phase 3.1] – Enum Contracts Refactor

### ✅ Added
- Introduced:
    - `RateLimitActionInterface`
    - `PlatformInterface`

### 🔄 Changed
- Updated all enums to implement the new contracts.
- Refactored `RateLimiterInterface` and all drivers to depend on **interfaces instead of concrete enums**.

### ✅ Impact
- Improved reusability.
- Achieved full **Open/Closed Principle compliance**.

---

## [1.0.0-alpha – Phase 4] – Resolver & Middleware

### ✅ Added
- `RateLimiterResolver` for dynamic driver resolution.
- `RateLimitHeadersMiddleware` (fully PSR-15 compatible).
- Integrated response headers:
    - `Retry-After`
    - `X-RateLimit-Limit`
    - `X-RateLimit-Remaining`
    - `X-RateLimit-Reset`
- Added `MiddlewareTest` to validate:
    - Resolver correctness
    - Header injection behavior

---

## [1.0.0-alpha – Phase 4.1] 🚀 Continuous Integration

### ✅ Added
- Docker-based CI pipeline using:
    - `docker-compose.ci.yml`
- GitHub Actions workflow:
    - `.github/workflows/ci.yml`
- Integrated containers:
    - Redis 7
    - MySQL 8
    - MongoDB 7
- Enabled **live PHPUnit output streaming** in CI logs.
- Automated `.env` generation for CI environment.
- Added Composer dependency caching.
- Enabled optional upload of test artifacts (`tests/_output`).

### ✅ Result
- Full **end-to-end integration testing environment** completed.

---

## [1.0.0-alpha – Phase 5] – Exponential Backoff & Global Limit

### 🧠 Adaptive Security Enhancements

### ✅ Added
- **Adaptive exponential backoff** using `2ⁿ` logic.
- **Global per-IP rate limit** across all actions.
- Extended `RateLimitStatusDTO` with:
    - `backoffSeconds` → adaptive delay in seconds
    - `nextAllowedAt` → timestamp for next allowed attempt
- Enhanced `TooManyRequestsException` to carry:
    - Retry metadata
    - Backoff metadata
- New environment variables:
    - `GLOBAL_RATE_LIMIT`
    - `GLOBAL_RATE_WINDOW`
    - `BACKOFF_BASE`
    - `BACKOFF_MAX`
- Added `RateLimitStatusDTO::fromArray()` for:
    - Cache reconstruction
    - Database hydration
- Implemented **global per-IP rate tracking for Redis**.
- Updated resolver and all drivers to support:
    - Backoff-aware status responses

### 🧪 Tests
- Added full backoff validation in:
    - `tests/BackoffTest.php`
- Validates:
    - Backoff progression
    - Maximum cap
    - Timestamp formatting

### 📄 Documentation
- Added phase documentation:
    - `docs/phases/README.phase5.md`

---

## 🏆 Stability Note

All `1.0.0-alpha` releases follow a strict **phase-based stabilization roadmap**.  
Breaking changes are avoided between alpha phases unless strictly required for architectural correctness.

---

## 📌 Next Planned Phase

- **Phase 6** – Advanced abuse detection strategies
- **Phase 7** – Distributed lock coordination (optional)
- **Phase 8** – Observability & metrics integration

---

© Maatify.dev — Unified Ecosystem for Modern PHP Libraries
