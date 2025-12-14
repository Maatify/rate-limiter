<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-26 11:11
 * @see         https://www.maatify.dev Maatify.dev
 * @link        https://github.com/Maatify/common view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Contracts\Adapter;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class AdapterInterfaceSignatureTest extends TestCase
{
    private ReflectionClass $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ref = new ReflectionClass(AdapterInterface::class);
    }

    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(AdapterInterface::class));
        $this->assertTrue($this->ref->isInterface(), 'AdapterInterface must be an interface');
    }

    public function testRequiredMethodsExist(): void
    {
        $expected = [
            'connect',
            'isConnected',
            'getConnection',
            'healthCheck',
            'disconnect',
            'getDriver',
        ];

        foreach ($expected as $method) {
            $this->assertTrue(
                $this->ref->hasMethod($method),
                "Expected method '$method' to exist in AdapterInterface"
            );
        }
    }

    public function testConnectSignature(): void
    {
        $method = $this->ref->getMethod('connect');

        $this->assertSame([], $this->paramNames($method));
        $this->assertSame('void', (string)$method->getReturnType());
    }

    public function testIsConnectedSignature(): void
    {
        $method = $this->ref->getMethod('isConnected');

        $this->assertSame([], $this->paramNames($method));
        $this->assertSame('bool', (string)$method->getReturnType());
    }

    public function testGetConnectionSignature(): void
    {
        $method = $this->ref->getMethod('getConnection');

        $this->assertSame([], $this->paramNames($method));
        $this->assertSame('mixed', (string)$method->getReturnType());
    }

    public function testHealthCheckSignature(): void
    {
        $method = $this->ref->getMethod('healthCheck');

        $this->assertSame([], $this->paramNames($method));
        $this->assertSame('bool', (string)$method->getReturnType());
    }

    public function testDisconnectSignature(): void
    {
        $method = $this->ref->getMethod('disconnect');

        $this->assertSame([], $this->paramNames($method));
        $this->assertSame('void', (string)$method->getReturnType());
    }

    public function testGetDriverSignature(): void
    {
        $method = $this->ref->getMethod('getDriver');

        $this->assertSame([], $this->paramNames($method));
        $this->assertSame('mixed', (string)$method->getReturnType());
    }

    /**
     * @return array<int,string>
     */
    private function paramNames(ReflectionMethod $method): array
    {
        return array_map(fn ($p) => $p->getName(), $method->getParameters());
    }
}
