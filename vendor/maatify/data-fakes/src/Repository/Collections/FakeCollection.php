<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:01
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Repository\Collections;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Maatify\DataFakes\Repository\Hydration\ArrayHydrator;
use Traversable;

/**
 * @implements IteratorAggregate<int, mixed>
 * @implements ArrayAccess<int, mixed>
 */
class FakeCollection implements IteratorAggregate, Countable, ArrayAccess
{
    /**
     * @param array<int, array<string, mixed>> $items
     * @param ArrayHydrator|null                $hydrator
     * @param class-string|null                 $hydrateClass
     */
    public function __construct(
        private array $items,
        private readonly ?ArrayHydrator $hydrator = null,
        /** @var class-string|null */
        private readonly ?string $hydrateClass = null
    ) {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->hydrateItems());
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->hydrateItems()[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \BadMethodCallException('FakeCollection is immutable.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \BadMethodCallException('FakeCollection is immutable.');
    }

    /**
     * @return array<int, mixed>
     */
    private function hydrateItems(): array
    {
        if ($this->hydrator === null || $this->hydrateClass === null) {
            return $this->items;
        }

        return array_map(fn (array $row): object => $this->hydrator->hydrate($this->hydrateClass, $row), $this->items);
    }
}
