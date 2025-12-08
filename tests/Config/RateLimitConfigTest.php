<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Config;

use Maatify\RateLimiter\Config\RateLimitConfig;
use PHPUnit\Framework\TestCase;

final class RateLimitConfigTest extends TestCase
{
    public function testGetReturnsConfigForKnownActions(): void
    {
        $loginConfig = RateLimitConfig::get('login');
        $this->assertSame(5, $loginConfig['limit']);
        $this->assertSame(60, $loginConfig['interval']);
        $this->assertSame(600, $loginConfig['ban_time']);

        $otpConfig = RateLimitConfig::get('otp_request');
        $this->assertSame(3, $otpConfig['limit']);
        $this->assertSame(120, $otpConfig['interval']);
        $this->assertSame(900, $otpConfig['ban_time']);

        $pwdResetConfig = RateLimitConfig::get('password_reset');
        $this->assertSame(2, $pwdResetConfig['limit']);
        $this->assertSame(300, $pwdResetConfig['interval']);
        $this->assertSame(1200, $pwdResetConfig['ban_time']);
    }

    public function testGetReturnsDefaultsForUnknownAction(): void
    {
        $defaultConfig = RateLimitConfig::get('unknown_action');
        $this->assertSame(5, $defaultConfig['limit']);
        $this->assertSame(60, $defaultConfig['interval']);
        $this->assertSame(300, $defaultConfig['ban_time']);
    }
}
