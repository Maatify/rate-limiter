# Phase 5.5: Native PHP Examples

This directory contains native PHP examples demonstrating the behavior of the Rate Limiter library using the **Resolver** as the entry point.
These examples rely only on `vendor/autoload.php` and use a dummy in-memory driver to replicate real-world scenarios.

## Core Concepts

1.  **Entry Point**: `RateLimiterResolver::resolve()` is the only public way to obtain a rate limiter instance. `EnforcingRateLimiter` is an internal implementation detail.
2.  **Global vs Action**: The system automatically enforces a "Global" limit before checking the specific "Action" limit.
3.  **Drivers**: Drivers are "dumb" counters. They do not calculate backoff or know about global rules.
4.  **Exceptions**: A `TooManyRequestsException` carries a `RateLimitStatusDTO` with full context (retry time, source, etc.).

## Examples

### 1. Basic Flow (`01_basic_flow.php`)
Demonstrates the standard usage:
- Create `RateLimiterResolver`.
- Resolve a driver.
- Call `attempt()`.
- Inspect the returned `RateLimitStatusDTO`.

### 2. Global Blocking (`02_global_blocking.php`)
Simulates a scenario where the **Global** rate limit is exceeded.
- The system checks the global limit *before* the action limit.
- If the global limit is exceeded, `TooManyRequestsException` is thrown with `source: 'global'`.

### 3. Action Blocking (`03_action_blocking.php`)
Simulates a scenario where the Global limit passes, but the specific **Action** limit is exceeded.
- If the global limit passes, the specific action limit is checked.
- If exceeded, `TooManyRequestsException` is thrown with `source: 'action'`.

### 4. Backoff Flow (`04_backoff_flow.php`)
Demonstrates how the library calculates exponential backoff.
- Repeated failures result in increased `retryAfter` and `backoffSeconds`.
- This calculation is handled internally by the `EnforcingRateLimiter`, not the driver.

### 5. Exception Handling (`05_exception_handling.php`)
Detailed inspection of the `TooManyRequestsException`.
- Shows how to access the `RateLimitStatusDTO` via `$e->status`.
- Prints all relevant properties for client response construction.
