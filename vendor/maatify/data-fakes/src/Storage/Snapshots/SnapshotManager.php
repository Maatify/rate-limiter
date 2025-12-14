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

use Maatify\DataFakes\Storage\FakeStorageLayer;

/**
 * Manages creation and restoration of storage snapshots.
 */
class SnapshotManager
{
    public function __construct(private readonly FakeStorageLayer $storage)
    {
    }

    public function createSnapshot(): SnapshotState
    {
        $state = $this->storage->exportState();

        return new SnapshotState($state['tables'], $state['autoIds']);
    }

    public function restoreSnapshot(SnapshotState $snapshot): void
    {
        $this->storage->importState($snapshot->getTables(), $snapshot->getAutoIds());
    }
}
