<?php

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/InMemoryDriver.php';

use Maatify\RateLimiter\Examples\Phase5_5\ExampleAction;
use Maatify\RateLimiter\Examples\Phase5_5\ExamplePlatform;
use Maatify\RateLimiter\Examples\Phase5_5\InMemoryDriver;
use Maatify\RateLimiter\Resolver\EnforcingRateLimiter;
use Maatify\RateLimiter\Resolver\ExponentialBackoffPolicy;

$driver = new InMemoryDriver();
$rateLimiter = new EnforcingRateLimiter($driver, new ExponentialBackoffPolicy());

$key = 'user_123';
$action = new ExampleAction('status_check');
$platform = new ExamplePlatform('web');

// Perform some activity to change state
$rateLimiter->attempt($key, $action, $platform);

// Check status
// Note: EnforcingRateLimiter::status() proxies to the driver for the SPECIFIC action/platform.
// It does NOT check the global limit status.
$status = $rateLimiter->status($key, $action, $platform);

echo "Status Check:\n";
echo "Limit: {$status->limit}\n";
echo "Remaining: {$status->remaining}\n";
echo "Source: {$status->source}\n"; // Expected: 'action' (enriched by EnforcingRateLimiter if missing)
