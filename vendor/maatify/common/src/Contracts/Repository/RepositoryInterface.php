<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 13:33
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Contracts\Repository;

use Maatify\Common\Contracts\Adapter\AdapterInterface;

/**
 * Interface RepositoryInterface
 *
 * Universal repository contract used across all Maatify libraries
 * including data-adapters, data-repository, and data-fakes.
 */
interface RepositoryInterface
{
    /**
     * Find a single row by ID.
     *
     * @param   int|string  $id
     *
     * @return array<string, mixed>|null
     */
    public function find(int|string $id): ?array;

    /**
     * Find multiple rows by filters.
     *
     * @param   array<string, mixed>  $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function findBy(array $filters): array;

    /**
     * Get all rows.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array;

    /**
     * Insert a new entity.
     *
     * @param   array<string, mixed>  $data
     *
     * @return int|string  The inserted ID
     */
    public function insert(array $data): int|string;

    /**
     * Update an existing entity.
     *
     * @param   int|string            $id
     * @param   array<string, mixed>  $data
     *
     * @return bool
     */
    public function update(int|string $id, array $data): bool;

    /**
     * Delete an entity by ID.
     *
     * @param   int|string  $id
     *
     * @return bool
     */
    public function delete(int|string $id): bool;

    /**
     * Inject the underlying adapter.
     *
     * @return static
     */
    public function setAdapter(AdapterInterface $adapter): static;
}
