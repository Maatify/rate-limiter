<?php

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/InMemoryDriver.php';

use Maatify\RateLimiter\Examples\Phase5_5\ExampleAction;
use Maatify\RateLimiter\Examples\Phase5_5\ExamplePlatform;
use Maatify\RateLimiter\Examples\Phase5_5\InMemoryDriver;
use Maatify\RateLimiter\Resolver\EnforcingRateLimiter;
use Maatify\RateLimiter\Resolver\ExponentialBackoffPolicy;

// 1. Setup the dummy driver
$driver = new InMemoryDriver();

// 2. Setup the EnforcingRateLimiter (the main entry point)
// Note: EnforcingRateLimiter requires a driver and a backoff policy.
$rateLimiter = new EnforcingRateLimiter(
    $driver,
    new ExponentialBackoffPolicy()
);

// 3. Define the context
$key = 'user_123';
$action = new ExampleAction('login');
$platform = new ExamplePlatform('web');

// 4. Perform an attempt
try {
    $status = $rateLimiter->attempt($key, $action, $platform);

    echo "Attempt successful!\n";
    echo "Limit: {$status->limit}\n";
    echo "Remaining: {$status->remaining}\n";
    echo "Source: {$status->source}\n"; // Should be 'action' or 'global' (defaults to action if global passes)

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
