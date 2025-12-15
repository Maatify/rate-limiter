<?php

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/InMemoryDriver.php';

use Maatify\RateLimiter\Examples\Phase5_5\ExampleAction;
use Maatify\RateLimiter\Examples\Phase5_5\ExamplePlatform;
use Maatify\RateLimiter\Examples\Phase5_5\InMemoryDriver;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Resolver\EnforcingRateLimiter;
use Maatify\RateLimiter\Resolver\ExponentialBackoffPolicy;

$driver = new InMemoryDriver();

// Configure the driver to limit the GLOBAL scope to 1 request.
// The EnforcingRateLimiter uses 'global' as the action and platform name for global checks.
$driver->setLimit('user_123:global:global', 1);

$rateLimiter = new EnforcingRateLimiter($driver, new ExponentialBackoffPolicy());

$key = 'user_123';
$action = new ExampleAction('login');
$platform = new ExamplePlatform('web');

echo "1. First attempt (Global count: 1/1) - Should Pass\n";
$rateLimiter->attempt($key, $action, $platform);
echo "   Success.\n\n";

echo "2. Second attempt (Global count: 2/1) - Should Fail\n";
try {
    $rateLimiter->attempt($key, $action, $platform);
} catch (TooManyRequestsException $e) {
    echo "   Caught TooManyRequestsException!\n";

    // Access the Status DTO from the exception
    $status = $e->status;

    if ($status) {
        echo "   Source: {$status->source}\n"; // Expected: 'global'
        echo "   Blocked: " . ($status->blocked ? 'Yes' : 'No') . "\n";
        echo "   Retry After: {$status->retryAfter} seconds\n";
        echo "   Backoff Seconds: {$status->backoffSeconds}\n";
    }
}
