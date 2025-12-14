<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:03
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Lock;

use Maatify\Common\Lock\HybridLockManager;
use Maatify\Common\Lock\LockModeEnum;
use Maatify\Common\Tests\Support\Adapters\FakeFailingAdapter;
use Maatify\Common\Tests\Support\Adapters\FakeHealthyAdapter;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class HybridLockManagerTest**
 *
 * 🎯 **Purpose:**
 * Verifies correct behavior of {@see HybridLockManager} under different adapter conditions,
 * including successful Redis-based locking, fallback to file-based locking, and queue-mode
 * waiting behavior.
 *
 * 🧠 **Test Coverage:**
 * - ✅ Redis adapter health and acquisition.
 * - 🔁 Fallback mechanism when adapter fails.
 * - ⏳ Queue (blocking) mode acquisition timing.
 *
 * ⚙️ **Usage Example:**
 * ```bash
 * vendor/bin/phpunit --filter HybridLockManagerTest
 * ```
 */
final class HybridLockManagerTest extends TestCase
{
    protected function setUp(): void
    {
        $dir = sys_get_temp_dir() . '/maatify/locks';

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        foreach (glob("$dir/*.lock") ?: [] as $file) {
            @unlink($file);
        }
    }
    /**
     * ✅ **Test Redis adapter usage when healthy.**
     *
     * 🧩 Ensures that when a healthy adapter (simulated Redis) is provided,
     * the `HybridLockManager` uses it successfully for lock operations.
     *
     * Expected behavior:
     * - Lock acquisition succeeds.
     * - Lock state reflects as active.
     * - Lock releases cleanly.
     *
     * @return void
     */
    public function testUsesRedisAdapterWhenHealthy(): void
    {
        $adapter = new FakeHealthyAdapter();

        $lock = new HybridLockManager('test_redis', LockModeEnum::EXECUTION, adapter: $adapter);

        $this->assertTrue($lock->acquire(), 'Should acquire lock using Redis adapter');
        $this->assertTrue($lock->isLocked(), 'Lock must be active');
        $lock->release();
        $this->assertFalse($lock->isLocked(), 'Lock should be released');
    }

    /**
     * 🧱 **Test fallback to FileLockManager when adapter fails.**
     *
     * 🧠 Simulates an unhealthy adapter to verify that the `HybridLockManager`
     * automatically switches to file-based locking.
     *
     * Expected behavior:
     * - Fallback is triggered automatically.
     * - Lock operations remain functional using file-based locks.
     *
     * @return void
     */
    public function testFallbackToFileLockWhenAdapterFails(): void
    {
        $adapter = new FakeFailingAdapter();

        $lock = new HybridLockManager('test_file', LockModeEnum::EXECUTION, adapter: $adapter);

        // ✅ Should use fallback (FileLockManager)
        $this->assertTrue($lock->acquire(), 'Should acquire fallback FileLock');
        $this->assertTrue($lock->isLocked(), 'File-based lock should be active');
        $lock->release();
        $this->assertFalse($lock->isLocked(), 'File-based lock released');
    }

    /**
     * ⏳ **Test queue-mode behavior with waiting mechanism.**
     *
     * 🧠 Simulates two concurrent locks on the same key:
     * - Lock 1 holds the key.
     * - Lock 2 waits until Lock 1 is released before acquiring.
     *
     * Expected behavior:
     * - Lock 2 waits before acquiring.
     * - Delay is measurable (simulating retry interval).
     *
     * @return void
     */
    public function testQueueModeWaitsAndAcquires(): void
    {
        $adapter = new FakeHealthyAdapter();

        // TTL = 2 seconds
        $lock1 = new HybridLockManager('queue_lock', LockModeEnum::EXECUTION, ttl: 3, adapter: $adapter);
        $lock2 = new HybridLockManager('queue_lock', LockModeEnum::QUEUE, ttl: 3, adapter: $adapter);

        $lock1->acquire();  // lock is held for 2 sec

        $start = microtime(true);
        $lock2->waitAndAcquire(200_000); // retry delay 0.2 sec
        $end = microtime(true);

        $elapsed = $end - $start;

        $this->assertGreaterThan(
            1.5,
            $elapsed,
            "Lock2 must wait at least 1.5 seconds (actual: $elapsed)"
        );

        $lock2->release();
    }

}
