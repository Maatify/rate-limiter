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

namespace Maatify\DataFakes\Repository;

use Maatify\DataFakes\Storage\FakeStorageLayer;
use Maatify\DataFakes\Storage\Snapshots\SnapshotManager;
use Maatify\DataFakes\Storage\Snapshots\SnapshotState;

/**
 * Minimal unit-of-work wrapper for FakeStorageLayer.
 *
 * Supports nested transactions via stacked snapshots and restores on rollback.
 */
class FakeUnitOfWork
{
    /** @var array<int, SnapshotState> */
    private array $snapshots = [];

    private readonly SnapshotManager $snapshotsManager;

    public function __construct(FakeStorageLayer $storage, ?SnapshotManager $snapshotsManager = null)
    {
        $this->snapshotsManager = $snapshotsManager ?? new SnapshotManager($storage);
    }

    public function begin(): void
    {
        $this->snapshots[] = $this->snapshotsManager->createSnapshot();
    }

    public function commit(): void
    {
        array_pop($this->snapshots);
    }

    public function rollback(): void
    {
        $snapshot = array_pop($this->snapshots);
        if ($snapshot instanceof SnapshotState) {
            $this->snapshotsManager->restoreSnapshot($snapshot);
        }
    }

    public function inTransaction(): bool
    {
        return $this->snapshots !== [];
    }

    /**
     * @template T
     * @param callable():T $callback
     * @return T
     */
    public function transactional(callable $callback)
    {
        $this->begin();

        try {
            $result = $callback();
            $this->commit();

            return $result;
        } catch (\Throwable $exception) {
            $this->rollback();

            throw $exception;
        }
    }
}
