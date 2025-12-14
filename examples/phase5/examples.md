# Phase 5: Rate Limiter Examples

This directory contains canonical usage examples for the Phase 5 implementation of the Rate Limiter. These examples are strictly derived from the test suite and demonstrate the correct usage of the library without framework dependencies.

## Examples

### 1. Global Limiter Flow
**File:** `native/global_limiter_flow.php`
**Derived from:** `tests/Phase5/GlobalLimiterTest.php`

Demonstrates that the Global Limiter is always evaluated *before* the Action Limiter. If the Global Limiter blocks a request, the Action Limiter is never checked.

- **Key Behavior:** `EnforcingRateLimiter` enforces global limits first.
- **Exception:** `TooManyRequestsException` is thrown with `source: global`.

### 2. Action Limiter Flow
**File:** `native/action_limiter_flow.php`
**Derived from:** `tests/Phase5/ActionLimiterTest.php`

Demonstrates the standard success path (both pass) and the action failure path (global passes, action fails).

- **Key Behavior:** Action Limiter is only checked if Global Limiter passes.
- **Exception:** `TooManyRequestsException` is thrown with `source: action`.

### 3. Exponential Backoff
**File:** `native/exponential_backoff_flow.php`
**Derived from:** `tests/Phase5/BackoffPolicyTest.php`

Demonstrates the `ExponentialBackoffPolicy` logic, including exponential growth ($base^{over\_limit}$) and capping by reset window or max delay.

- **Key Behavior:** Delay increases exponentially as requests exceed the limit further (negative remaining).

### 4. DTO Serialization
**File:** `native/dto_serialization.php`
**Derived from:** `tests/Phase5/RateLimitStatusDTOTest.php`

Demonstrates how to serialize `RateLimitStatusDTO` to an array and re-instantiate it. Useful for API responses or cache storage.

### 5. Exception Propagation
**File:** `native/exception_flow.php`
**Derived from:** `tests/Phase5/ExceptionPropagationTest.php`

Demonstrates that `TooManyRequestsException` carries the full `RateLimitStatusDTO` and provides helper methods like `getRetryAfter()` and `getNextAllowedAt()`.

### 6. Custom Backoff Policy
**File:** `native/custom_backoff_policy.php`
**Derived from:** `tests/Phase5/BackoffPolicyTest.php` (Conceptually)

Demonstrates how to implement the `BackoffPolicyInterface` to create a custom backoff strategy (e.g., Linear Backoff) and inject it into the `EnforcingRateLimiter`.

## Usage

To run any example, execute it with PHP from the project root:

```bash
php examples/phase5/native/global_limiter_flow.php
php examples/phase5/native/action_limiter_flow.php
# ... etc
```

These examples require `vendor/autoload.php` to be present (run `composer install` first).
