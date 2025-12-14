<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:24
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Tests\Adapters;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Maatify\DataFakes\Adapters\MySQL\FakeMySQLAdapter;
use Maatify\DataFakes\Adapters\MySQL\FakeMySQLDbalAdapter;
use Maatify\DataFakes\Storage\FakeStorageLayer;

#[CoversClass(FakeMySQLDbalAdapter::class)]
class FakeMySQLDbalAdapterTest extends TestCase
{
    private FakeMySQLDbalAdapter $dbal;
    private FakeMySQLAdapter $mysql;
    private FakeStorageLayer $store;

    protected function setUp(): void
    {
        $this->store = new FakeStorageLayer();
        $this->mysql = new FakeMySQLAdapter($this->store);
        $this->dbal  = new FakeMySQLDbalAdapter($this->mysql);

        // Seed test table
        $this->store->write('products', ['id' => 1, 'name' => 'Phone', 'price' => 100]);
        $this->store->write('products', ['id' => 2, 'name' => 'Laptop', 'price' => 500]);

        $this->dbal->connect();
    }

    // ------------------------------------------------------------
    //  Lifecycle Delegation Tests
    // ------------------------------------------------------------

    public function testConnectionDelegation(): void
    {
        $this->assertTrue($this->dbal->isConnected());
        $this->assertTrue($this->dbal->healthCheck());

        $this->dbal->disconnect();
        $this->assertFalse($this->dbal->isConnected());
    }

    public function testGetConnectionDelegation(): void
    {
        $conn = $this->dbal->getConnection();
        $this->assertInstanceOf(FakeStorageLayer::class, $conn);
    }

    public function testGetDriverDelegation(): void
    {
        $driver = $this->dbal->getDriver();
        $this->assertInstanceOf(FakeStorageLayer::class, $driver);
    }

    // ------------------------------------------------------------
    //  DBAL API Tests
    // ------------------------------------------------------------

    public function testFetchAll(): void
    {
        $rows = $this->dbal->fetchAll('products');
        $this->assertCount(2, $rows);
    }

    public function testFetchOne(): void
    {
        $row = $this->dbal->fetchOne('products', ['id' => 1]);
        $this->assertNotNull($row);
        $this->assertArrayHasKey('name', $row);
        $this->assertSame('Phone', $row['name']);

    }

    public function testInsert(): void
    {
        $new = $this->dbal->insert('products', [
            'id' => 3,
            'name' => 'Tablet',
            'price' => 200,
        ]);

        $this->assertSame(3, $new['id']);

        $rows = $this->dbal->fetchAll('products');
        $this->assertCount(3, $rows);
    }
}
