<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 04:56
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Tests\Adapters;

use PHPUnit\Framework\TestCase;
use Maatify\DataFakes\Adapters\Redis\FakeRedisAdapter;

final class FakeRedisAdapterTest extends TestCase
{
    private FakeRedisAdapter $redis;

    protected function setUp(): void
    {
        $this->redis = new FakeRedisAdapter();
        $this->redis->connect();
    }

    public function testSetAndGet(): void
    {
        $this->redis->set('name', 'Mohamed');
        $this->assertSame('Mohamed', $this->redis->get('name'));
    }

    public function testDel(): void
    {
        $this->redis->set('key', 'test');
        $this->assertSame(1, $this->redis->del('key'));
        $this->assertNull($this->redis->get('key'));
    }

    public function testHash(): void
    {
        $this->redis->hset('user:1', 'name', 'Ali');
        $this->assertSame('Ali', $this->redis->hget('user:1', 'name'));

        $this->assertSame(1, $this->redis->hdel('user:1', 'name'));
    }

    public function testListOperations(): void
    {
        $this->redis->lpush('nums', 1);
        $this->redis->rpush('nums', 2);

        $this->assertSame([1, 2], $this->redis->lrange('nums', 0, 10));
    }

    public function testCounters(): void
    {
        $this->assertSame(1, $this->redis->incr('count'));
        $this->assertSame(2, $this->redis->incr('count'));
        $this->assertSame(1, $this->redis->decr('count'));
    }

    public function testTTLExpiration(): void
    {
        $this->redis->set('temp', 'X', 1);
        sleep(2);
        $this->assertNull($this->redis->get('temp'));
    }
}
