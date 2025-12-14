<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-16 17:53
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Repository;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\DataAdapters\Traits\ProvidesRawAccessTrait;

abstract class BaseRedisRepository
{
    use ProvidesRawAccessTrait;

    public function __construct(protected AdapterInterface $adapter)
    {
    }
}
