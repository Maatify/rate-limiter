<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-12 12:29
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Telemetry;

use Throwable;

/**
 * ⚙️ **Class AdapterMetricsMiddleware**
 *
 * 🎯 **Purpose:**
 * Acts as a lightweight middleware wrapper for adapter operations,
 * automatically measuring execution time and recording performance metrics
 * (latency, success, and failure) via {@see AdapterMetricsCollector}.
 *
 * 🧠 **Key Features:**
 * - Measures precise operation latency in milliseconds.
 * - Tracks success or failure automatically via `try/catch/finally`.
 * - Minimal overhead and framework-agnostic.
 * - Ideal for wrapping Redis, MySQL, HTTP, or any I/O-bound operation.
 *
 * ✅ **Example Usage:**
 * ```php
 * use Maatify\DataAdapters\Telemetry\AdapterMetricsMiddleware;
 * use Maatify\DataAdapters\Telemetry\AdapterMetricsCollector;
 *
 * $middleware = new AdapterMetricsMiddleware(AdapterMetricsCollector::instance());
 *
 * $data = $middleware->measure('redis', 'get', function () {
 *     // Simulate adapter operation
 *     return 'value_from_cache';
 * });
 * ```
 *
 * ✅ **Example with Exception:**
 * ```php
 * try {
 *     $middleware->measure('mysql', 'query', function () {
 *         throw new RuntimeException('Query failed');
 *     });
 * } catch (Throwable $e) {
 *     // Exception still propagates, but failure metric is recorded automatically
 * }
 * ```
 */
final readonly class AdapterMetricsMiddleware
{
    /**
     * 🧱 **Constructor**
     *
     * Accepts the shared {@see AdapterMetricsCollector} instance for recording metrics.
     *
     * @param AdapterMetricsCollector $collector The collector responsible for storing metrics.
     */
    public function __construct(
        private AdapterMetricsCollector $collector
    ) {
    }

    /**
     * 🧩 **Measure an Operation’s Execution Time and Record Metrics**
     *
     * Wraps any callable operation to automatically:
     * - Record its latency (in milliseconds).
     * - Mark success/failure depending on whether an exception was thrown.
     *
     * @template T
     * @param string       $adapter   Adapter name (e.g., `"redis"`, `"mysql"`, `"api"`).
     * @param string       $operation Operation name (e.g., `"get"`, `"set"`, `"query"`).
     * @param callable():T $callback  Operation to execute and measure.
     *
     * @return T Returns the result of the wrapped callback.
     *
     * @throws Throwable Re-throws any exception from the callback while recording as failure.
     *
     * ✅ **Example:**
     * ```php
     * $middleware->measure('redis', 'set', fn() => $redis->set('key', 'value'));
     * ```
     */
    public function measure(string $adapter, string $operation, callable $callback)
    {
        // ⏱ Start time tracking (in microseconds)
        $start = microtime(true);

        try {
            // ✅ Execute operation
            $result = $callback();
            $success = true;

            return $result;
        } catch (Throwable $exception) {
            // 🚫 Failure detected — mark unsuccessful
            $success = false;
            throw $exception;
        } finally {
            // 📊 Compute elapsed time and record the metric
            $latency = (microtime(true) - $start) * 1000;
            $this->collector->record($adapter, $operation, $latency, $success);
        }
    }
}
