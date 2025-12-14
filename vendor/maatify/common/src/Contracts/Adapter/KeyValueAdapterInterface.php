<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-12-09 08:45
 * @see         https://www.maatify.dev Maatify.dev
 * @link        https://github.com/Maatify/common view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Contracts\Adapter;

interface KeyValueAdapterInterface
{
    public function get(string $key): mixed;
    public function set(string $key, mixed $value, ?int $ttl = null): void;
    public function del(string $key): void;
}
