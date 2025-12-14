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

namespace Maatify\DataFakes\Repository\Hydration;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class ArrayHydrator
{
    /**
     * Hydrate an object using public properties that match the provided array keys.
     *
     * @param class-string $class
     * @param array<string, mixed> $data
     * @return object
     */
    public function hydrate(string $class, array $data): object
    {
        try {
            $reflection = new ReflectionClass($class);
            $instance   = $reflection->newInstanceWithoutConstructor();
        } catch (ReflectionException $exception) {
            throw new InvalidArgumentException(sprintf('Unable to hydrate %s: %s', $class, $exception->getMessage()), 0, $exception);
        }

        foreach ($data as $property => $value) {
            if ($reflection->hasProperty($property)) {
                $propertyRef = $reflection->getProperty($property);
                if ($propertyRef->isPublic()) {
                    $propertyRef->setValue($instance, $value);
                    continue;
                }
            }

            if (property_exists($instance, $property)) {
                $instance->{$property} = $value;
            }
        }

        return $instance;
    }
}
