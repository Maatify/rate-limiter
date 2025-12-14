<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:22
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Adapters\Base\Traits;

use Maatify\DataFakes\Storage\FakeStorageLayer;

/**
 * Provides access to the in-memory FakeStorageLayer instance.
 */
trait HasMemoryStoreTrait
{
    protected FakeStorageLayer $store;

    public function setStore(FakeStorageLayer $store): void
    {
        $this->store = $store;
    }

    public function getStore(): FakeStorageLayer
    {
        return $this->store;
    }
}
