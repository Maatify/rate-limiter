<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-12 12:35
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Telemetry;

use Maatify\DataAdapters\Telemetry\{
    AdapterMetricsCollector,
    AdapterMetricsMiddleware
};
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;

/**
 * 🧪 **Class AdapterMetricsMiddlewareTest**
 *
 * 🎯 **Purpose:**
 * Validates the automatic timing, success/failure tracking, and
 * exception-handling behavior of {@see AdapterMetricsMiddleware}.
 *
 * 🧠 **Key Verifications:**
 * - ✅ Successful operations record success counts and latency metrics.
 * - ✅ Failed operations still record latency and increment failure counts.
 * - ✅ Exceptions are re-thrown to preserve business logic integrity.
 *
 * 🧩 **Test Coverage Summary:**
 * | Test Method                               | Purpose                                          |
 * |-------------------------------------------|--------------------------------------------------|
 * | `testMeasureRecordsSuccessAndLatency()`   | Verifies latency and success tracking.           |
 * | `testMeasureRecordsFailureAndThrowsException()` | Ensures failures increment fail counters. |
 *
 * @covers \Maatify\DataAdapters\Telemetry\AdapterMetricsMiddleware
 */
final class AdapterMetricsMiddlewareTest extends TestCase
{
    /**
     * @var AdapterMetricsCollector Shared collector instance for tests.
     */
    private AdapterMetricsCollector $collector;

    /**
     * @var AdapterMetricsMiddleware Middleware instance under test.
     */
    private AdapterMetricsMiddleware $middleware;

    /**
     * 🧱 **Setup Before Each Test**
     *
     * Initializes a fresh collector and middleware to guarantee
     * test independence and data isolation.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->collector = AdapterMetricsCollector::instance();
        $this->collector->reset();
        $this->middleware = new AdapterMetricsMiddleware($this->collector);
    }

    /**
     * ✅ **Test: Measure Records Success and Latency**
     *
     * Ensures that a successful adapter operation records a success metric
     * and returns the expected result.
     *
     * @return void
     * @throws Throwable
     */
    public function testMeasureRecordsSuccessAndLatency(): void
    {
        // 🧩 Perform a mock successful operation
        $result = $this->middleware->measure('redis', 'get', fn () => 'ok');

        // ✅ Verify returned result
        $this->assertSame('ok', $result);

        // 📊 Retrieve and verify collected metrics
        $metrics = $this->collector->getAll();
        $this->assertArrayHasKey('redis', $metrics, 'Redis adapter metrics missing.');
        $this->assertSame(1, $metrics['redis']['get']['success'], 'Success count mismatch.');
    }

    /**
     * ⚠️ **Test: Measure Records Failure and Throws Exception**
     *
     * Verifies that when an exception occurs inside the measured callback:
     * - The middleware rethrows the exception (preserving normal control flow).
     * - The failure is still recorded in metrics.
     *
     * @return void
     * @throws Throwable
     */
    public function testMeasureRecordsFailureAndThrowsException(): void
    {
        $this->expectException(RuntimeException::class);

        try {
            // 🧩 Simulate a failed adapter operation
            $this->middleware->measure('mysql', 'query', function () {
                throw new RuntimeException('Simulated failure');
            });
        } finally {
            // 📊 Even after failure, a failure metric should be recorded
            $metrics = $this->collector->getAll();
            $this->assertSame(1, $metrics['mysql']['query']['fail'], 'Failure count mismatch.');
        }
    }
}
