<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Enums;

use Maatify\RateLimiter\Enums\PlatformEnum;
use PHPUnit\Framework\TestCase;

final class PlatformEnumTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertSame('web', PlatformEnum::WEB->value);
        $this->assertSame('mobile', PlatformEnum::MOBILE->value);
        $this->assertSame('api', PlatformEnum::API->value);
        $this->assertSame('admin', PlatformEnum::ADMIN->value);
    }

    public function testValueMethod(): void
    {
        $this->assertSame('web', PlatformEnum::WEB->value());
        $this->assertSame('mobile', PlatformEnum::MOBILE->value());
        $this->assertSame('api', PlatformEnum::API->value());
        $this->assertSame('admin', PlatformEnum::ADMIN->value());
    }
}
