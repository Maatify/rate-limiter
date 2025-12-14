<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-12 12:30
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Telemetry\Logger;

/**
 * 🧾 **Class AdapterLogContext**
 *
 * 🎯 **Purpose:**
 * Builds a structured, standardized log context array for adapter-level
 * operations. Designed to enrich PSR-3 logs with consistent telemetry metadata
 * such as adapter name, operation, latency, and execution status.
 *
 * 🧠 **Key Features:**
 * - Unified log context structure for all data adapters (Redis, MySQL, Mongo, etc.).
 * - Records latency, status, and timestamps in ISO-8601 format.
 * - Optional message field for extended log detail.
 * - Ideal for usage within adapter wrappers or telemetry middleware.
 *
 * ✅ **Example Usage:**
 * ```php
 * use Maatify\DataAdapters\Telemetry\Logger\AdapterLogContext;
 * use Psr\Log\LoggerInterface;
 *
 * $context = AdapterLogContext::build('redis', 'get', 2.345, true, 'Cache hit');
 * $logger->info('Adapter operation completed.', $context);
 * ```
 *
 * ✅ **Example Output:**
 * ```php
 * [
 *   'adapter'   => 'redis',
 *   'operation' => 'get',
 *   'latency'   => '2.345 ms',
 *   'status'    => 'success',
 *   'message'   => 'Cache hit',
 *   'timestamp' => '2025-11-12T12:31:45+00:00',
 * ]
 * ```
 */
final class AdapterLogContext
{
    /**
     * 🧩 **Build Structured Log Context**
     *
     * Generates a standardized associative array describing an adapter operation.
     * Can be directly passed as `$context` in PSR-3 log calls.
     *
     * @param string      $adapter   Adapter identifier (e.g., `"redis"`, `"mysql"`, `"http-client"`).
     * @param string      $operation Operation name (e.g., `"get"`, `"query"`, `"set"`).
     * @param float       $latencyMs Operation latency in milliseconds.
     * @param bool        $success   Whether the operation succeeded or failed.
     * @param string|null $message   Optional descriptive message for contextual clarity.
     *
     * @return array<string, string> Structured context array with metadata.
     *
     * ✅ **Example:**
     * ```php
     * AdapterLogContext::build('mysql', 'query', 10.245, false, 'Syntax error');
     * ```
     */
    public static function build(
        string $adapter,
        string $operation,
        float $latencyMs,
        bool $success,
        ?string $message = null
    ): array {
        $context = [
            'adapter'   => $adapter,
            'operation' => $operation,
            'latency'   => round($latencyMs, 3) . ' ms',
            'status'    => $success ? 'success' : 'fail',
            'timestamp' => date('c'),
        ];

        if ($message !== null) {
            $context['message'] = $message;
        }

        return $context;
    }
}
