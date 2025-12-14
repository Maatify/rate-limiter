<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Phase5;

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Resolver\ExponentialBackoffPolicy;
use PHPUnit\Framework\TestCase;

final class BackoffPolicyTest extends TestCase
{
    public function testBackoffGrowsExponentially(): void
    {
        $policy = new ExponentialBackoffPolicy(base: 2);

        // Limit 10, remaining -1 -> over 1 -> 2^1 = 2
        $status1 = new RateLimitStatusDTO(10, -1, 60);
        $this->assertSame(2, $policy->calculateDelay($status1));

        // Limit 10, remaining -2 -> over 2 -> 2^2 = 4
        $status2 = new RateLimitStatusDTO(10, -2, 60);
        $this->assertSame(4, $policy->calculateDelay($status2));

        // Limit 10, remaining -3 -> over 3 -> 2^3 = 8
        $status3 = new RateLimitStatusDTO(10, -3, 60);
        $this->assertSame(8, $policy->calculateDelay($status3));
    }

    public function testBackoffCappedByResetWindow(): void
    {
        $policy = new ExponentialBackoffPolicy(base: 2);

        // Limit 10, remaining -10 -> over 10 -> 2^10 = 1024
        // Reset after 60
        // Should be capped at 60
        $status = new RateLimitStatusDTO(10, -10, 60);
        $this->assertSame(60, $policy->calculateDelay($status));
    }

    public function testBackoffCappedByMaxDelay(): void
    {
        $policy = new ExponentialBackoffPolicy(base: 2, maxDelay: 100);

        // Limit 10, remaining -10 -> over 10 -> 2^10 = 1024
        // Reset after 2000
        // Should be capped at 100
        $status = new RateLimitStatusDTO(10, -10, 2000);
        $this->assertSame(100, $policy->calculateDelay($status));
    }

    public function testZeroDelayIfNotOverLimit(): void
    {
        $policy = new ExponentialBackoffPolicy(base: 2);

        // Remaining 0 -> over 0 -> delay 0
        $status = new RateLimitStatusDTO(10, 0, 60);
        $this->assertSame(0, $policy->calculateDelay($status));

        // Remaining 5 -> over 0 -> delay 0
        $status2 = new RateLimitStatusDTO(10, 5, 60);
        $this->assertSame(0, $policy->calculateDelay($status2));
    }
}
