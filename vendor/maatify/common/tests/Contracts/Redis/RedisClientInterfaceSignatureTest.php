<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-26 11:09
 * @see         https://www.maatify.dev Maatify.dev
 * @link        https://github.com/Maatify/common view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Contracts\Redis;

use Maatify\Common\Contracts\Redis\RedisClientInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class RedisClientInterfaceSignatureTest extends TestCase
{
    private ReflectionClass $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ref = new ReflectionClass(RedisClientInterface::class);
    }

    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(RedisClientInterface::class));
        $this->assertTrue($this->ref->isInterface());
    }

    public function testRequiredMethodsExist(): void
    {
        $methods = [
            'get',
            'set',
            'del',
            'keys',
        ];

        foreach ($methods as $method) {
            $this->assertTrue(
                $this->ref->hasMethod($method),
                "Expected method '$method' to exist in RedisClientInterface"
            );
        }
    }

    public function testGetSignature(): void
    {
        $method = $this->ref->getMethod('get');

        $this->assertSame(['key'], $this->getParamNames($method));
        $this->assertSame('string|false|null', (string)$method->getReturnType());
    }

    public function testSetSignature(): void
    {
        $method = $this->ref->getMethod('set');

        $this->assertSame(['key', 'value'], $this->getParamNames($method));
        $this->assertSame('bool', (string)$method->getReturnType());
    }

    public function testDelSignature(): void
    {
        $method = $this->ref->getMethod('del');

        $this->assertSame(['keys'], $this->getParamNames($method));
        $this->assertSame('int', (string)$method->getReturnType());
        $this->assertTrue($method->isVariadic());
    }

    public function testKeysSignature(): void
    {
        $method = $this->ref->getMethod('keys');

        $this->assertSame(['pattern'], $this->getParamNames($method));
        $this->assertSame('array', (string)$method->getReturnType());
    }

    /**
     * @return array<int,string>
     */
    private function getParamNames(ReflectionMethod $method): array
    {
        return array_map(fn ($p) => $p->getName(), $method->getParameters());
    }
}
