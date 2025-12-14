<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:13
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Support\Adapters;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\Common\Tests\Support\Connections\FakeRedisConnection;

final class FakeHealthyAdapter implements AdapterInterface
{
    private FakeRedisConnection $connection;
    private bool $connected = false;

    public function __construct()
    {
        $this->connection = new FakeRedisConnection();
    }

    public function connect(): void
    {
        $this->connected = true;
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }

    public function getConnection(): FakeRedisConnection
    {
        return $this->connection;
    }

    public function healthCheck(): bool
    {
        echo "HEALTH CHECK OK\n";
        return true;
    }

    public function disconnect(): void
    {
        $this->connected = false;
    }

    public function getDriver(): string
    {
        return 'fake';
    }
}
