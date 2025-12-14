<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-05 21:25
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Pagination\Helpers;

use Maatify\Common\Pagination\DTO\PaginationDTO;

/**
 * 📄 **Class PaginationHelper**
 *
 * 🎯 **Purpose:**
 * Provides simple and consistent pagination utilities for **in-memory, cached, or API-based**
 * datasets — eliminating the need for direct database pagination logic.
 *
 * 🧠 **Key Responsibilities:**
 * - Paginate local arrays, collections, or iterables.
 * - Generate reusable pagination metadata objects (`PaginationDTO`).
 * - Support database and API pagination offsets.
 *
 * ✅ **Features:**
 * - Works with any iterable (arrays, generators, collections).
 * - Includes navigation flags (`hasNext`, `hasPrev`).
 * - Offers helper for SQL-style LIMIT/OFFSET generation.
 *
 * ⚙️ **Example:**
 * ```php
 * use Maatify\Common\Pagination\Helpers\PaginationHelper;
 *
 * $items = range(1, 100);
 * $result = PaginationHelper::paginate($items, page: 2, perPage: 10);
 *
 * print_r($result['pagination']->toArray());
 * ```
 */
final class PaginationHelper
{
    /**
     * 🔹 **Paginate a given dataset (array or iterable).**
     *
     * Converts a dataset (array, iterator, generator) into a paginated structure
     * that includes both sliced data and metadata about pagination state.
     *
     * 🧩 **Behavior:**
     * - Automatically handles edge cases like negative page values.
     * - Calculates total records, total pages, and navigation flags.
     * - Converts any iterable into a standard array before slicing.
     *
     * @param iterable<int, mixed> $items   Full dataset to paginate.
     * @param int      $page    Current page number (1-based index).
     * @param int      $perPage Number of items per page.
     *
     * @return array{
     *     data: array<int, mixed>,
     *     pagination: PaginationDTO
     * }
     *
     * ✅ **Example:**
     * ```php
     * $items = range(1, 50);
     * $result = PaginationHelper::paginate($items, page: 3, perPage: 10);
     * print_r($result['pagination']->toArray());
     * ```
     */
    public static function paginate(iterable $items, int $page = 1, int $perPage = 20): array
    {
        // ✅ Normalize iterable input into array
        $data = is_array($items) ? $items : iterator_to_array($items);

        // 📊 Determine total count
        $total = count($data);

        // 📍 Safely calculate offset
        $offset = max(($page - 1) * $perPage, 0);

        // ✂️ Slice array to retrieve current page subset
        $paginated = array_slice($data, $offset, $perPage);

        // 📈 Calculate total page count
        $totalPages = (int)ceil($total / $perPage);

        // 🧾 Return paginated data with metadata object
        return [
            'data' => $paginated,
            'pagination' => new PaginationDTO(
                page: $page,
                perPage: $perPage,
                total: $total,
                totalPages: $totalPages,
                hasNext: $page < $totalPages,
                hasPrev: $page > 1
            ),
        ];
    }

    /**
     * 🧮 **Build a `PaginationDTO` directly from metadata.**
     *
     * Designed for situations where the total record count is already known
     * (e.g., from SQL `COUNT()` or API metadata).
     *
     * 🧩 **Behavior:**
     * - Computes total page count.
     * - Automatically sets navigation flags.
     *
     * @param int $total   Total number of records.
     * @param int $page    Current page number.
     * @param int $perPage Number of items per page.
     *
     * @return PaginationDTO Fully constructed pagination metadata object.
     *
     * ✅ **Example:**
     * ```php
     * $meta = PaginationHelper::buildMeta(total: 150, page: 3, perPage: 25);
     * echo json_encode($meta->toArray());
     * ```
     */
    public static function buildMeta(int $total, int $page, int $perPage): PaginationDTO
    {
        // ⚙️ Compute page count
        $totalPages = (int)ceil($total / $perPage);

        // 🧾 Return immutable metadata DTO
        return new PaginationDTO(
            page: $page,
            perPage: $perPage,
            total: $total,
            totalPages: $totalPages,
            hasNext: $page < $totalPages,
            hasPrev: $page > 1
        );
    }

    /**
     * ⚙️ **Generate MySQL-style LIMIT and OFFSET values.**
     *
     * Converts pagination parameters (`page`, `perPage`) into database-friendly
     * values suitable for SQL queries.
     *
     * 🧠 **Example:**
     * ```php
     * [$limit, $offset] = PaginationHelper::toLimitOffset(3, 20);
     * // $limit = 20, $offset = 40
     * ```
     *
     * @param int $page    Current page number (1-based).
     * @param int $perPage Number of items per page.
     *
     * @return array{int,int} Returns `[limit, offset]`.
     */
    public static function toLimitOffset(int $page, int $perPage): array
    {
        $page = max($page, 1);
        $limit = max($perPage, 1);
        $offset = ($page - 1) * $limit;

        return [$limit, $offset];
    }
}
