<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;

// 1. Create a DTO manually
$dto = new RateLimitStatusDTO(
    limit: 100,
    remaining: 50,
    resetAfter: 30,
    retryAfter: null,
    blocked: false,
    backoffSeconds: null,
    nextAllowedAt: null,
    source: 'redis'
);

// 2. Access Properties
echo "Limit: " . $dto->limit . "\n";
echo "Remaining: " . $dto->remaining . "\n";

// 3. Serialize to Array
$array = $dto->toArray();
echo "Array Export: " . print_r($array, true);

// 4. Re-create from Array
$newDto = RateLimitStatusDTO::fromArray($array);
echo "Re-created Source: " . $newDto->source . "\n";

// 5. Serialize to JSON
echo "JSON: " . json_encode($array) . "\n";
