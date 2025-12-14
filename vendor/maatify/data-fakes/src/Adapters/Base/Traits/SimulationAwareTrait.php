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

namespace Maatify\DataFakes\Adapters\Base\Traits;

use Maatify\DataFakes\Simulation\ErrorSimulator;
use Maatify\DataFakes\Simulation\LatencySimulator;

/**
 * Provides latency and failure hooks for fake adapters.
 */
trait SimulationAwareTrait
{
    private ?LatencySimulator $latencySimulator = null;

    private ?ErrorSimulator $errorSimulator = null;

    public function setLatencySimulator(LatencySimulator $latencySimulator): void
    {
        $this->latencySimulator = $latencySimulator;
    }

    public function setErrorSimulator(ErrorSimulator $errorSimulator): void
    {
        $this->errorSimulator = $errorSimulator;
    }

    protected function guardOperation(string $operation): void
    {
        if ($this->errorSimulator !== null) {
            $this->errorSimulator->failIfTriggered($operation);
        }

        if ($this->latencySimulator !== null) {
            $this->latencySimulator->applyLatency($operation);
        }
    }
}
