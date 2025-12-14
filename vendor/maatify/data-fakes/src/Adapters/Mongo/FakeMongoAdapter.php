<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 06:15
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Adapters\Mongo;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\DataFakes\Adapters\Base\Traits\SimulationAwareTrait;
use Maatify\DataFakes\Storage\FakeStorageLayer;

/**
 * Fake MongoDB Adapter
 */
class FakeMongoAdapter implements AdapterInterface
{
    use SimulationAwareTrait;

    private bool $connected = false;

    /**
     * @var FakeStorageLayer
     */
    private FakeStorageLayer $storage;

    public function __construct(?FakeStorageLayer $storage = null)
    {
        $this->storage = $storage ?? new FakeStorageLayer();
    }

    public function connect(): void
    {
        $this->guardOperation('mongo.connect');
        $this->connected = true;
    }

    public function disconnect(): void
    {
        $this->guardOperation('mongo.disconnect');
        $this->connected = false;
    }

    public function isConnected(): bool
    {
        $this->guardOperation('mongo.health');
        return $this->connected;
    }

    public function healthCheck(): bool
    {
        $this->guardOperation('mongo.health');
        return $this->connected;
    }

    public function getDriver(): FakeStorageLayer
    {
        $this->guardOperation('mongo.driver');
        return $this->storage;
    }

    public function getConnection(): FakeStorageLayer
    {
        $this->guardOperation('mongo.connection');
        return $this->storage;
    }

    /**
     * @param array<string,mixed> $document
     * @return array<string,mixed>
     */
    public function insertOne(string $collection, array $document): array
    {
        $this->guardOperation('mongo.insert_one');
        if (! isset($document['_id'])) {
            $document['_id'] = $this->generateId();
        }

        /** @var array<int,array<string,mixed>> $rows */
        $rows = array_values($this->storage->read($collection));
        $rows[] = $document;

        $this->storage->writeTable($collection, $rows);

        return $document;
    }

    /**
     * @param array<int,array<string,mixed>> $documents
     * @return array<int,array<string,mixed>>
     */
    public function insertMany(string $collection, array $documents): array
    {
        $this->guardOperation('mongo.insert_many');
        $inserted = [];

        foreach ($documents as $doc) {
            $inserted[] = $this->insertOne($collection, $doc);
        }

        return $inserted;
    }

    /**
     * @param array<string,mixed> $filters
     * @return array<string,mixed>|null
     */
    public function findOne(string $collection, array $filters): ?array
    {
        $this->guardOperation('mongo.find_one');
        $rows = $this->find($collection, $filters);

        return $rows[0] ?? null;
    }

    /**
     * @param array<string,mixed> $filters
     * @return array<int,array<string,mixed>>
     */
    public function find(string $collection, array $filters): array
    {
        $this->guardOperation('mongo.find');
        /** @var array<int,array<string,mixed>> $rows */
        $rows = array_values($this->storage->read($collection));

        $results = [];

        foreach ($rows as $row) {
            if ($this->matchFilters($row, $filters)) {
                $results[] = $row;
            }
        }

        return array_values($results);
    }

    /**
     * @param array<string,mixed> $filters
     * @param array<string,mixed> $updates
     */
    public function updateOne(string $collection, array $filters, array $updates): int
    {
        $this->guardOperation('mongo.update');
        /** @var array<int,array<string,mixed>> $rows */
        $rows = array_values($this->storage->read($collection));
        $updated = 0;

        foreach ($rows as $i => $row) {
            if ($this->matchFilters($row, $filters)) {
                foreach ($updates as $field => $value) {
                    $row[$field] = $value;
                }
                $rows[$i] = $row;
                $updated = 1;
                break;
            }
        }

        $this->storage->writeTable($collection, $rows);

        return $updated;
    }

    /**
     * @param array<string,mixed> $filters
     */
    public function deleteOne(string $collection, array $filters): int
    {
        $this->guardOperation('mongo.delete');
        /** @var array<int,array<string,mixed>> $rows */
        $rows = array_values($this->storage->read($collection));
        $deleted = 0;

        foreach ($rows as $i => $row) {
            if ($this->matchFilters($row, $filters)) {
                unset($rows[$i]);
                $deleted = 1;
                break;
            }
        }

        $this->storage->writeTable($collection, array_values($rows));

        return $deleted;
    }

    /**
     * @param array<string,mixed> $row
     * @param array<string,mixed> $filters
     */
    private function matchFilters(array $row, array $filters): bool
    {
        foreach ($filters as $field => $condition) {
            $value = $row[$field] ?? null;

            if (is_array($condition)) {
                foreach ($condition as $op => $expected) {
                    if (! $this->applyOperator($op, $value, $expected)) {
                        return false;
                    }
                }
            } elseif ($value !== $condition) {
                return false;
            }
        }

        return true;
    }

    private function applyOperator(string $op, mixed $value, mixed $expected): bool
    {
        return match ($op) {
            '$eq'  => $value === $expected,
            '$ne'  => $value !== $expected,
            '$in'  => in_array($value, (array) $expected, true),
            '$nin' => ! in_array($value, (array) $expected, true),
            '$gt'  => is_numeric($value) && is_numeric($expected) && $value > $expected,
            '$gte' => is_numeric($value) && is_numeric($expected) && $value >= $expected,
            '$lt'  => is_numeric($value) && is_numeric($expected) && $value < $expected,
            '$lte' => is_numeric($value) && is_numeric($expected) && $value <= $expected,
            default => false,
        };
    }

    private function generateId(): string
    {
        return uniqid('fake_', true);
    }
}
