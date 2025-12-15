# Phase 5.5 Examples

These examples demonstrate the correct usage of the Rate Limiter library as defined in Phase 5.
They mimic the behavior tested in `tests/` and the public API contract.

## Native PHP Examples

These scripts are standalone and require `vendor/autoload.php`.

### 1. Global Limiter Flow
Demonstrates how the `EnforcingRateLimiter` enforces the Global Rate Limit before checking the specific action limit.
- **File:** `native/global_limiter_flow.php`
- **Key Concept:** If the global limit is exceeded, the action limit is not checked.

### 2. Action Limiter Flow
Demonstrates the standard flow where the Global Limit passes, but the specific Action Limit is exceeded.
- **File:** `native/action_limiter_flow.php`
- **Key Concept:** `EnforcingRateLimiter` ensures granularity by checking specific actions after global checks.

### 3. Exponential Backoff Flow
Shows how `ExponentialBackoffPolicy` calculates delays when a limit is exceeded.
- **File:** `native/exponential_backoff_flow.php`
- **Key Concept:** The `TooManyRequestsException` contains an enhanced `RateLimitStatusDTO` with `retryAfter` and `backoffSeconds`.

### 4. DTO Serialization
Examples of creating, accessing, and serializing the `RateLimitStatusDTO`.
- **File:** `native/dto_serialization.php`

### 5. Exception Flow
Demonstrates catching `TooManyRequestsException` and retrieving the underlying `RateLimitStatusDTO` for client responses.
- **File:** `native/exception_flow.php`
- **Key Concept:** The `TooManyRequestsException` exposes the `RateLimitStatusDTO` via the public readonly property `$status`. There is no `getStatus()` method.

## Important Implementation Notes

- **Source of Truth:** These examples are derived strictly from `src/` and `tests/`.
- **Backoff & Source:** The `EnforcingRateLimiter` is the sole authority for applying backoff logic (via `BackoffPolicyInterface`) and assigning the `source` property ('global' or 'action') to the DTO. Drivers should not attempt to calculate backoff manually.
- **Exception Handling:** When catching `TooManyRequestsException`, always access the DTO via `$e->status`.
