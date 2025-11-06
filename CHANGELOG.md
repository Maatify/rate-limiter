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