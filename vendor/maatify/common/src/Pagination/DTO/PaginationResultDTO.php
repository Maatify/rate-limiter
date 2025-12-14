<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 21:47
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Pagination\DTO;

/**
 * Class PaginationResultDTO
 *
 * Represents a standardized paginated result structure containing:
 * - A list of paginated data
 * - Pagination metadata (via {@see PaginationDTO})
 * - Optional extra metadata (e.g. collection name, applied filters, etc.)
 *
 * This DTO provides a consistent format for paginated API responses
 * and inter-module data exchange within maatify projects.
 *
 * Example:
 * ```php
 * $pagination = new PaginationDTO(page: 1, perPage: 10, total: 50, totalPages: 5, hasNext: true, hasPrev: false);
 * $result = new PaginationResultDTO(
 *     data: $records,
 *     pagination: $pagination,
 *     meta: ['collection' => 'users', 'filter' => 'active']
 * );
 * return $result->toArray();
 * ```
 */
final class PaginationResultDTO
{
    /**
     * @param   array<int, mixed>          $data        The actual paginated dataset (e.g., database records or API results)..
     * @param   PaginationDTO              $pagination  The pagination metadata describing current pagination state.
     * @param   array<string, mixed>|null  $meta        Optional additional metadata such as collection name, applied filters, or context info.
     */
    public function __construct(
        public readonly array $data,
        public readonly PaginationDTO $pagination,
        public readonly ?array $meta = null,
    ) {
    }

    /**
     * Convert the DTO into an associative array for JSON serialization or API response output.
     *
     * @return array{
     *     data: array<int, mixed>,
     *     pagination: array{
     *     page: int,
     *     perPage: int,
     *     total: int,
     *     totalPages: int,
     *     hasNext: bool,
     *     hasPrev: bool
     * },
     *     meta?: array<string, mixed>
     * }
     */
    public function toArray(): array
    {
        $result = [
            'data'       => $this->data,
            'pagination' => $this->pagination->toArray(),
        ];

        if (! empty($this->meta)) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }

    /**
     * Create a new PaginationResultDTO instance from raw data arrays.
     *
     * @param   array<int, mixed>          $data        The paginated dataset.
     * @param   array{
     *     page:int,
     *     perPage:int,
     *     total:int,
     *     totalPages:int,
     *     hasNext:bool,
     *     hasPrev:bool
     * }  $pagination
     * @param   array<string, mixed>|null  $meta        Optional metadata array (e.g. filters, context, collection info).
     *
     * @return self
     */
    public static function fromArray(array $data, array $pagination, ?array $meta = null): self
    {
        return new self(
            data      : $data,
            pagination: PaginationDTO::fromArray($pagination),
            meta      : $meta
        );
    }
}
