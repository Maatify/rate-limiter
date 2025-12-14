<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-12 12:22
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Telemetry;

/**
 * 🧠 **Class AdapterMetricsCollector**
 *
 * 🎯 **Purpose:**
 * Provides in-memory collection and aggregation of runtime metrics for
 * all registered adapters and their operations — including latency, success,
 * and failure counts.
 *
 * 🧩 **Key Features:**
 * - Tracks metrics per adapter and operation type.
 * - Calculates average latency per operation.
 * - Singleton pattern ensures consistent tracking throughout runtime.
 * - Lightweight and dependency-free telemetry layer.
 *
 * ⚙️ **Example Usage:**
 * ```php
 * use Maatify\DataAdapters\Telemetry\AdapterMetricsCollector;
 *
 * // Record metrics
 * AdapterMetricsCollector::instance()->record('redis', 'get', 2.5, true);
 * AdapterMetricsCollector::instance()->record('mysql', 'query', 5.7, false);
 *
 * // Retrieve aggregated results
 * print_r(AdapterMetricsCollector::instance()->getAll());
 * ```
 *
 * ✅ **Output Example:**
 * ```php
 * [
 *   'redis' => [
 *     'get' => ['avg_latency_ms' => 2.5, 'success' => 1, 'fail' => 0],
 *   ],
 *   'mysql' => [
 *     'query' => ['avg_latency_ms' => 5.7, 'success' => 0, 'fail' => 1],
 *   ],
 * ]
 * ```
 */
final class AdapterMetricsCollector
{
    /**
     * 🧱 **Singleton Instance**
     *
     * Holds the shared instance of the metrics collector.
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * 📊 **Metrics Data Store**
     *
     * Nested associative array storing collected metrics.
     * Structure:
     * ```
     * [
     *   'adapter' => [
     *     'operation' => [
     *       'latency' => float[],
     *       'success' => int,
     *       'fail'    => int,
     *     ]
     *   ]
     * ]
     * ```
     *
     * @var array<string, array<string, array{latency: float[], success: int, fail: int}>>
     */
    private array $metrics = [];

    /**
     * 🧩 **Private Constructor**
     *
     * Enforces singleton usage through {@see instance()}.
     */
    private function __construct()
    {
    }

    /**
     * 🧠 **Retrieve Singleton Instance**
     *
     * @return self Shared `AdapterMetricsCollector` instance.
     */
    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * ⚙️ **Record a New Operation Metric**
     *
     * Tracks the latency and success/failure of an adapter operation.
     * Used by adapters to report runtime performance data.
     *
     * @param string $adapter    Adapter name (e.g., `"redis"`, `"mysql"`).
     * @param string $operation  Operation name (e.g., `"get"`, `"set"`, `"query"`).
     * @param float  $latencyMs  Operation duration in milliseconds.
     * @param bool   $success    Whether the operation succeeded (`true`) or failed (`false`).
     *
     * @return void
     *
     * ✅ **Example:**
     * ```php
     * AdapterMetricsCollector::instance()->record('redis', 'get', 1.8, true);
     * ```
     */
    public function record(string $adapter, string $operation, float $latencyMs, bool $success): void
    {
        // Create bucket on first use
        if (!isset($this->metrics[$adapter][$operation])) {
            $this->metrics[$adapter][$operation] = [
                'latency' => [],
                'success' => 0,
                'fail'    => 0,
            ];
        }

        // Reference for convenience
        $bucket = &$this->metrics[$adapter][$operation];

        // Add latency
        $bucket['latency'][] = $latencyMs;

        // Increment counters
        if ($success) {
            $bucket['success']++;
        } else {
            $bucket['fail']++;
        }
    }

    /**
     * 📊 **Retrieve Aggregated Metrics**
     *
     * Returns summarized metrics for all adapters, including average latency,
     * total successes, and total failures.
     *
     * @return array<string, array<string, array{avg_latency_ms: float, success: int, fail: int}>>
     *
     * ✅ **Example Output:**
     * ```php
     * [
     *   'redis' => ['get' => ['avg_latency_ms' => 2.5, 'success' => 10, 'fail' => 1]],
     * ]
     * ```
     */
    public function getAll(): array
    {
        $result = [];

        foreach ($this->metrics as $adapter => $operations) {
            foreach ($operations as $op => $data) {
                $avg = ! empty($data['latency'])
                    ? array_sum($data['latency']) / count($data['latency'])
                    : 0.0;

                $result[$adapter][$op] = [
                    'avg_latency_ms' => round($avg, 3),
                    'success'        => $data['success'],
                    'fail'           => $data['fail'],
                ];
            }
        }

        return $result;
    }

    /**
     * 🧹 **Reset All Collected Metrics**
     *
     * Clears all stored metrics from memory.
     * Typically used during tests or adapter restarts.
     *
     * @return void
     */
    public function reset(): void
    {
        $this->metrics = [];
    }
}
