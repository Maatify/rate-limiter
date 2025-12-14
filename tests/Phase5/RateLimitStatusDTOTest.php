<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Phase5;

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use PHPUnit\Framework\TestCase;

final class RateLimitStatusDTOTest extends TestCase
{
    public function testDtoInvariants(): void
    {
        $dto = new RateLimitStatusDTO(
            limit: 10,
            remaining: -1,
            resetAfter: 60,
            retryAfter: 30,
            blocked: true,
            backoffSeconds: 30,
            nextAllowedAt: '2025-01-01 12:00:00',
            source: 'global'
        );

        $this->assertSame(10, $dto->limit);
        $this->assertSame(-1, $dto->remaining);
        $this->assertSame(60, $dto->resetAfter);
        $this->assertSame(30, $dto->retryAfter);
        $this->assertTrue($dto->blocked);
        $this->assertSame(30, $dto->backoffSeconds);
        $this->assertSame('2025-01-01 12:00:00', $dto->nextAllowedAt);
        $this->assertSame('global', $dto->source);
    }

    public function testToArrayAndFromArray(): void
    {
        $dto = new RateLimitStatusDTO(
            limit: 10,
            remaining: 5,
            resetAfter: 60,
            retryAfter: null,
            blocked: false,
            backoffSeconds: null,
            nextAllowedAt: null,
            source: 'action'
        );

        $array = $dto->toArray();

        $this->assertSame(10, $array['limit']);
        $this->assertSame(5, $array['remaining']);
        $this->assertSame(60, $array['reset_after']);
        $this->assertNull($array['retry_after']);
        $this->assertFalse($array['blocked']);
        $this->assertSame('action', $array['source']);

        $newDto = RateLimitStatusDTO::fromArray($array);

        $this->assertEquals($dto, $newDto);
    }
}
