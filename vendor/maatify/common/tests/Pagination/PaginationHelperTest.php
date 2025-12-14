<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 21:34
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Pagination;

use Maatify\Common\Pagination\DTO\PaginationDTO;
use Maatify\Common\Pagination\Helpers\PaginationHelper;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class PaginationHelperTest**
 *
 * 🎯 **Purpose:**
 * Validates functionality of {@see PaginationHelper}, ensuring accurate pagination
 * calculations, array slicing, and LIMIT/OFFSET conversion logic for data retrieval.
 *
 * 🧠 **Covers:**
 * - Array pagination (`paginate()`).
 * - SQL offset and limit computation (`toLimitOffset()`).
 * - Boundary and edge cases (e.g., page 0 handling).
 *
 * ✅ **Usage:**
 * ```bash
 * vendor/bin/phpunit --filter PaginationHelperTest
 * ```
 */
final class PaginationHelperTest extends TestCase
{
    /**
     * ✅ **Test in-memory array pagination.**
     *
     * 🧩 Ensures `paginate()` correctly slices datasets,
     * constructs `PaginationDTO` objects, and computes metadata accurately.
     *
     * Example:
     * ```php
     * $items = range(1, 50);
     * $result = PaginationHelper::paginate($items, page: 2, perPage: 10);
     * // Expected: 10 items, total=50, totalPages=5, hasNext=true, hasPrev=true
     * ```
     *
     * @return void
     */
    public function testPaginateArrayData(): void
    {
        $items = range(1, 50);
        $result = PaginationHelper::paginate($items, page: 2, perPage: 10);

        // ✅ Validate data count
        $this->assertCount(10, $result['data']);

        // ✅ Validate DTO type and structure
        $this->assertInstanceOf(PaginationDTO::class, $result['pagination']);
        $this->assertSame(2, $result['pagination']->page);
        $this->assertSame(10, $result['pagination']->perPage);
        $this->assertSame(50, $result['pagination']->total);
        $this->assertSame(5, $result['pagination']->totalPages);
        $this->assertTrue($result['pagination']->hasNext);
        $this->assertTrue($result['pagination']->hasPrev);
    }

    /**
     * ⚙️ **Test SQL-style LIMIT/OFFSET computation.**
     *
     * 🎯 Ensures `toLimitOffset()` correctly translates page/perPage into
     * SQL query-compatible LIMIT and OFFSET values.
     *
     * Example:
     * ```php
     * [$limit, $offset] = PaginationHelper::toLimitOffset(3, 20);
     * // Expected: $limit = 20, $offset = 40
     * ```
     *
     * @return void
     */
    public function testPaginationOffsetCalculation(): void
    {
        [$limit, $offset] = PaginationHelper::toLimitOffset(3, 20);

        $this->assertSame(20, $limit);
        $this->assertSame(40, $offset);
    }

    /**
     * 🚧 **Test edge cases for pagination calculations.**
     *
     * 🧠 Ensures boundary inputs like page=0 are safely normalized
     * (page 0 behaves as page 1) to prevent invalid offsets.
     *
     * Example:
     * ```php
     * [$limit, $offset] = PaginationHelper::toLimitOffset(0, 10);
     * // Expected: limit=10, offset=0
     * ```
     *
     * @return void
     */
    public function testPaginationEdgeCases(): void
    {
        [$limit, $offset] = PaginationHelper::toLimitOffset(0, 10);

        $this->assertSame(10, $limit);
        $this->assertSame(0, $offset);
    }
}
