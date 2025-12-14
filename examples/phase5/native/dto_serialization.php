<?php

/**
 * DTO Serialization Example
 *
 * Simulates: RateLimitStatusDTOTest::testToArrayAndFromArray
 *
 * This example demonstrates:
 * 1. DTO instantiation
 * 2. Serialization to array (toArray)
 * 3. Re-instantiation from array (fromArray)
 * 4. Data integrity verification
 */

require __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;

echo "=== Scenario 1: Instantiate DTO ===\n";

$dto = new RateLimitStatusDTO(
    limit: 10,
    remaining: 5,
    resetAfter: 60,
    retryAfter: null,
    blocked: false,
    backoffSeconds: null,
    nextAllowedAt: null,
    source: 'action'
);

echo "DTO Created:\n";
echo "Limit: " . $dto->limit . "\n";
echo "Remaining: " . $dto->remaining . "\n";
echo "Source: " . $dto->source . "\n";

echo "\n=== Scenario 2: Convert to Array ===\n";

$array = $dto->toArray();
print_r($array);

echo "\n=== Scenario 3: Recreate from Array ===\n";

$newDto = RateLimitStatusDTO::fromArray($array);

// Verify equality
$isEqual = (
    $dto->limit === $newDto->limit &&
    $dto->remaining === $newDto->remaining &&
    $dto->resetAfter === $newDto->resetAfter &&
    $dto->source === $newDto->source
);

echo "Equality Check: " . ($isEqual ? "PASS" : "FAIL") . "\n";
