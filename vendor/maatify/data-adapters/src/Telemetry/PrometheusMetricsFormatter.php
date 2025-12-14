<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-12 12:28
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Telemetry;

/**
 * 🧩 **Class PrometheusMetricsFormatter**
 *
 * 🎯 **Purpose:**
 * Converts the aggregated adapter metrics into a **Prometheus-compatible exposition format**
 * (plain text), allowing integration with Prometheus or Grafana dashboards.
 *
 * 🧠 **Key Features:**
 * - Formats adapter metrics (latency, success, failure) into Prometheus metrics syntax.
 * - Uses labels for `adapter` and `operation` to ensure clear metric separation.
 * - Adds `HELP` and `TYPE` headers for metric introspection.
 *
 * ✅ **Example Usage:**
 * ```php
 * use Maatify\DataAdapters\Telemetry\PrometheusMetricsFormatter;
 * use Maatify\DataAdapters\Telemetry\AdapterMetricsCollector;
 *
 * $collector = AdapterMetricsCollector::instance();
 * $collector->record('redis', 'get', 2.5, true);
 * $collector->record('mysql', 'query', 5.7, false);
 *
 * $formatter = new PrometheusMetricsFormatter($collector);
 * echo $formatter->render();
 * ```
 *
 * ✅ **Example Output:**
 * ```
 * # HELP adapter_latency_avg Average adapter latency (ms)
 * # TYPE adapter_latency_avg gauge
 * adapter_latency_avg{adapter="redis",operation="get"} 2.500
 * adapter_success_total{adapter="redis",operation="get"} 1
 * adapter_fail_total{adapter="redis",operation="get"} 0
 * adapter_latency_avg{adapter="mysql",operation="query"} 5.700
 * adapter_success_total{adapter="mysql",operation="query"} 0
 * adapter_fail_total{adapter="mysql",operation="query"} 1
 * ```
 */
final readonly class PrometheusMetricsFormatter
{
    /**
     * 🧱 **Constructor**
     *
     * Accepts a shared {@see AdapterMetricsCollector} instance.
     *
     * @param AdapterMetricsCollector $collector The collector providing aggregated metrics.
     */
    public function __construct(
        private AdapterMetricsCollector $collector
    ) {
    }

    /**
     * 🧾 **Render Prometheus Metrics Output**
     *
     * Generates a Prometheus-compatible text payload including:
     * - Metric headers (`HELP`, `TYPE`)
     * - Formatted adapter metrics with labels and values
     *
     * @return string Formatted Prometheus exposition text, ready for HTTP response.
     *
     * ✅ **Example:**
     * ```php
     * header('Content-Type: text/plain');
     * echo (new PrometheusMetricsFormatter($collector))->render();
     * ```
     */
    public function render(): string
    {
        // 📊 Standard metric metadata (Prometheus HELP/TYPE headers)
        $lines = [
            '# HELP adapter_latency_avg Average adapter latency (ms)',
            '# TYPE adapter_latency_avg gauge',
        ];

        // 🔍 Retrieve all collected metrics from the collector
        $data = $this->collector->getAll();

        // 🧠 Format each adapter and operation metric into Prometheus lines
        foreach ($data as $adapter => $operations) {
            foreach ($operations as $operation => $info) {
                // Average latency
                $lines[] = sprintf(
                    'adapter_latency_avg{adapter="%s",operation="%s"} %.3f',
                    $adapter,
                    $operation,
                    $info['avg_latency_ms']
                );

                // Success count
                $lines[] = sprintf(
                    'adapter_success_total{adapter="%s",operation="%s"} %d',
                    $adapter,
                    $operation,
                    $info['success']
                );

                // Failure count
                $lines[] = sprintf(
                    'adapter_fail_total{adapter="%s",operation="%s"} %d',
                    $adapter,
                    $operation,
                    $info['fail']
                );
            }
        }

        // 🧾 Combine lines into a single string for HTTP/plaintext output
        return implode(PHP_EOL, $lines) . PHP_EOL;
    }
}
