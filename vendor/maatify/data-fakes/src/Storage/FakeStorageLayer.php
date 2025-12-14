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

namespace Maatify\DataFakes\Storage;

use Maatify\DataFakes\Simulation\LatencySimulator;

/**
 * FakeStorageLayer
 *
 * Unified in-memory engine powering all FakeAdapters (MySQL, DBAL, Redis, Mongo, …).
 * - Deterministic behaviour for testing
 * - Fully isolated per-test (no global state)
 * - Supports auto-increment IDs
 * - Table-level read/write/reset operations
 */
class FakeStorageLayer
{
    /** @var array<string, array<int|string, array<string, mixed>>> */
    private array $tables = [];

    /** @var array<string, int> */
    private array $autoIds = [];

    private ?LatencySimulator $latencySimulator = null;

    public function setLatencySimulator(LatencySimulator $latencySimulator): void
    {
        $this->latencySimulator = $latencySimulator;
    }

    private function applyLatency(string $operation): void
    {
        if ($this->latencySimulator !== null) {
            $this->latencySimulator->applyLatency($operation);
        }
    }

    /**
     * Read all rows of a table.
     *
     * @param string $table
     * @return array<int|string, array<string, mixed>>
     */
    public function read(string $table): array
    {
        $this->applyLatency('storage.read');
        return $this->tables[$table] ?? [];
    }

    /**
     * Read a row by its identifier.
     *
     * @param string     $table
     * @param int|string $id
     * @return array<string, mixed>|null
     */
    public function readById(string $table, int|string $id): ?array
    {
        $this->applyLatency('storage.read_by_id');
        $normalizedId = $this->normalizeKey($id);

        return $this->tables[$table][$normalizedId] ?? null;
    }

    /**
     * Insert a new row and return it after applying auto-increment.
     *
     * @param string               $table
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    public function write(string $table, array $row): array
    {
        $this->applyLatency('storage.write');
        // Initialize table if not exists
        if (!isset($this->tables[$table])) {
            $this->tables[$table] = [];
            $this->autoIds[$table] = 1;
        }

        // Auto-increment if missing
        if (!isset($row['id'])) {
            $row['id'] = $this->autoIds[$table]++;
        } else {
            // Safe convert id to integer
            $id = is_numeric($row['id']) ? (int)$row['id'] : 0;
            // Keep auto increment counter always ahead
            $this->autoIds[$table] = max($this->autoIds[$table], $id + 1);
            $row['id'] = $id;
        }

        $this->tables[$table][$row['id']] = $row;

        return $row;
    }

    /**
     * Update a row by its identifier.
     *
     * @param string               $table
     * @param int|string           $id
     * @param array<string, mixed> $updates
     * @return array<string, mixed>|null
     */
    public function updateById(string $table, int|string $id, array $updates): ?array
    {
        $this->applyLatency('storage.update');
        $row = $this->readById($table, $id);
        if ($row === null) {
            return null;
        }

        unset($updates['id']);
        $updated = array_merge($row, $updates);
        $this->tables[$table][$this->normalizeKey($id)] = $updated;

        return $updated;
    }

    /**
     * Replace an entire table.
     *
     * @param string                                    $table
     * @param array<int|string, array<string, mixed>>   $rows
     */
    public function writeTable(string $table, array $rows): void
    {
        $this->applyLatency('storage.write_table');
        $normalizedRows = [];
        foreach ($rows as $row) {
            // Prefer an explicit numeric id, otherwise fall back to Mongo-style _id
            $id = $row['id'] ?? ($row['_id'] ?? null);
            if ($id === null) {
                continue;
            }

            if (!is_int($id) && !is_string($id) && !is_float($id)) {
                continue;
            }

            $key = is_numeric($id) ? (int) $id : (string) $id;
            $normalizedRows[$key] = $row;
        }

        $this->tables[$table] = $normalizedRows;

        // Recalculate auto-increment using numeric keys only
        $max = 0;
        foreach ($normalizedRows as $id => $_row) {
            if (is_int($id) || (is_string($id) && ctype_digit($id))) {
                $intId = (int) $id;
                $max    = max($max, $intId);
            }
        }

        $this->autoIds[$table] = $max + 1;
    }

    /**
     * Delete a row by its identifier.
     *
     * @param string     $table
     * @param int|string $id
     * @return bool
     */
    public function deleteById(string $table, int|string $id): bool
    {
        $this->applyLatency('storage.delete');
        $normalizedId = $this->normalizeKey($id);
        if (!isset($this->tables[$table][$normalizedId])) {
            return false;
        }

        unset($this->tables[$table][$normalizedId]);

        return true;
    }

    /**
     * List all rows for a table.
     *
     * @param string $table
     * @return array<int, array<string, mixed>>
     */
    public function listAll(string $table): array
    {
        $this->applyLatency('storage.list');
        return array_values($this->read($table));
    }

    /**
     * Export both tables and auto-increment metadata for snapshotting.
     *
     * @return array{tables: array<string, array<int|string, array<string, mixed>>>, autoIds: array<string, int>}
     */
    public function exportState(): array
    {
        $this->applyLatency('storage.export_state');
        return [
            'tables'  => $this->tables,
            'autoIds' => $this->autoIds,
        ];
    }

    /**
     * Import a previously exported storage state.
     *
     * @param array<string, array<int|string, array<string, mixed>>> $tables
     * @param array<string, int>                                      $autoIds
     */
    public function importState(array $tables, array $autoIds): void
    {
        $this->applyLatency('storage.import_state');
        $this->tables  = $tables;
        $this->autoIds = $autoIds;
    }

    /**
     * Delete a whole table.
     */
    public function drop(string $table): void
    {
        $this->applyLatency('storage.drop');
        unset($this->tables[$table], $this->autoIds[$table]);
    }

    /**
     * Reset full storage — used between tests.
     */
    public function reset(): void
    {
        $this->applyLatency('storage.reset');
        $this->tables  = [];
        $this->autoIds = [];
    }

    /**
     * Return raw state (for debugging/testing).
     *
     * @return array<string, array<int|string, array<string, mixed>>>
     */
    public function export(): array
    {
        $this->applyLatency('storage.export');
        return $this->tables;
    }

    private function normalizeKey(int|string $id): int|string
    {
        return is_numeric($id) ? (int) $id : (string) $id;
    }
}
