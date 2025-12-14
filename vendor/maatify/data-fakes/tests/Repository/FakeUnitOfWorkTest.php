<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-22 03:01
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Tests\Repository;

use Maatify\DataFakes\Adapters\MySQL\FakeMySQLAdapter;
use Maatify\DataFakes\Repository\FakeUnitOfWork;
use Maatify\DataFakes\Storage\FakeStorageLayer;
use PHPUnit\Framework\TestCase;

final class FakeUnitOfWorkTest extends TestCase
{
    private FakeStorageLayer $storage;
    private FakeMySQLAdapter $adapter;
    private FakeUnitOfWork $unitOfWork;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage    = new FakeStorageLayer();
        $this->adapter    = new FakeMySQLAdapter($this->storage);
        $this->unitOfWork = new FakeUnitOfWork($this->storage);
        $this->adapter->connect();
    }

    public function testCommitPersistsChanges(): void
    {
        $this->unitOfWork->begin();
        $this->adapter->insert('users', ['name' => 'persisted']);
        $this->unitOfWork->commit();

        $rows = $this->storage->listAll('users');
        self::assertCount(1, $rows);
        self::assertSame('persisted', $rows[0]['name']);
    }

    public function testRollbackRestoresSnapshot(): void
    {
        $this->adapter->insert('users', ['name' => 'existing']);

        $this->unitOfWork->begin();
        $this->adapter->insert('users', ['name' => 'temporary']);
        $this->unitOfWork->rollback();

        $rows = $this->storage->listAll('users');
        self::assertCount(1, $rows);
        self::assertSame('existing', $rows[0]['name']);
    }

    public function testNestedTransactionsRestoreInner(): void
    {
        $this->unitOfWork->begin();
        $this->adapter->insert('users', ['name' => 'outer']);

        $this->unitOfWork->begin();
        $this->adapter->insert('users', ['name' => 'inner']);
        $this->unitOfWork->rollback();

        $rowsAfterInner = $this->storage->listAll('users');
        self::assertCount(1, $rowsAfterInner);
        self::assertSame('outer', $rowsAfterInner[0]['name']);

        $this->unitOfWork->commit();

        $finalRows = $this->storage->listAll('users');
        self::assertCount(1, $finalRows);
        self::assertSame('outer', $finalRows[0]['name']);
    }

    public function testTransactionalHelperCommitsOnSuccess(): void
    {
        $result = $this->unitOfWork->transactional(function (): string {
            $this->adapter->insert('users', ['name' => 'via helper']);

            return 'ok';
        });

        self::assertSame('ok', $result);
        self::assertCount(1, $this->storage->listAll('users'));
    }

    public function testTransactionalHelperRollsBackOnException(): void
    {
        $this->expectException(\RuntimeException::class);

        try {
            $this->unitOfWork->transactional(function (): void {
                $this->adapter->insert('users', ['name' => 'will revert']);

                throw new \RuntimeException('force failure');
            });
        } finally {
            self::assertSame([], $this->storage->listAll('users'));
        }
    }
}
