# 📜 **CHANGELOG**

## [1.0.0-alpha – Phase 1 (Local)]

* Initialized project without Docker.
* Added local `.env.example` configuration.
* Enabled Redis, MongoDB, and MySQL local connectivity.
* Created Composer + CI setup.
* Prepared for Phase 2 – Core Architecture.

---

## [1.0.0-alpha – Phase 2]

* Added `RateLimiterInterface` (core contract).
* Added `RateLimitActionEnum` and `PlatformEnum`.
* Added `RateLimitConfig` for default actions.
* Added `RateLimitStatusDTO` for structured responses.
* Added `TooManyRequestsException`.
* Added `CoreStructureTest.php`.

---

## [1.0.0-alpha – Phase 3]

* Implemented `RedisRateLimiter`.
* Implemented `MongoRateLimiter`.
* Implemented `MySQLRateLimiter`.
* Added test `DriversTest.php`.

---

## [1.0.0-alpha – Phase 3.1]

* Added `RateLimitActionInterface` and `PlatformInterface` contracts.
* Updated enums to implement these interfaces.
* Updated `RateLimiterInterface` + drivers to depend on contracts (not hard-coded enums).
* Improved reusability and Open/Closed compliance.

---

## [1.0.0-alpha – Phase 4]

* Added `RateLimiterResolver` for dynamic driver selection.
* Added `RateLimitHeadersMiddleware` (PSR-15 compatible).
* Integrated `Retry-After` and rate status headers.
* Added `MiddlewareTest` to validate resolution + header injection.

---

## [1.0.0-alpha – Phase 4.1 🚀 Continuous Integration]

* Introduced **Docker-based CI pipeline** using `docker-compose.ci.yml`.
* Added `.github/workflows/ci.yml` for **GitHub Actions**.
* Integrated **Redis 7**, **MySQL 8**, and **MongoDB 7** containers.
* Implemented **live PHPUnit output streaming** in CI logs (via `docker compose run --rm php`).
* Automated `.env` generation for CI environment.
* Added Composer caching for faster builds.
* Enabled optional upload of test artifacts (`tests/_output`).
* Completed full **integration test environment** for Phase 4.

---