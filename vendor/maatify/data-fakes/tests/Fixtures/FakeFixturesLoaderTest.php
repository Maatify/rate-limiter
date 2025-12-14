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

namespace Maatify\DataFakes\Tests\Fixtures;

use Maatify\DataFakes\Adapters\Mongo\FakeMongoAdapter;
use Maatify\DataFakes\Adapters\Redis\FakeRedisAdapter;
use Maatify\DataFakes\Fixtures\FakeFixturesLoader;
use Maatify\DataFakes\Fixtures\JsonFixtureParser;
use Maatify\DataFakes\Storage\FakeStorageLayer;
use PHPUnit\Framework\TestCase;

final class FakeFixturesLoaderTest extends TestCase
{
    private FakeStorageLayer $storage;
    private FakeRedisAdapter $redis;
    private FakeMongoAdapter $mongo;
    private FakeFixturesLoader $loader;

    protected function setUp(): void
    {
        $this->storage = new FakeStorageLayer();
        $this->redis   = new FakeRedisAdapter();
        $this->mongo   = new FakeMongoAdapter($this->storage);
        $this->loader  = new FakeFixturesLoader($this->storage, new JsonFixtureParser(), $this->mongo, $this->redis);
    }

    public function testLoadsFromArray(): void
    {
        $this->loader->loadFixtures([
            'mysql' => [
                'users' => [
                    ['id' => 1, 'name' => 'Alice'],
                ],
            ],
            'mongo' => [
                'products' => [
                    ['_id' => 'p1', 'price' => 100],
                ],
            ],
            'redis' => [
                'strings' => ['token' => 'abc'],
                'hashes'  => ['profile' => ['name' => 'Alice']],
                'lists'   => ['queue' => ['job1', 'job2']],
            ],
        ]);

        $mysqlRows = $this->storage->listAll('users');
        self::assertCount(1, $mysqlRows);
        self::assertSame('Alice', $mysqlRows[0]['name']);

        $mongoRows = $this->mongo->find('products', ['_id' => 'p1']);
        self::assertCount(1, $mongoRows);

        self::assertSame('abc', $this->redis->get('token'));
        self::assertSame('Alice', $this->redis->hget('profile', 'name'));
        self::assertSame(['job1', 'job2'], $this->redis->lrange('queue', 0, 5));
    }

    public function testLoadsFromFile(): void
    {
        $fixturePath = __DIR__ . '/sample-fixtures.json';
        $this->loader->loadFromFile($fixturePath);

        $mysqlRows = $this->storage->listAll('users');
        self::assertCount(2, $mysqlRows);
        self::assertSame('Bob', $mysqlRows[1]['name']);

        $mongoRows = $this->mongo->find('products', ['price' => ['$gt' => 120]]);
        self::assertCount(1, $mongoRows);
        self::assertSame('p2', $mongoRows[0]['_id']);

        self::assertSame('abc', $this->redis->get('token'));
    }
}
