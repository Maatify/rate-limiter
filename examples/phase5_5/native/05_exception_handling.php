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
$driver->setLimit('user_05:api:web', 0); // Always fail

$resolver = new RateLimiterResolver(['memory' => $driver], 'memory');
$rateLimiter = $resolver->resolve();

try {
    $rateLimiter->attempt('user_05', new ExampleAction('api'), new ExamplePlatform('web'));
} catch (TooManyRequestsException $e) {
    echo "Exception Caught: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";

    $status = $e->status;
    if ($status) {
        echo "DTO Status:\n";
        echo " - Remaining: {$status->remaining}\n";
        echo " - Reset After: {$status->resetAfter}\n";
        echo " - Retry After: {$status->retryAfter}\n";
        echo " - Blocked: " . ($status->blocked ? 'Yes' : 'No') . "\n";
        echo " - Source: {$status->source}\n";
        echo " - Next Allowed: {$status->nextAllowedAt}\n";
    }
}
