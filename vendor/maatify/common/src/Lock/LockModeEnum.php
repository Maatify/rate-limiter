<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 23:22
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Lock;

/**
 * Enum LockModeEnum
 *
 * Defines the operational mode for lock managers.
 * Used to specify how concurrent processes should behave
 * when a lock already exists.
 *
 * 🧩 Modes:
 * - **EXECUTION** → Prevents simultaneous execution (default behaviour).
 * - **QUEUE** → Allows waiting processes to queue and execute sequentially.
 *
 * Example:
 * ```php
 * $mode = LockModeEnum::EXECUTION;
 * if ($mode === LockModeEnum::QUEUE) {
 *     // wait until lock is released, then continue
 * }
 * ```
 */
enum LockModeEnum: string
{
    /** Prevents concurrent execution — fails immediately if locked. */
    case EXECUTION = 'execution';

    /** Queues execution — waits for lock release before continuing. */
    case QUEUE = 'queue';
}
