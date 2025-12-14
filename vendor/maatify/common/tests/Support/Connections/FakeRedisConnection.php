<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-18 10:47
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Support\Connections;

/**
 * 🧪 **FakeRedisConnection**
 *
 * A lightweight, Redis-compatible in-memory mock used ONLY for testing.
 *
 * ✔ Simulates Redis commands used by RedisLockManager:
 *   - `set(key, value, ['nx', 'ex' => ttl])`
 *   - `exists(key)`
 *   - `del(key)`
 *
 * ✔ Fully deterministic, no I/O, no networking.
 * ✔ Designed to be returned by FakeHealthyAdapter::getConnection().
 */

class FakeRedisConnection
{
    private array $store = [];
    private array $expiry = []; // key => timestamp

    public function set(string $key, string $value, array $options = [])
    {
        $isNx = in_array('nx', $options, true)
                || ($options['nx'] ?? false);

        if ($isNx && isset($this->store[$key]) && !$this->isExpired($key)) {
            return false;
        }

        $this->store[$key] = $value;

        if (isset($options['ex'])) {
            $this->expiry[$key] = time() + (int)$options['ex'];
        }

        return true;
    }

    public function exists(string $key): int
    {
        if ($this->isExpired($key)) {
            unset($this->store[$key], $this->expiry[$key]);
            return 0;
        }

        return isset($this->store[$key]) ? 1 : 0;
    }

    public function del(string $key): void
    {
        unset($this->store[$key], $this->expiry[$key]);
    }

    private function isExpired(string $key): bool
    {
        return isset($this->expiry[$key]) && time() >= $this->expiry[$key];
    }
}
