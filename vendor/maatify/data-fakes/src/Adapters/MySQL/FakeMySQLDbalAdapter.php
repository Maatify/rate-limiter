<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:21
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Adapters\MySQL;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\DataFakes\Storage\FakeStorageLayer;
use Maatify\DataFakes\Adapters\Base\Traits\NormalizesInputTrait;

/**
 * FakeMySQLDbalAdapter
 *
 * Doctrine-style DBAL wrapper built on top of FakeMySQLAdapter.
 * Fully implements AdapterInterface by delegating lifecycle to FakeMySQLAdapter.
 */
class FakeMySQLDbalAdapter implements AdapterInterface
{
    use NormalizesInputTrait;

    public function __construct(private FakeMySQLAdapter $mysql)
    {
    }

    // ===========================================================
    // AdapterInterface Lifecycle (delegated)
    // ===========================================================

    public function connect(): void
    {
        $this->mysql->connect();
    }

    public function isConnected(): bool
    {
        return $this->mysql->isConnected();
    }

    /**
     * Fake adapter returns FakeStorageLayer instead of real DBAL/Mongo/Redis connection.
     *
     * @return FakeStorageLayer|null
     */
    public function getConnection(): mixed
    {
        return $this->mysql->getConnection();
    }

    public function healthCheck(): bool
    {
        return $this->mysql->healthCheck();
    }

    public function disconnect(): void
    {
        $this->mysql->disconnect();
    }

    /**
     * Fake adapter driver always resolves to FakeStorageLayer.
     *
     * @return FakeStorageLayer
     */
    public function getDriver(): mixed
    {
        return $this->mysql->getDriver();
    }

    // ===========================================================
    // DBAL Operations
    // ===========================================================

    /**
     * @param string $table
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(string $table, array $filters = []): array
    {
        return $this->mysql->select($table, $filters);
    }

    /**
     * @param string $table
     * @param array<string, mixed> $filters
     *
     * @return array<string, mixed>|null
     */
    public function fetchOne(string $table, array $filters = []): ?array
    {
        $rows = $this->mysql->select($table, $filters, ['limit' => 1]);
        return $rows[0] ?? null;
    }

    /**
     * Insert row using normalized DBAL-style input.
     *
     * @param string $table
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function insert(string $table, array $data): array
    {
        return $this->mysql->insert($table, $this->normalizeRow($data));
    }
}
