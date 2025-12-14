<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:01
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Repository;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\Common\Contracts\Repository\RepositoryInterface;
use Maatify\DataFakes\Repository\Collections\FakeCollection;
use Maatify\DataFakes\Repository\Hydration\ArrayHydrator;
use Maatify\DataFakes\Storage\FakeStorageLayer;

class FakeRepository implements RepositoryInterface
{
    private ?AdapterInterface $adapter = null;

    public function __construct(
        private readonly FakeStorageLayer $storage,
        private readonly string $table,
        private readonly ?ArrayHydrator $hydrator = null,
        /** @var class-string|null */
        private readonly ?string $hydrateClass = null
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @return int|string
     */
    public function insert(array $data): int|string
    {
        $row = $this->storage->write($this->table, $data);
        $id  = $row['id'] ?? null;

        if (!is_int($id) && !is_string($id) && !is_float($id)) {
            throw new \RuntimeException('Failed to generate identifier for inserted row.');
        }

        return is_numeric($id) ? (int) $id : $id;
    }

    public function find(string|int $id): ?array
    {
        return $this->storage->readById($this->table, $id);
    }

    /**
     * @param array<string, mixed> $filters
     * @return array<int, array<string, mixed>>
     */
    public function findBy(array $filters): array
    {
        $rows = array_filter(
            $this->storage->read($this->table),
            fn (array $row): bool => $this->matchesFilters($row, $filters)
        );

        return array_values($rows);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        return $this->storage->listAll($this->table);
    }

    /**
     * @param array<string, mixed> $filters
     * @return FakeCollection<int, array<string, mixed>|object>
     */
    public function findCollection(array $filters = []): FakeCollection
    {
        $rows = $filters === [] ? $this->findAll() : $this->findBy($filters);

        return new FakeCollection($rows, $this->hydrator, $this->hydrateClass);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(string|int $id, array $data): bool
    {
        return $this->storage->updateById($this->table, $id, $data) !== null;
    }

    public function delete(string|int $id): bool
    {
        return $this->storage->deleteById($this->table, $id);
    }

    public function setAdapter(AdapterInterface $adapter): static
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getAdapter(): ?AdapterInterface
    {
        return $this->adapter;
    }

    /**
     * @param array<string, mixed> $row
     * @param array<string, mixed> $filters
     */
    private function matchesFilters(array $row, array $filters): bool
    {
        foreach ($filters as $key => $value) {
            if (!array_key_exists($key, $row)) {
                return false;
            }

            if (is_array($value)) {
                if (!in_array($row[$key], $value, true)) {
                    return false;
                }

                continue;
            }

            if ($row[$key] !== $value) {
                return false;
            }
        }

        return true;
    }
}
