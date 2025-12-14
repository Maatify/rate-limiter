<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-26 10:02
 * @see         https://www.maatify.dev Maatify.dev
 * @link        https://github.com/Maatify/common view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Contracts\Redis;

/**
 * 🔌 RedisClientInterface
 *
 * A unified contract for Redis operations supported across:
 *  - phpredis (Redis extension)
 *  - Predis (Pure PHP)
 *  - FakeRedisAdapter (testing layer)
 *
 * Minimal KV surface:
 *  - get()
 *  - set()
 *  - del()
 *  - keys()
 */
interface RedisClientInterface
{
    /**
     * @param   string  $key
     *
     * @return string|false|null
     */
    public function get(string $key): string|false|null;

    public function set(string $key, string $value): bool;

    /**
     * @return int Number of deleted keys
     */
    public function del(string ...$keys): int;

    /**
     * @return array<int,string>
     */
    public function keys(string $pattern): array;
}
