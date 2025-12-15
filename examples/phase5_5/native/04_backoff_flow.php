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
// Strict limit of 1
$driver->setLimit('user_04:search:api', 1);

$resolver = new RateLimiterResolver(['memory' => $driver], 'memory');
$rateLimiter = $resolver->resolve();

$key = 'user_04';
$action = new ExampleAction('search');
$platform = new ExamplePlatform('api');

// 1. First attempt (Pass)
$rateLimiter->attempt($key, $action, $platform);

// 2. Second attempt (Fail)
try {
    $rateLimiter->attempt($key, $action, $platform);
} catch (TooManyRequestsException $e) {
    echo "First Block:\n";
    echo "  Retry After: {$e->status->retryAfter}s\n";
    echo "  Backoff: {$e->status->backoffSeconds}s\n";
}

// 3. Third attempt (Fail with exponential backoff)
// Note: In a real scenario, time would pass. Here we just trigger another failure.
// The default backoff policy increases delay based on attempts over limit.
try {
    $rateLimiter->attempt($key, $action, $platform);
} catch (TooManyRequestsException $e) {
    echo "Second Block:\n";
    echo "  Retry After: {$e->status->retryAfter}s\n";
    echo "  Backoff: {$e->status->backoffSeconds}s\n";
}
