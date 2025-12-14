<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-26 11:24
 * @see         https://www.maatify.dev Maatify.dev
 * @link        https://github.com/Maatify/common view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Contracts\Lock;

use Maatify\Common\Lock\LockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class LockInterfaceSignatureTest extends TestCase
{
    public function testInterfaceExists(): void
    {
        $this->assertTrue(
            interface_exists(LockInterface::class),
            'LockInterface does not exist'
        );
    }

    public function testMethodsExist(): void
    {
        $ref = new ReflectionClass(LockInterface::class);

        $this->assertTrue($ref->hasMethod('acquire'), 'Method acquire() missing');
        $this->assertTrue($ref->hasMethod('isLocked'), 'Method isLocked() missing');
        $this->assertTrue($ref->hasMethod('release'), 'Method release() missing');
    }

    public function testAcquireSignature(): void
    {
        $ref = new ReflectionMethod(LockInterface::class, 'acquire');

        // no parameters
        $this->assertCount(0, $ref->getParameters());

        // return type: bool
        $this->assertSame('bool', (string)$ref->getReturnType());
    }

    public function testIsLockedSignature(): void
    {
        $ref = new ReflectionMethod(LockInterface::class, 'isLocked');

        // no parameters
        $this->assertCount(0, $ref->getParameters());

        // return type: bool
        $this->assertSame('bool', (string)$ref->getReturnType());
    }

    public function testReleaseSignature(): void
    {
        $ref = new ReflectionMethod(LockInterface::class, 'release');

        // no parameters
        $this->assertCount(0, $ref->getParameters());

        // return type: void
        $this->assertSame('void', (string)$ref->getReturnType());
    }
}
