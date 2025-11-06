<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 00:27
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use PHPUnit\Framework\TestCase;

final class CoreStructureTest extends TestCase
{
    public function testEnumsAndDTO(): void
    {
        $status = new RateLimitStatusDTO(10, 5, 60);
        $this->assertSame(5, $status->remaining);
        $this->assertTrue(RateLimitActionEnum::LOGIN instanceof RateLimitActionEnum);
        $this->assertTrue(PlatformEnum::WEB instanceof PlatformEnum);
    }

    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(RateLimiterInterface::class));
    }
}