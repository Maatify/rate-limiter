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

use Random\Engine\Mt19937;
use Random\Randomizer;

/**
 * Injects deterministic failure scenarios for fake adapters and repositories.
 */
final class ErrorSimulator
{
    /** @var array<string, list<FailureScenario>> */
    private array $scenarios = [];

    public function __construct(private readonly Randomizer $randomizer = new Randomizer(new Mt19937()))
    {
    }

    public function registerScenario(string $operation, FailureScenario $scenario): void
    {
        $this->scenarios[$operation][] = $scenario;
    }

    public function clearScenarios(string $operation): void
    {
        unset($this->scenarios[$operation]);
    }

    public function hasScenarios(string $operation): bool
    {
        return isset($this->scenarios[$operation]);
    }

    public function failIfTriggered(string $operation): void
    {
        $scenario = $this->nextFailure($operation);
        if ($scenario === null) {
            return;
        }

        $exceptionClass = $scenario->getExceptionClass();
        if (!is_subclass_of($exceptionClass, \Throwable::class)) {
            throw new \RuntimeException('Configured exception class must implement Throwable.');
        }

        throw new $exceptionClass($scenario->getMessage(), $scenario->getCode());
    }

    private function nextFailure(string $operation): ?FailureScenario
    {
        $scenarios = $this->scenarios[$operation] ?? [];
        foreach ($scenarios as $scenario) {
            if ($scenario->getProbability() === 0.0) {
                continue;
            }

            if ($scenario->getProbability() === 1.0) {
                return $scenario;
            }

            if ($this->randomizer->getFloat(0.0, 1.0) <= $scenario->getProbability()) {
                return $scenario;
            }
        }

        return null;
    }
}
