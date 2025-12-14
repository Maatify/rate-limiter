<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:03
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Tests\Storage;

use Maatify\DataFakes\Storage\FakeStorageLayer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Maatify\DataFakes\Storage\FakeStorageLayer::class)]
class FakeStorageLayerTest extends TestCase
{
    public function test_write_and_read(): void
    {
        $layer = new FakeStorageLayer();

        $row1 = $layer->write('users', ['name' => 'Alice']);
        $row2 = $layer->write('users', ['name' => 'Bob']);

        $this->assertSame(1, $row1['id']);
        $this->assertSame(2, $row2['id']);

        $rows = $layer->read('users');

        $this->assertCount(2, $rows);
        $this->assertSame('Alice', $rows[1]['name']);
        $this->assertSame('Bob', $rows[2]['name']);
    }

    public function test_writeTable(): void
    {
        $layer = new FakeStorageLayer();

        $layer->writeTable('products', [
            10 => ['id' => 10, 'name' => 'Phone'],
            11 => ['id' => 11, 'name' => 'Laptop']
        ]);

        $rows = $layer->read('products');

        $this->assertCount(2, $rows);
        $this->assertSame('Phone', $rows[10]['name']);

        // Auto-increment must be updated to 12
        $new = $layer->write('products', ['name' => 'Tablet']);
        $this->assertSame(12, $new['id']);
    }

    public function test_reset(): void
    {
        $layer = new FakeStorageLayer();

        $layer->write('users', ['name' => 'Alice']);
        $layer->write('users', ['name' => 'Bob']);

        $this->assertCount(2, $layer->read('users'));

        $layer->reset();

        $this->assertSame([], $layer->read('users'));
    }

    public function test_drop(): void
    {
        $layer = new FakeStorageLayer();
        $layer->write('logs', ['msg' => 'ok']);

        $this->assertCount(1, $layer->read('logs'));

        $layer->drop('logs');

        $this->assertSame([], $layer->read('logs'));
    }
}
