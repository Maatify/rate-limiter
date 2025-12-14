<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 06:16
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Tests\Adapters;

use PHPUnit\Framework\TestCase;
use Maatify\DataFakes\Adapters\Mongo\FakeMongoAdapter;
use Maatify\DataFakes\Storage\FakeStorageLayer;

final class FakeMongoAdapterTest extends TestCase
{
    private FakeMongoAdapter $mongo;

    protected function setUp(): void
    {
        $this->mongo = new FakeMongoAdapter(new FakeStorageLayer());
        $this->mongo->connect();
    }

    public function testInsertOne(): void
    {
        $doc = $this->mongo->insertOne('users', ['name' => 'Mohamed']);

        $this->assertArrayHasKey('_id', $doc);
        $this->assertSame('Mohamed', $doc['name']);
    }

    public function testInsertMany(): void
    {
        $docs = [
            ['name' => 'A'],
            ['name' => 'B'],
        ];

        $inserted = $this->mongo->insertMany('users', $docs);

        $this->assertCount(2, $inserted);
    }

    public function testFindOne(): void
    {
        $this->mongo->insertOne('users', ['name' => 'Ali']);
        $this->mongo->insertOne('users', ['name' => 'Hassan']);

        $row = $this->mongo->findOne('users', ['name' => 'Hassan']);

        $this->assertNotNull($row);
        $this->assertSame('Hassan', $row['name']);
    }

    public function testFindWithOperators(): void
    {
        $this->mongo->insertOne('products', ['price' => 100]);
        $this->mongo->insertOne('products', ['price' => 150]);

        $rows = $this->mongo->find('products', [
            'price' => ['$gt' => 120]
        ]);

        $this->assertCount(1, $rows);
        $this->assertSame(150, $rows[0]['price']);
    }

    public function testUpdateOne(): void
    {
        $this->mongo->insertOne('users', ['name' => 'Old']);
        $updated = $this->mongo->updateOne('users', ['name' => 'Old'], ['name' => 'New']);

        $this->assertSame(1, $updated);

        $row = $this->mongo->findOne('users', ['name' => 'New']);
        $this->assertNotNull($row);
        $this->assertSame('New', $row['name']);
    }

    public function testDeleteOne(): void
    {
        $this->mongo->insertOne('users', ['name' => 'ToDelete']);
        $deleted = $this->mongo->deleteOne('users', ['name' => 'ToDelete']);

        $this->assertSame(1, $deleted);

        $row = $this->mongo->findOne('users', ['name' => 'ToDelete']);
        $this->assertNull($row);
    }

    public function testLifecycle(): void
    {
        $this->mongo->disconnect();
        $this->assertFalse($this->mongo->isConnected());

        $this->mongo->connect();
        $this->assertTrue($this->mongo->isConnected());
        $this->assertTrue($this->mongo->healthCheck());
    }
}
