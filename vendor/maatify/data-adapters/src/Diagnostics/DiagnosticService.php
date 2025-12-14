<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 21:12
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Diagnostics;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;

/**
 * 🧩 **DiagnosticService**
 *
 * Provides unified health diagnostics across all registered database adapters.
 *
 * 🎯 Responsibilities:
 * - Register adapters by route (e.g., `mysql.main`, `mongo.logs`, `redis`)
 * - Instantiate adapters through the `DatabaseResolver`
 * - Run standard health checks (`connect()` + `healthCheck()`)
 * - Collect diagnostic status (success/error/timestamp)
 * - Export results as JSON
 *
 * ---
 * ### Example
 * ```php
 * $diag = new DiagnosticService($env, $resolver);
 * $diag->register(['mysql.main', 'redis.cache', 'mongo.logs']);
 *
 * $results = $diag->collect();
 * echo $diag->toJson();
 * ```
 * ---
 */
final class DiagnosticService
{
    /**
     * Registered adapters for diagnostics.
     *
     * @var array<string, AdapterInterface>
     */
    private array $adapters = [];

    /**
     * @param EnvironmentConfig $config   Environment configuration manager
     * @param DatabaseResolver  $resolver Adapter auto-router
     */
    public function __construct(
        private readonly EnvironmentConfig $config,
        private readonly DatabaseResolver  $resolver
    ) {
    }

    /**
     * 🧩 **Register adapters for diagnostics**
     *
     * Accepts array of adapter routes:
     * ```
     * ['mysql', 'mysql.main', 'redis', 'mongo.logs']
     * ```
     *
     * @param array<int,string> $types
     */
    public function register(array $types): void
    {
        foreach ($types as $type) {

            // 👉 Normalize key (lowercase + trimmed)
            $key = strtolower(trim($type));

            // 👉 Resolve adapter using string-routing
            $this->adapters[$key] = $this->resolver->resolve($key);
        }
    }

    /**
     * 🩺 **Run diagnostics for all registered adapters**
     *
     * - Attempts connection
     * - Runs `healthCheck()`
     * - Logs errors via failover system
     *
     * @return array<int,array<string,mixed>>
     */
    public function collect(): array
    {
        $results = [];

        foreach ($this->adapters as $key => $adapter) {

            $connected = false;
            $error     = null;

            try {
                // 🔌 Attempt connection
                $adapter->connect();

                // 🧪 Run health check
                $connected = $adapter->healthCheck();
            } catch (\Throwable $e) {

                // Record exception message
                $error = $e->getMessage();

                // 🔥 Old failover logging system (static call)
                AdapterFailoverLog::record($key, $error);

            } finally {
                // Disconnect after check
                $adapter->disconnect();
            }

            $results[] = [
                'adapter'   => $key,
                'connected' => $connected,
                'error'     => $error,
                'timestamp' => date('Y-m-d H:i:s'),
            ];
        }

        return $results;
    }

    /**
     * 🧱 **Return results as formatted JSON**
     *
     * @return string JSON output with diagnostics
     */
    public function toJson(): string
    {
        $json = json_encode(
            ['diagnostics' => $this->collect()],
            JSON_PRETTY_PRINT
        );

        return $json === false ? '{}' : $json;
    }

    /**
     * Get all registered adapter instances.
     *
     * @return array<string, AdapterInterface>
     */
    public function getAdapters(): array
    {
        return $this->adapters;
    }

    /**
     * Get the environment configuration instance.
     */
    public function getConfig(): EnvironmentConfig
    {
        return $this->config;
    }
}
