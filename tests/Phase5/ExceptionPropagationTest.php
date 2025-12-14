<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Phase5;

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use PHPUnit\Framework\TestCase;

final class ExceptionPropagationTest extends TestCase
{
    public function testExceptionCarriesDTO(): void
    {
        $dto = new RateLimitStatusDTO(10, 0, 60);
        $exception = new TooManyRequestsException('Error', 429, $dto);

        $this->assertSame($dto, $exception->status);
    }

    public function testExceptionExposesRetryAfter(): void
    {
        $dto = new RateLimitStatusDTO(10, 0, 60, 30);
        $exception = new TooManyRequestsException('Error', 429, $dto);

        $this->assertSame(30, $exception->getRetryAfter());
    }

    public function testExceptionExposesNextAllowedAt(): void
    {
        $timestamp = '2025-01-01 12:00:00';
        $dto = new RateLimitStatusDTO(10, 0, 60, 30, true, null, $timestamp);
        $exception = new TooManyRequestsException('Error', 429, $dto);

        $this->assertSame($timestamp, $exception->getNextAllowedAt());
    }
}
