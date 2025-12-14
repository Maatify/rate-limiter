<?php

/**
 * 🎯 DTO Serialization Example
 *
 * Demonstrates:
 * - Creating a RateLimitStatusDTO.
 * - Converting it to an array for JSON responses.
 * - Recreating the DTO from an array (useful for caching/storage).
 * - Verifying data integrity.
 */

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;

echo "📦 RateLimitStatusDTO Serialization\n";
echo "-----------------------------------\n";

// 1. Create original DTO
$original = new RateLimitStatusDTO(
    limit: 100,
    remaining: 42,
    resetAfter: 300,
    retryAfter: null,
    blocked: false,
    backoffSeconds: null,
    nextAllowedAt: '2025-12-31 23:59:59',
    source: 'action'
);

// 2. Convert to Array
$asArray = $original->toArray();

echo "1. Serialized to JSON:\n";
echo json_encode($asArray, JSON_PRETTY_PRINT) . "\n\n";

// 3. Recreate from Array
$restored = RateLimitStatusDTO::fromArray($asArray);

echo "2. Restored DTO Verification:\n";
echo "Limit matches: " . ($original->limit === $restored->limit ? "✅" : "❌") . "\n";
echo "Remaining matches: " . ($original->remaining === $restored->remaining ? "✅" : "❌") . "\n";
echo "Next Allowed At matches: " . ($original->nextAllowedAt === $restored->nextAllowedAt ? "✅" : "❌") . "\n";

// 4. Equality Check
if ($original == $restored) {
    echo "\n🎉 Success: Objects are equivalent.\n";
} else {
    echo "\n⚠️ Objects differ.\n";
}
