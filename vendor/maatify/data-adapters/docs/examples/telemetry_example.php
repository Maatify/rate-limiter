<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-12 12:49
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

use Maatify\DataAdapters\Telemetry\{
    AdapterMetricsCollector,
    AdapterMetricsMiddleware,
    PrometheusMetricsFormatter
};

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * 🧪 **Telemetry Example Script**
 *
 * 🎯 **Purpose:**
 * Demonstrates how to use:
 * - {@see AdapterMetricsCollector} for runtime metric collection.
 * - {@see AdapterMetricsMiddleware} for automatic timing and recording.
 * - {@see PrometheusMetricsFormatter} for Prometheus-compatible exports.
 *
 * 🧠 **Workflow Overview:**
 * 1️⃣ Initialize the collector and middleware.
 * 2️⃣ Simulate successful and failed adapter operations.
 * 3️⃣ Display raw collected metrics in array format.
 * 4️⃣ Output Prometheus-compatible formatted metrics.
 *
 * ✅ **Run Example:**
 * ```bash
 * php examples/telemetry/example_metrics.php
 * ```
 */

// 🧠 Initialize the collector & middleware (shared instances)
$collector = AdapterMetricsCollector::instance();
$middleware = new AdapterMetricsMiddleware($collector);

// -----------------------------------------------------------
// 1️⃣ Simulate Adapter Operations
// -----------------------------------------------------------
try {
    // ✅ Successful Redis SET operation
    $middleware->measure('redis', 'set', function () {
        usleep(2000); // Simulate latency (2 ms)
        return true;
    });

    // 🚫 Failed MySQL query simulation
    $middleware->measure('mysql', 'query', function () {
        usleep(4000); // Simulate latency (4 ms)
        throw new RuntimeException('Query timeout');
    });
} catch (Throwable $e) {
    // ⚠️ Capture any thrown exceptions from simulated operations
    echo "⚠️ Exception captured: {$e->getMessage()}\n";
}

// -----------------------------------------------------------
// 2️⃣ Display Raw Collected Metrics
// -----------------------------------------------------------
echo "\n=== Raw Metrics ===\n";
print_r($collector->getAll());

// -----------------------------------------------------------
// 3️⃣ Export Metrics in Prometheus Format
// -----------------------------------------------------------
$formatter = new PrometheusMetricsFormatter($collector);

echo "\n=== Prometheus Metrics ===\n";
echo $formatter->render();
