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

namespace Maatify\DataFakes\Storage\Snapshots;

/**
 * Immutable snapshot of FakeStorageLayer tables and auto-increment metadata.
 */
class SnapshotState
{
    /**
     * @param array<string, array<int|string, array<string, mixed>>> $tables
     * @param array<string, int>                                     $autoIds
     */
    public function __construct(
        private readonly array $tables,
        private readonly array $autoIds
    ) {
    }

    /**
     * @return array<string, array<int|string, array<string, mixed>>>
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * @return array<string, int>
     */
    public function getAutoIds(): array
    {
        return $this->autoIds;
    }
}
