<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-05 21:26
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Pagination\DTO;

/**
 * 📦 **Class PaginationDTO**
 *
 * 🎯 **Purpose:**
 * Provides a standardized representation of pagination metadata
 * used across Maatify libraries for consistent API responses and internal data handling.
 *
 * 🧠 **Common Use Cases:**
 * - Returning pagination metadata alongside paginated API results.
 * - Transferring paging context between repository, service, and controller layers.
 * - Serializing pagination state for client-side consumption.
 *
 * ✅ **Features:**
 * - Immutable (read-only) data object.
 * - Supports array serialization (`toArray()`, `fromArray()`).
 * - Implements `jsonSerialize()` for clean JSON output.
 *
 * ⚙️ **Example:**
 * ```php
 * use Maatify\Common\Pagination\DTO\PaginationDTO;
 *
 * $pagination = new PaginationDTO(
 *     page: 2,
 *     perPage: 10,
 *     total: 95,
 *     totalPages: 10,
 *     hasNext: true,
 *     hasPrev: true
 * );
 *
 * echo json_encode($pagination->toArray(), JSON_PRETTY_PRINT);
 * ```
 */
final class PaginationDTO
{
    /**
     * 🧱 **Constructor**
     *
     * Initializes an immutable pagination data object.
     *
     * @param int  $page        Current page number (1-based index).
     * @param int  $perPage     Number of items per page.
     * @param int  $total       Total number of records in the dataset.
     * @param int  $totalPages  Total number of pages calculated.
     * @param bool $hasNext     Whether a next page exists.
     * @param bool $hasPrev     Whether a previous page exists.
     */
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
        public readonly int $total,
        public readonly int $totalPages,
        public readonly bool $hasNext,
        public readonly bool $hasPrev,
    ) {
    }

    /**
     * 🔁 **Convert pagination metadata to an associative array.**
     *
     * Useful for:
     * - Building standardized API responses.
     * - Serializing pagination metadata to JSON.
     * - Returning consistent pagination structures across services.
     *
     * @return array{
     *     page: int,
     *     perPage: int,
     *     total: int,
     *     totalPages: int,
     *     hasNext: bool,
     *     hasPrev: bool
     * }
     */
    public function toArray(): array
    {
        return [
            'page'        => $this->page,
            'perPage'     => $this->perPage,
            'total'       => $this->total,
            'totalPages'  => $this->totalPages,
            'hasNext'     => $this->hasNext,
            'hasPrev'     => $this->hasPrev,
        ];
    }

    /**
     * 🧩 **Create a PaginationDTO instance from an associative array.**
     *
     * Converts raw data arrays (e.g., from databases or HTTP payloads)
     * into a strongly typed DTO.
     *
     * Defaults:
     * - `page` → 1
     * - `perPage` → 20
     * - `total` → 0
     * - `totalPages` → 1
     * - `hasNext` / `hasPrev` → false
     *
     * @param array{
     *     page?: int,
     *     perPage?: int,
     *     total?: int,
     *     totalPages?: int,
     *     hasNext?: bool,
     *     hasPrev?: bool
     * } $data
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            page: (int)($data['page'] ?? 1),
            perPage: (int)($data['perPage'] ?? 20),
            total: (int)($data['total'] ?? 0),
            totalPages: (int)($data['totalPages'] ?? 1),
            hasNext: (bool)($data['hasNext'] ?? false),
            hasPrev: (bool)($data['hasPrev'] ?? false),
        );
    }

    /**
     * 📤 **Serialize pagination metadata for JSON output.**
     *
     * Delegates to {@see self::toArray()} to ensure consistent structure.
     *
     * @return array<string, int|bool> The pagination data as an array.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
