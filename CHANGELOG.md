# 📜 CHANGELOG

## [1.0.0-alpha – Phase 1 (Local)]
- Initialized project without Docker
- Added local `.env.example` configuration
- Enabled Redis, MongoDB, and MySQL local connectivity
- Created Composer + CI setup
- Prepared for Phase 2 – Core Architecture
---

## [1.0.0-alpha – Phase 2]
- Added RateLimiterInterface (core contract)
- Added RateLimitActionEnum and PlatformEnum
- Added RateLimitConfig for default actions
- Added RateLimitStatusDTO for structured responses
- Added TooManyRequestsException
- Added CoreStructureTest.php

---
## [1.0.0-alpha – Phase 3]
- Implemented RedisRateLimiter
- Implemented MongoRateLimiter
- Implemented MySQLRateLimiter
- Added test DriversTest.php

---
## [1.0.0-alpha – Phase 3.1]
- Added RateLimitActionInterface and PlatformInterface contracts.
- Updated RateLimitActionEnum and PlatformEnum to implement contracts.
- Updated RateLimiterInterface and all drivers to use contracts instead of hard-coded enums.
- Improved library reusability and Open/Closed design compliance.

---
## [1.0.0-alpha – Phase 4]
- Added RateLimiterResolver for dynamic driver selection.
- Added RateLimitHeadersMiddleware compatible with PSR-15 (Slim / Laravel).
- Integrated retry-after headers and rate status headers.
- Added MiddlewareTest to validate resolution and header injection.