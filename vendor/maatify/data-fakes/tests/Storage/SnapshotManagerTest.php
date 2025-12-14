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

namespace Maatify\DataFakes\Tests\Storage;

use Maatify\DataFakes\Storage\FakeStorageLayer;
use Maatify\DataFakes\Storage\Snapshots\SnapshotManager;
use PHPUnit\Framework\TestCase;

final class SnapshotManagerTest extends TestCase
{
    public function testSnapshotRoundTrip(): void
    {
        $storage  = new FakeStorageLayer();
        $manager  = new SnapshotManager($storage);

        $storage->write('users', ['name' => 'before']);
        $snapshot = $manager->createSnapshot();

        $storage->write('users', ['name' => 'after']);
        $storage->write('orders', ['amount' => 10]);

        $manager->restoreSnapshot($snapshot);

        $users = $storage->listAll('users');
        self::assertCount(1, $users);
        self::assertSame('before', $users[0]['name']);

        $orders = $storage->listAll('orders');
        self::assertSame([], $orders);
    }
}
