<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-12 12:34
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Telemetry;

use Maatify\DataAdapters\Telemetry\{
    AdapterMetricsCollector,
    PrometheusMetricsFormatter
};
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class PrometheusMetricsFormatterTest**
 *
 * 🎯 **Purpose:**
 * Validates that {@see PrometheusMetricsFormatter} correctly converts adapter metrics
 * into a Prometheus-compatible exposition format.
 *
 * 🧠 **Key Verifications:**
 * - Ensures presence of `HELP` and `TYPE` headers.
 * - Validates Prometheus label formatting `{adapter="...", operation="..."}`.
 * - Confirms inclusion of all expected metric types:
 *   - `adapter_latency_avg`
 *   - `adapter_success_total`
 *   - `adapter_fail_total`
 * - Checks that output ends with a newline (Prometheus compliance requirement).
 *
 * ✅ **Example Scenario:**
 * - Redis operation (successful) and MySQL operation (failed) are recorded.
 * - The resulting Prometheus text output must contain valid metric lines.
 *
 * @covers \Maatify\DataAdapters\Telemetry\PrometheusMetricsFormatter
 */
final class PrometheusMetricsFormatterTest extends TestCase
{
    /**
     * ✅ **Test: Render Outputs Valid Prometheus Format**
     *
     * Ensures that the formatter produces a valid and complete Prometheus metrics string
     * including required headers, metrics, and labels.
     *
     * @return void
     */
    public function testRenderOutputsValidPrometheusFormat(): void
    {
        // 🧩 Prepare collector with sample metrics
        $collector = AdapterMetricsCollector::instance();
        $collector->reset();
        $collector->record('redis', 'get', 3.25, true);
        $collector->record('mysql', 'query', 5.10, false);

        // ⚙️ Create formatter and render Prometheus text output
        $formatter = new PrometheusMetricsFormatter($collector);
        $output = $formatter->render();

        // 🔍 Validate Prometheus standard elements and data structure
        $this->assertStringContainsString('# HELP adapter_latency_avg', $output, 'Missing HELP header.');
        $this->assertStringContainsString('# TYPE adapter_latency_avg gauge', $output, 'Missing TYPE definition.');
        $this->assertStringContainsString('adapter_latency_avg{adapter="redis"', $output, 'Missing Redis latency metric.');
        $this->assertStringContainsString('adapter_success_total', $output, 'Missing success total metric.');
        $this->assertStringContainsString('adapter_fail_total', $output, 'Missing failure total metric.');

        // 🧾 Ensure Prometheus output compliance — must end with a newline
        $this->assertStringEndsWith(PHP_EOL, $output, 'Output must end with a newline.');
    }
}
