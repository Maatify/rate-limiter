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
 * Immutable description of a failure scenario used by ErrorSimulator.
 */
final class FailureScenario
{
    /**
     * @param class-string<\Throwable> $exceptionClass
     */
    public function __construct(
        private readonly string $name,
        private readonly float $probability,
        private readonly string $exceptionClass = \RuntimeException::class,
        private readonly string $message = 'Simulated failure',
        private readonly int $code = 0
    ) {
        if ($this->probability < 0.0 || $this->probability > 1.0) {
            throw new \InvalidArgumentException('Probability must be between 0 and 1.');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProbability(): float
    {
        return $this->probability;
    }

    /**
     * @return class-string<\Throwable>
     */
    public function getExceptionClass(): string
    {
        return $this->exceptionClass;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}
