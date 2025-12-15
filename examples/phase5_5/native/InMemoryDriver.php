<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Examples\Phase5_5;

use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

final class ExampleAction implements RateLimitActionInterface
{
    public function __construct(private string $name = 'test_action')
    {
    }

    public function value(): string
    {
        return $this->name;
    }
}

final class ExamplePlatform implements PlatformInterface
{
    public function __construct(private string $name = 'test_platform')
    {
    }

    public function value(): string
    {
        return $this->name;
    }
}

/**
 * A dummy in-memory driver for demonstration purposes.
 * It mimics the behavior of a real storage driver (Redis/MySQL).
 */
final class InMemoryDriver implements RateLimiterInterface
{
    /** @var array<string, int> */
    private array $counts = [];

    /** @var array<string, int> */
    private array $limits = [];

    public function setLimit(string $key, int $limit): void
    {
        $this->limits[$key] = $limit;
    }

    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $storageKey = $this->buildKey($key, $action, $platform);
        $limit = $this->limits[$storageKey] ?? 10; // Default limit 10
        $current = ($this->counts[$storageKey] ?? 0) + 1;
        $this->counts[$storageKey] = $current;

        $remaining = $limit - $current;
        $dto = new RateLimitStatusDTO($limit, $remaining, 60);

        if ($current > $limit) {
            throw new TooManyRequestsException('Rate limit exceeded', 429, $dto);
        }

        return $dto;
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        $storageKey = $this->buildKey($key, $action, $platform);
        unset($this->counts[$storageKey]);

        return true;
    }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $storageKey = $this->buildKey($key, $action, $platform);
        $limit = $this->limits[$storageKey] ?? 10;
        $current = $this->counts[$storageKey] ?? 0;
        $remaining = $limit - $current;

        return new RateLimitStatusDTO($limit, $remaining, 60);
    }

    private function buildKey(string $key, RateLimitActionInterface $action, PlatformInterface $platform): string
    {
        // Simple composite key simulation
        return sprintf('%s:%s:%s', $key, $action->value(), $platform->value());
    }
}
