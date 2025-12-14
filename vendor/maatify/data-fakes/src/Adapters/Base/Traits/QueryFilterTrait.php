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

/**
 * Implements filtering, ordering and simple pattern matching.
 */
trait QueryFilterTrait
{
    /**
     * @param array<int|string, array<string, mixed>> $rows
     * @param array<string, mixed>                    $filters
     * @return array<int|string, array<string, mixed>>
     */
    protected function applyFilters(array $rows, array $filters): array
    {
        if ($filters === []) {
            return $rows;
        }

        return array_filter($rows, function (array $row) use ($filters): bool {

            foreach ($filters as $column => $expected) {

                if (!array_key_exists($column, $row)) {
                    return false;
                }

                $actual = $row[$column];

                // Normalize to string safely when required
                $actualString = is_scalar($actual) ? (string) $actual : '';

                // IN filter
                if (is_array($expected)) {
                    if (!in_array($actual, $expected, true)) {
                        return false;
                    }
                    continue;
                }

                // Contains: %text%
                if (is_string($expected) && str_starts_with($expected, '%') && str_ends_with($expected, '%')) {
                    $pattern = trim($expected, '%');
                    if (!str_contains($actualString, $pattern)) {
                        return false;
                    }
                    continue;
                }

                // Regex: /pattern/
                if (is_string($expected) && str_starts_with($expected, '/') && str_ends_with($expected, '/')) {
                    if (@preg_match($expected, $actualString) !== 1) {
                        return false;
                    }
                    continue;
                }

                // Scalar strict equality
                if ($actual !== $expected) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * @param array<int|string, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    protected function applyOrdering(array $rows, string $column, string $order): array
    {
        usort($rows, function (array $a, array $b) use ($column, $order): int {

            $va = $a[$column] ?? null;
            $vb = $b[$column] ?? null;

            if ($va === $vb) {
                return 0;
            }

            return ($order === 'DESC')
                ? (($va < $vb) ? 1 : -1)
                : (($va > $vb) ? 1 : -1);
        });

        return $rows;
    }
}
