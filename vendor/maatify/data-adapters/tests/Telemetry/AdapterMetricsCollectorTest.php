<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-12 12:33
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Telemetry;

use Maatify\DataAdapters\Telemetry\AdapterMetricsCollector;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class AdapterMetricsCollectorTest**
 *
 * 🎯 **Purpose:**
 * Ensures that {@see AdapterMetricsCollector} correctly handles
 * metric recording, aggregation, and reset functionality.
 *
 * 🧠 **Key Verifications:**
 * - ✅ Metrics are recorded per adapter and operation.
 * - ✅ Aggregated results (latency, success/fail counts) are accurate.
 * - ✅ Reset functionality clears all previously collected data.
 *
 * 🧩 **Test Coverage Summary:**
 * | Test Method                | Purpose                                       |
 * |----------------------------|-----------------------------------------------|
 * | `testRecordAndAggregateMetrics()` | Validates metric aggregation and structure.   |
 * | `testResetClearsMetrics()`        | Ensures reset wipes metrics cleanly.         |
 *
 * @covers \Maatify\DataAdapters\Telemetry\AdapterMetricsCollector
 */
final class AdapterMetricsCollectorTest extends TestCase
{
    /**
     * @var AdapterMetricsCollector Shared metrics collector instance for testing.
     */
    private AdapterMetricsCollector $collector;

    /**
     * 🧱 **Setup Before Each Test**
     *
     * Initializes a clean instance of {@see AdapterMetricsCollector}
     * and resets all metrics to ensure test isolation.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->collector = AdapterMetricsCollector::instance();
        $this->collector->reset();
    }

    /**
     * ✅ **Test: Record and Aggregate Metrics**
     *
     * Verifies that metrics are correctly stored, counted, and averaged.
     * Ensures success/failure counters and latency values are correctly computed.
     *
     * @return void
     */
    public function testRecordAndAggregateMetrics(): void
    {
        // 🧩 Simulate mixed adapter operations
        $this->collector->record('redis', 'get', 2.5, true);
        $this->collector->record('redis', 'get', 3.5, false);
        $this->collector->record('mysql', 'query', 5.0, true);

        // 📊 Retrieve metrics and validate aggregation
        $metrics = $this->collector->getAll();

        // 🔍 Assertions for structure and values
        $this->assertArrayHasKey('redis', $metrics, 'Redis adapter not found in metrics.');
        $this->assertArrayHasKey('mysql', $metrics, 'MySQL adapter not found in metrics.');
        $this->assertSame(
            2,
            $metrics['redis']['get']['success'] + $metrics['redis']['get']['fail'],
            'Redis total operation count mismatch.'
        );
        $this->assertGreaterThan(
            0,
            $metrics['mysql']['query']['avg_latency_ms'],
            'Average latency for MySQL query should be > 0.'
        );
    }

    /**
     * 🧹 **Test: Reset Clears Metrics**
     *
     * Ensures that after calling `reset()`, all collected metrics are removed.
     *
     * @return void
     */
    public function testResetClearsMetrics(): void
    {
        // 🧩 Add temporary metrics
        $this->collector->record('redis', 'set', 1.0, true);

        // 🧹 Perform reset
        $this->collector->reset();

        // ✅ Expect an empty result
        $this->assertSame([], $this->collector->getAll(), 'Metrics were not cleared after reset.');
    }
}
