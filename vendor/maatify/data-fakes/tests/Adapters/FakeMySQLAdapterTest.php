<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:23
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Tests\Adapters;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Maatify\DataFakes\Adapters\MySQL\FakeMySQLAdapter;
use Maatify\DataFakes\Storage\FakeStorageLayer;

#[CoversClass(FakeMySQLAdapter::class)]
class FakeMySQLAdapterTest extends TestCase
{
    private FakeMySQLAdapter $adapter;
    private FakeStorageLayer $store;

    protected function setUp(): void
    {
        $this->store = new FakeStorageLayer();

        // Seed data
        $this->store->write('users', ['id' => 1, 'name' => 'Alice', 'age' => 20]);
        $this->store->write('users', ['id' => 2, 'name' => 'Bob', 'age' => 25]);
        $this->store->write('users', ['id' => 3, 'name' => 'Charlie', 'age' => 30]);

        $this->adapter = new FakeMySQLAdapter($this->store);
        $this->adapter->connect();
    }

    // ------------------------------------------------------------
    //  Lifecycle Tests
    // ------------------------------------------------------------

    public function testConnectionLifecycle(): void
    {
        $this->assertTrue($this->adapter->isConnected());
        $this->assertTrue($this->adapter->healthCheck());

        $this->adapter->disconnect();
        $this->assertFalse($this->adapter->isConnected());
        $this->assertFalse($this->adapter->healthCheck());
    }

    public function testGetConnection(): void
    {
        $conn = $this->adapter->getConnection();
        $this->assertInstanceOf(FakeStorageLayer::class, $conn);
    }

    public function testGetDriver(): void
    {
        $driver = $this->adapter->getDriver();
        $this->assertInstanceOf(FakeStorageLayer::class, $driver);
    }

    // ------------------------------------------------------------
    //  CRUD Tests
    // ------------------------------------------------------------

    public function testSelectAll(): void
    {
        $rows = $this->adapter->select('users');
        $this->assertCount(3, $rows);
    }

    public function testSelectWithFilter(): void
    {
        $rows = $this->adapter->select('users', ['age' => 25]);
        $this->assertCount(1, $rows);
        $this->assertSame('Bob', $rows[0]['name']);
    }

    public function testInsert(): void
    {
        $this->adapter->insert('users', [
            'id' => 4,
            'name' => 'David',
            'age' => 40,
        ]);

        $rows = $this->adapter->select('users');
        $this->assertCount(4, $rows);
    }

    public function testUpdate(): void
    {
        $count = $this->adapter->update('users', ['name' => 'Alice'], ['age' => 21]);
        $this->assertEquals(1, $count);

        $updated = $this->adapter->select('users', ['id' => 1])[0];
        $this->assertEquals(21, $updated['age']);
    }

    public function testDelete(): void
    {
        $count = $this->adapter->delete('users', ['id' => 2]);
        $this->assertEquals(1, $count);

        $rows = $this->adapter->select('users');
        $this->assertCount(2, $rows);
    }

    // ------------------------------------------------------------
    //  Filtering & Ordering Tests
    // ------------------------------------------------------------

    public function testContainsFilter(): void
    {
        $rows = $this->adapter->select('users', ['name' => '%li%']);
        $this->assertCount(2, $rows);
    }

    public function testRegexFilter(): void
    {
        $rows = $this->adapter->select('users', ['name' => '/^A/']);
        $this->assertCount(1, $rows);
        $this->assertSame('Alice', $rows[0]['name']);
    }

    public function testOrderingAsc(): void
    {
        $rows = $this->adapter->select('users', [], ['orderBy' => 'age', 'order' => 'ASC']);
        $this->assertSame(20, $rows[0]['age']);
    }

    public function testOrderingDesc(): void
    {
        $rows = $this->adapter->select('users', [], ['orderBy' => 'age', 'order' => 'DESC']);
        $this->assertSame(30, $rows[0]['age']);
    }

    public function testLimit(): void
    {
        $rows = $this->adapter->select('users', [], ['limit' => 2]);
        $this->assertCount(2, $rows);
    }
}
