<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:01
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Environment;

class ResetState
{
    public function __construct(private bool $autoReset = true)
    {
    }

    public function isAutoResetEnabled(): bool
    {
        return $this->autoReset;
    }

    public function disableAutoReset(): void
    {
        $this->autoReset = false;
    }

    public function enableAutoReset(): void
    {
        $this->autoReset = true;
    }
}
