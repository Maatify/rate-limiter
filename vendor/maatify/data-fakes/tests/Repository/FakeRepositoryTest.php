<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:01
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Tests\Repository;

use Maatify\DataFakes\Repository\FakeRepository;
use Maatify\DataFakes\Repository\Hydration\ArrayHydrator;
use Maatify\DataFakes\Storage\FakeStorageLayer;
use PHPUnit\Framework\TestCase;

class FakeRepositoryTest extends TestCase
{
    private FakeStorageLayer $storage;
    private FakeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage     = new FakeStorageLayer();
        $this->repository  = new FakeRepository($this->storage, 'users');
    }

    public function testInsertAndFindOne(): void
    {
        $id = $this->repository->insert(['name' => 'Alice']);
        self::assertSame(1, $id);

        $found = $this->repository->find($id);
        self::assertNotNull($found);
        self::assertSame('Alice', $found['name']);
        self::assertSame($id, $found['id']);
    }

    public function testFindReturnsCollection(): void
    {
        $this->repository->insert(['name' => 'Alice']);
        $this->repository->insert(['name' => 'Bob']);

        $results = $this->repository->findBy(['name' => ['Alice', 'Bob']]);
        self::assertCount(2, $results);
        self::assertSame('Alice', $results[0]['name']);
    }

    public function testUpdateWithFilters(): void
    {
        $aliceId = $this->repository->insert(['name' => 'Alice']);
        $this->repository->insert(['name' => 'Bob']);

        $updated = $this->repository->update($aliceId, ['name' => 'Alicia']);
        self::assertTrue($updated);

        $updatedRow = $this->repository->find($aliceId);
        self::assertNotNull($updatedRow);
        self::assertSame('Alicia', $updatedRow['name']);
    }

    public function testDelete(): void
    {
        $aliceId = $this->repository->insert(['name' => 'Alice']);
        $this->repository->insert(['name' => 'Bob']);

        $deleted = $this->repository->delete($aliceId);
        self::assertTrue($deleted);

        $remaining = $this->repository->findAll();
        self::assertCount(1, $remaining);
        self::assertSame('Bob', $remaining[0]['name']);
    }

    public function testHydration(): void
    {
        $hydrator = new ArrayHydrator();
        $repository = new FakeRepository($this->storage, 'users', $hydrator, FakeUser::class);

        $repository->insert(['name' => 'Hydrated']);
        $collection = $repository->findCollection();

        $first = $collection[0];
        self::assertInstanceOf(FakeUser::class, $first);
        self::assertSame('Hydrated', $first->name);
    }
}

class FakeUser
{
    public string $name = '';
}
