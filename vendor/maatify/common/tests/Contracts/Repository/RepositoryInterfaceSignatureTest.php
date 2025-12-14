<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-26 11:12
 * @see         https://www.maatify.dev Maatify.dev
 * @link        https://github.com/Maatify/common view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Contracts\Repository;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\Common\Contracts\Repository\RepositoryInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;

class RepositoryInterfaceSignatureTest extends TestCase
{
    private ReflectionClass $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ref = new ReflectionClass(RepositoryInterface::class);
    }

    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(RepositoryInterface::class));
        $this->assertTrue($this->ref->isInterface());
    }

    public function testRequiredMethodsExist(): void
    {
        $expected = [
            'find',
            'findBy',
            'findAll',
            'insert',
            'update',
            'delete',
            'setAdapter',
        ];

        foreach ($expected as $method) {
            $this->assertTrue(
                $this->ref->hasMethod($method),
                "Expected method {$method} to exist"
            );
        }
    }

    public function testFindSignature(): void
    {
        $method = $this->ref->getMethod('find');

        $this->assertSame(['id'], $this->paramNames($method));
        $this->assertSame('?array', (string) $method->getReturnType());

        $types = $this->unionTypeNames($method->getParameters()[0]->getType());
        $this->assertSame(['int', 'string'], $types);
    }

    public function testFindBySignature(): void
    {
        $method = $this->ref->getMethod('findBy');

        $this->assertSame(['filters'], $this->paramNames($method));
        $this->assertSame('array', (string) $method->getReturnType());
    }

    public function testFindAllSignature(): void
    {
        $method = $this->ref->getMethod('findAll');

        $this->assertSame([], $this->paramNames($method));
        $this->assertSame('array', (string) $method->getReturnType());
    }

    public function testInsertSignature(): void
    {
        $method = $this->ref->getMethod('insert');

        $this->assertSame(['data'], $this->paramNames($method));
        $returnTypes = explode('|', (string) $method->getReturnType());
        sort($returnTypes);

        $this->assertSame(['int', 'string'], $returnTypes);

        $this->assertSame('array', (string) $method->getParameters()[0]->getType());
    }

    public function testUpdateSignature(): void
    {
        $method = $this->ref->getMethod('update');

        $this->assertSame(['id', 'data'], $this->paramNames($method));
        $this->assertSame('bool', (string) $method->getReturnType());

        [$id, $data] = $method->getParameters();

        $this->assertSame(
            ['int', 'string'],
            $this->unionTypeNames($id->getType())
        );
        $this->assertSame('array', (string) $data->getType());
    }

    public function testDeleteSignature(): void
    {
        $method = $this->ref->getMethod('delete');

        $this->assertSame(['id'], $this->paramNames($method));
        $this->assertSame('bool', (string) $method->getReturnType());

        $this->assertSame(
            ['int', 'string'],
            $this->unionTypeNames($method->getParameters()[0]->getType())
        );
    }

    public function testSetAdapterSignature(): void
    {
        $method = $this->ref->getMethod('setAdapter');

        $this->assertSame(['adapter'], $this->paramNames($method));
        $this->assertSame('static', (string) $method->getReturnType());

        $this->assertSame(
            AdapterInterface::class,
            $method->getParameters()[0]->getType()?->getName()
        );
    }

    /**
     * @return array<int,string>
     */
    private function paramNames(ReflectionMethod $method): array
    {
        return array_map(
            static fn(ReflectionParameter $p): string => $p->getName(),
            $method->getParameters()
        );
    }

    /**
     * @return array<int,string>
     */
    private function unionTypeNames(?ReflectionType $type): array
    {
        if ($type instanceof ReflectionUnionType) {
            $names = array_map(
                static fn(ReflectionType $t): string => $t->getName(),
                $type->getTypes()
            );
        } else {
            $names = [$type?->getName() ?? ''];
        }

        sort($names); // ← FIX
        return $names;
    }

}
