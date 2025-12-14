<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;

// 1. Define Dummy Classes
$action = new class implements RateLimitActionInterface {
    public function value(): string
    {
        return 'upload';
    }
};

$platform = new class implements PlatformInterface {
    public function value(): string
    {
        return 'api';
    }
};

// 2. Define a Dummy Driver that passes Global but fails Action
$driver = new class implements RateLimiterInterface {
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // Pass Global
        if ($action->value() === 'global') {
            return new RateLimitStatusDTO(1000, 999, 3600);
        }

        // Fail Specific Action
        if ($action->value() === 'upload') {
            throw new TooManyRequestsException('Action limit exceeded', 429);
        }

        return new RateLimitStatusDTO(10, 9, 60);
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        return true;
    }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        return new RateLimitStatusDTO(5, 0, 60);
    }
};

// 3. Setup Resolver
$resolver = new RateLimiterResolver(['memory' => $driver], 'memory');

// 4. Resolve and Attempt
$limiter = $resolver->resolve();

try {
    echo "Attempting upload action...\n";
    $limiter->attempt('user_456', $action, $platform);
} catch (TooManyRequestsException $e) {
    echo "Caught expected exception: " . $e->getMessage() . "\n";

    $status = $e->getStatus();
    if ($status) {
        echo "Source of block: " . $status->source . "\n"; // Expected: action
    }
}
