<?php

/**
 * Exception Propagation Example
 *
 * Simulates: ExceptionPropagationTest
 *
 * This example demonstrates:
 * 1. Throwing TooManyRequestsException with a DTO
 * 2. Catching the exception
 * 3. Accessing the embedded DTO and its properties
 */

require __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

echo "=== Scenario: Exception Propagation ===\n";

try {
    // 1. Create Status DTO
    $timestamp = '2025-01-01 12:00:00';
    $dto = new RateLimitStatusDTO(
        limit: 10,
        remaining: 0,
        resetAfter: 60,
        retryAfter: 30,
        blocked: true,
        backoffSeconds: 30,
        nextAllowedAt: $timestamp,
        source: 'action'
    );

    // 2. Throw Exception
    echo "Throwing TooManyRequestsException...\n";
    throw new TooManyRequestsException('Rate limit exceeded', 429, $dto);

} catch (TooManyRequestsException $e) {
    // 3. Catch and Inspect
    echo "Caught Exception: " . $e->getMessage() . "\n";

    // Access DTO
    $status = $e->status;
    echo "Status Source: " . $status->source . "\n";

    // Access Helpers
    echo "Retry After (via Helper): " . $e->getRetryAfter() . "s\n";
    echo "Next Allowed At (via Helper): " . $e->getNextAllowedAt() . "\n";

    // Validate
    if ($e->getRetryAfter() === 30 && $e->getNextAllowedAt() === '2025-01-01 12:00:00') {
        echo "SUCCESS: Exception propagated data correctly.\n";
    } else {
        echo "FAILURE: Data mismatch.\n";
    }
}
