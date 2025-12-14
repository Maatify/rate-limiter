<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

// 1. Create Exception with DTO
$status = new RateLimitStatusDTO(
    limit: 60,
    remaining: 0,
    resetAfter: 120,
    retryAfter: 120,
    blocked: true,
    backoffSeconds: 120,
    nextAllowedAt: '2025-01-01 12:00:00',
    source: 'mysql'
);

try {
    throw new TooManyRequestsException('Rate limit exceeded', 429, $status);
} catch (TooManyRequestsException $e) {
    // 2. Catch and Inspect
    echo "Message: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";

    $attachedStatus = $e->getStatus();
    if ($attachedStatus) {
        echo "Attached Status Remaining: " . $attachedStatus->remaining . "\n";
        echo "Attached Status RetryAfter: " . $attachedStatus->retryAfter . "\n";
    }
}
