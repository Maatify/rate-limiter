<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 04:55
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Adapters\Redis;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\DataFakes\Adapters\Base\Traits\SimulationAwareTrait;

/**
 * FakeRedisAdapter
 *
 * 🎯 Purpose:
 * In-memory Redis-like simulation layer used for deterministic testing.
 *
 * Supports:
 *  - Strings (get/set/del)
 *  - TTL expiration
 *  - Hashes (hget/hset/hdel)
 *  - Lists (lpush/rpush/lrange)
 *  - Counters (incr/decr)
 *
 * Deterministic behavior is guaranteed to stabilize CI pipelines.
 */
class FakeRedisAdapter implements AdapterInterface
{
    use SimulationAwareTrait;

    /** @var array<string, mixed> */
    private array $store = [];

    /** @var array<string, int> */
    private array $ttl = [];

    private bool $connected = false;

    public function connect(): void
    {
        $this->guardOperation('redis.connect');
        $this->connected = true;
    }

    public function disconnect(): void
    {
        $this->guardOperation('redis.disconnect');
        $this->connected = false;
    }

    public function isConnected(): bool
    {
        $this->guardOperation('redis.health');
        return $this->connected;
    }

    public function healthCheck(): bool
    {
        $this->guardOperation('redis.health');
        return $this->connected;
    }

    public function getConnection(): ?self
    {
        $this->guardOperation('redis.connection');
        return $this->connected ? $this : null;
    }

    public function getDriver(): self
    {
        $this->guardOperation('redis.driver');
        return $this;
    }

    public function reset(): void
    {
        $this->guardOperation('redis.reset');
        $this->store = [];
        $this->ttl   = [];
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        $this->guardOperation('redis.get');
        $this->expireIfNeeded($key);
        return $this->store[$key] ?? null;
    }

    public function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        $this->guardOperation('redis.set');
        $this->store[$key] = $value;

        if ($ttl !== null) {
            $this->ttl[$key] = time() + $ttl;
        }

        return true;
    }

    public function del(string $key): int
    {
        $this->guardOperation('redis.delete');
        $this->expireIfNeeded($key);

        if (!array_key_exists($key, $this->store)) {
            return 0;
        }

        unset($this->store[$key], $this->ttl[$key]);
        return 1;
    }

    /**
     * @return mixed
     */
    public function hget(string $key, string $field)
    {
        $this->guardOperation('redis.hget');
        $this->expireIfNeeded($key);

        $bucket = $this->store[$key] ?? null;

        if (!is_array($bucket)) {
            return null;
        }

        return $bucket[$field] ?? null;
    }

    public function hset(string $key, string $field, mixed $value): bool
    {
        $this->guardOperation('redis.hset');
        $this->expireIfNeeded($key);

        $bucket = $this->store[$key] ?? [];

        if (!is_array($bucket)) {
            $bucket = [];
        }

        $bucket[$field] = $value;
        $this->store[$key] = $bucket;

        return true;
    }

    public function hdel(string $key, string $field): int
    {
        $this->guardOperation('redis.hdel');
        $this->expireIfNeeded($key);

        $bucket = $this->store[$key] ?? null;

        if (!is_array($bucket) || !array_key_exists($field, $bucket)) {
            return 0;
        }

        unset($bucket[$field]);
        $this->store[$key] = $bucket;

        return 1;
    }

    public function lpush(string $key, mixed $value): int
    {
        $this->guardOperation('redis.lpush');
        $this->expireIfNeeded($key);

        $list = $this->store[$key] ?? [];

        if (!is_array($list)) {
            $list = [];
        }

        array_unshift($list, $value);

        $this->store[$key] = $list;

        return count($list);
    }

    public function rpush(string $key, mixed $value): int
    {
        $this->guardOperation('redis.rpush');
        $this->expireIfNeeded($key);

        $list = $this->store[$key] ?? [];

        if (!is_array($list)) {
            $list = [];
        }

        $list[] = $value;

        $this->store[$key] = $list;

        return count($list);
    }

    /**
     * @return array<int, mixed>
     */
    public function lrange(string $key, int $start, int $end): array
    {
        $this->guardOperation('redis.lrange');
        $this->expireIfNeeded($key);

        $list = $this->store[$key] ?? [];

        if (!is_array($list)) {
            return [];
        }

        $length = $end - $start + 1;
        if ($length < 1) {
            return [];
        }

        return array_slice($list, $start, $length);
    }

    public function incr(string $key): int
    {
        $this->guardOperation('redis.incr');
        $this->expireIfNeeded($key);

        $raw = $this->store[$key] ?? 0;
        $int = is_numeric($raw) ? (int)$raw : 0;

        $int++;
        $this->store[$key] = $int;

        return $int;
    }

    public function decr(string $key): int
    {
        $this->guardOperation('redis.decr');
        $this->expireIfNeeded($key);

        $raw = $this->store[$key] ?? 0;
        $int = is_numeric($raw) ? (int)$raw : 0;

        $int--;
        $this->store[$key] = $int;

        return $int;
    }

    private function expireIfNeeded(string $key): void
    {
        $expires = $this->ttl[$key] ?? null;

        if ($expires !== null && $expires < time()) {
            unset($this->store[$key], $this->ttl[$key]);
        }
    }
}
