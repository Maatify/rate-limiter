<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-16 17:46
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Traits;

use Maatify\Common\Contracts\Adapter\AdapterInterface;

trait ProvidesRawAccessTrait
{
    protected AdapterInterface $adapter;

    /**
     * Expose the raw driver instance for advanced operations.
     *
     * @return mixed  PDO | Doctrine\DBAL\Connection | MongoDB\Database | Redis | Predis\Client
     */
    public function raw(): mixed
    {
        return $this->adapter->getDriver();
    }
}
