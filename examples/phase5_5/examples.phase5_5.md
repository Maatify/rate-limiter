# Phase 5.5: Native PHP Examples

This directory contains native PHP examples demonstrating the behavior of the Rate Limiter library.
These examples rely only on `vendor/autoload.php` and use a dummy in-memory driver to replicate real-world scenarios without external infrastructure.

## Examples

### 1. Basic Flow (`01_basic_flow.php`)
Demonstrates how to instantiate the `EnforcingRateLimiter` with a driver and perform a successful rate limit attempt.
It shows the structure of the returned `RateLimitStatusDTO`.

### 2. Global Blocking (`02_global_blocking.php`)
Simulates a scenario where the **Global** rate limit is exceeded.
- The `EnforcingRateLimiter` checks the global limit *before* the action limit.
- If the global limit is exceeded, a `TooManyRequestsException` is thrown with `source: 'global'`.
- The exception contains a `RateLimitStatusDTO` with backoff details.

### 3. Action Blocking (`03_action_blocking.php`)
Simulates a scenario where the Global limit passes, but the specific **Action** limit is exceeded.
- The `EnforcingRateLimiter` proceeds to check the action limit.
- If exceeded, a `TooManyRequestsException` is thrown with `source: 'action'`.

### 4. Status Check (`04_status_check.php`)
Demonstrates how to retrieve the current status without incrementing the counter.
- `EnforcingRateLimiter::status()` proxies the call to the driver.
- **Important:** It returns the status of the specific *Action*, not the Global limit.

## Key Concepts

- **EnforcingRateLimiter**: The main entry point. It orchestrates Global vs Action limits and calculates backoff.
- **RateLimitStatusDTO**: The data transfer object containing limit, remaining, retry-after, and source information.
- **TooManyRequestsException**: The exception thrown when a limit is exceeded. It carries the `RateLimitStatusDTO` in its `$status` property.
- **Drivers**: Responsible for atomic counting and enforcing limits. They do not know about "Global" or "Action" concepts directly; they just enforce the key provided to them.
