<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:20
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Adapters\MySQL;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\DataFakes\Adapters\Base\Traits\SimulationAwareTrait;
use Maatify\DataFakes\Storage\FakeStorageLayer;
use Maatify\DataFakes\Adapters\Base\Traits\NormalizesInputTrait;
use Maatify\DataFakes\Adapters\Base\Traits\QueryFilterTrait;

/**
 * FakeMySQLAdapter
 *
 * In-memory simulation of basic MySQL CRUD operations.
 * Fully implements AdapterInterface for compatibility with real adapters.
 */
class FakeMySQLAdapter implements AdapterInterface
{
    use NormalizesInputTrait;
    use QueryFilterTrait;
    use SimulationAwareTrait;

    private bool $connected = false;

    public function __construct(private FakeStorageLayer $storage)
    {
        // not connected until connect() is called
    }

    // ===========================================================
    //  AdapterInterface Implementation
    // ===========================================================

    public function connect(): void
    {
        $this->guardOperation('mysql.connect');
        $this->connected = true;
    }

    public function isConnected(): bool
    {
        $this->guardOperation('mysql.health');
        return $this->connected;
    }

    /**
     * Fake adapter returns FakeStorageLayer instead of real DBAL/Mongo/Redis connection.
     *
     * @return FakeStorageLayer|null
     */
    public function getConnection(): mixed
    {
        $this->guardOperation('mysql.connection');
        return $this->connected ? $this->storage : null;
    }

    public function healthCheck(): bool
    {
        $this->guardOperation('mysql.health');
        return $this->connected;
    }

    public function disconnect(): void
    {
        $this->guardOperation('mysql.disconnect');
        $this->connected = false;
    }

    /**
     * Fake adapter driver is always FakeStorageLayer.
     *
     * @return FakeStorageLayer
     */
    public function getDriver(): mixed
    {
        $this->guardOperation('mysql.driver');
        return $this->storage;
    }

    // ===========================================================
    //  Fake CRUD Operations
    // ===========================================================

    /**
     * @param   string                $table
     * @param   array<string, mixed>  $filters
     * @param   array{
     *     orderBy?: string,
     *     order?: 'ASC'|'DESC',
     *     limit?: int,
     *     offset?: int
     * }                              $options
     *
     * @return array<int, array<string, mixed>>
     */
    public function select(string $table, array $filters = [], array $options = []): array
    {
        $this->guardOperation('mysql.select');
        $rows = $this->storage->read($table);

        $rows = $this->applyFilters($rows, $filters);

        if (isset($options['orderBy'])) {
            $rows = $this->applyOrdering(
                $rows,
                $options['orderBy'],
                strtoupper($options['order'] ?? 'ASC')
            );
        }

        if (isset($options['limit'])) {
            $offset = (int)($options['offset'] ?? 0);
            $rows = array_slice($rows, $offset, $options['limit']);
        }

        return array_values($rows);
    }

    /**
     * @param   string                $table
     * @param   array<string, mixed>  $row
     *
     * @return array<string, mixed>
     */
    public function insert(string $table, array $row): array
    {
        $this->guardOperation('mysql.insert');
        $normalized = $this->normalizeRow($row);

        return $this->storage->write($table, $normalized);
    }

    /**
     * @param   string                $table
     * @param   array<string, mixed>  $filters
     * @param   array<string, mixed>  $updates
     *
     * @return int Number of rows updated
     */
    public function update(string $table, array $filters, array $updates): int
    {
        $this->guardOperation('mysql.update');
        $rows = $this->storage->read($table);
        $matched = $this->applyFilters($rows, $filters);
        $count = 0;

        foreach ($matched as $id => $_row) {
            $rows[$id] = array_merge($rows[$id], $updates);
            $count++;
        }

        $this->storage->writeTable($table, $rows);

        return $count;
    }

    /**
     * @param   string                $table
     * @param   array<string, mixed>  $filters
     *
     * @return int Number of rows deleted
     */
    public function delete(string $table, array $filters): int
    {
        $this->guardOperation('mysql.delete');
        $rows = $this->storage->read($table);
        $matched = $this->applyFilters($rows, $filters);
        $count = 0;

        foreach (array_keys($matched) as $id) {
            unset($rows[$id]);
            $count++;
        }

        $this->storage->writeTable($table, $rows);

        return $count;
    }
}
