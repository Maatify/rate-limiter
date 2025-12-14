<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Config;

use Maatify\RateLimiter\Config\ActionRateLimitConfig;
use Maatify\RateLimiter\Config\GlobalRateLimitConfig;
use Maatify\RateLimiter\Config\InMemoryActionRateLimitConfigProvider;
use PHPUnit\Framework\TestCase;

final class RateLimitConfigTest extends TestCase
{
    public function testProviderReturnsConfigForKnownActions(): void
    {
        $provider = new InMemoryActionRateLimitConfigProvider(
            new GlobalRateLimitConfig(defaultLimit: 5, defaultInterval: 60, defaultBanTime: 300),
            [
                'login' => new ActionRateLimitConfig(5, 60, 600),
                'otp_request' => new ActionRateLimitConfig(3, 120, 900),
                'password_reset' => new ActionRateLimitConfig(2, 300, 1200),
            ]
        );

        $loginConfig = $provider->getForAction('login');
        $this->assertSame(5, $loginConfig->limit());
        $this->assertSame(60, $loginConfig->interval());
        $this->assertSame(600, $loginConfig->banTime());

        $otpConfig = $provider->getForAction('otp_request');
        $this->assertSame(3, $otpConfig->limit());
        $this->assertSame(120, $otpConfig->interval());
        $this->assertSame(900, $otpConfig->banTime());

        $pwdResetConfig = $provider->getForAction('password_reset');
        $this->assertSame(2, $pwdResetConfig->limit());
        $this->assertSame(300, $pwdResetConfig->interval());
        $this->assertSame(1200, $pwdResetConfig->banTime());
    }

    public function testProviderFallsBackToGlobalDefaults(): void
    {
        $provider = new InMemoryActionRateLimitConfigProvider(
            new GlobalRateLimitConfig(defaultLimit: 5, defaultInterval: 60, defaultBanTime: 300)
        );

        $defaultConfig = $provider->getForAction('unknown_action');
        $this->assertSame(5, $defaultConfig->limit());
        $this->assertSame(60, $defaultConfig->interval());
        $this->assertSame(300, $defaultConfig->banTime());
    }
}
