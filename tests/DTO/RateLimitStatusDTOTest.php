<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\DTO;

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use PHPUnit\Framework\TestCase;

final class RateLimitStatusDTOTest extends TestCase
{
    public function testConstructorAndToArray(): void
    {
        $dto = new RateLimitStatusDTO(
            limit: 10,
            remaining: 5,
            resetAfter: 60,
            retryAfter: 30,
            blocked: true,
            backoffSeconds: 15,
            nextAllowedAt: '2025-01-01 12:00:00'
        );

        $expected = [
            'limit' => 10,
            'remaining' => 5,
            'reset_after' => 60,
            'retry_after' => 30,
            'blocked' => true,
            'backoff_seconds' => 15,
            'next_allowed_at' => '2025-01-01 12:00:00',
            'source' => null,
        ];

        $this->assertSame($expected, $dto->toArray());
    }

    public function testNullableFields(): void
    {
        $dto = new RateLimitStatusDTO(
            limit: 10,
            remaining: 9,
            resetAfter: 60
        );

        $expected = [
            'limit' => 10,
            'remaining' => 9,
            'reset_after' => 60,
            'retry_after' => null,
            'blocked' => false,
            'backoff_seconds' => null,
            'next_allowed_at' => null,
            'source' => null,
        ];

        $this->assertSame($expected, $dto->toArray());
    }
}
