<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-22 03:01
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Simulation;

/**
 * Applies deterministic latency to simulated operations.
 */
final class LatencySimulator
{
    /** @var array<string, int> */
    private array $operationLatencyMs = [];

    private int $defaultLatencyMs = 0;

    private int $maxJitterMs = 0;

    public function setDefaultLatency(int $milliseconds): void
    {
        $this->defaultLatencyMs = max(0, $milliseconds);
    }

    public function setOperationLatency(string $operation, int $milliseconds): void
    {
        $this->operationLatencyMs[$operation] = max(0, $milliseconds);
    }

    public function setMaxJitter(int $milliseconds): void
    {
        $this->maxJitterMs = max(0, $milliseconds);
    }

    /**
     * Sleep for the configured latency and return the applied duration in milliseconds.
     */
    public function applyLatency(string $operation): int
    {
        $latency = $this->operationLatencyMs[$operation] ?? $this->defaultLatencyMs;
        $jitter  = $this->maxJitterMs > 0 ? random_int(0, $this->maxJitterMs) : 0;
        $total   = $latency + $jitter;

        if ($total > 0) {
            usleep($total * 1000);
        }

        return $total;
    }
}
