<?php

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/InMemoryDriver.php';

use Maatify\RateLimiter\Examples\Phase5_5\ExampleAction;
use Maatify\RateLimiter\Examples\Phase5_5\ExamplePlatform;
use Maatify\RateLimiter\Examples\Phase5_5\InMemoryDriver;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;

$driver = new InMemoryDriver();

// Global limit is high (10), so it won't block.
$driver->setLimit('user_03:global:global', 10);

// Action limit is strict (1).
// Key format: key:action_name:platform_name
$driver->setLimit('user_03:checkout:api', 1);

$resolver = new RateLimiterResolver(['memory' => $driver], 'memory');
$rateLimiter = $resolver->resolve();

$key = 'user_03';
$action = new ExampleAction('checkout');
$platform = new ExamplePlatform('api');

echo "1. First attempt (Action count: 1/1) - Should Pass\n";
$rateLimiter->attempt($key, $action, $platform);
echo "   Success.\n\n";

echo "2. Second attempt (Action count: 2/1) - Should Fail\n";
try {
    $rateLimiter->attempt($key, $action, $platform);
} catch (TooManyRequestsException $e) {
    echo "   Caught TooManyRequestsException!\n";

    $status = $e->status;
    if ($status) {
        echo "   Source: {$status->source}\n"; // Expected: 'action'
        echo "   Blocked: " . ($status->blocked ? 'Yes' : 'No') . "\n";
    }
}
