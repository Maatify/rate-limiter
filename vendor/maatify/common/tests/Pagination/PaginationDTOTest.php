<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 21:36
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Pagination;

use Maatify\Common\Pagination\DTO\PaginationDTO;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class PaginationDTOTest**
 *
 * 🎯 **Purpose:**
 * Verifies that {@see PaginationDTO} behaves as expected — ensuring accurate
 * array serialization, JSON serialization, and consistent data integrity
 * across construction and conversion methods.
 *
 * 🧠 **Covers:**
 * - Proper array structure and key existence.
 * - Correct value assignment and type safety.
 * - JSON encoding compatibility for API responses.
 *
 * ✅ **Usage:**
 * ```bash
 * vendor/bin/phpunit --filter PaginationDTOTest
 * ```
 */
final class PaginationDTOTest extends TestCase
{
    /**
     * ✅ **Test `toArray()` structure and values.**
     *
     * 🧩 Ensures all expected keys exist in the serialized array
     * and the DTO properties are correctly reflected.
     *
     * Example:
     * ```php
     * $dto = new PaginationDTO(2, 10, 45, 5, true, true);
     * $dto->toArray();
     * // Expected keys: page, perPage, total, totalPages, hasNext, hasPrev
     * ```
     *
     * @return void
     */
    public function testDTOToArrayStructure(): void
    {
        $dto = new PaginationDTO(
            page      : 2,
            perPage   : 10,
            total     : 45,
            totalPages: 5,
            hasNext   : true,
            hasPrev   : true
        );

        $arr = $dto->toArray();

        // 🧠 Validate structure
        $this->assertArrayHasKey('page', $arr);
        $this->assertArrayHasKey('perPage', $arr);
        $this->assertArrayHasKey('total', $arr);
        $this->assertArrayHasKey('totalPages', $arr);
        $this->assertArrayHasKey('hasNext', $arr);
        $this->assertArrayHasKey('hasPrev', $arr);

        // 🧾 Validate values
        $this->assertSame(2, $arr['page']);
        $this->assertSame(10, $arr['perPage']);
        $this->assertSame(45, $arr['total']);
        $this->assertSame(5, $arr['totalPages']);
        $this->assertTrue($arr['hasNext']);
        $this->assertTrue($arr['hasPrev']);
    }

    /**
     * 🧩 **Test JSON serialization.**
     *
     * 🎯 Verifies that the DTO can be safely encoded to JSON and
     * includes expected keys and values — used in API responses.
     *
     * Example:
     * ```php
     * $dto = new PaginationDTO(1, 10, 100, 10, true, false);
     * echo json_encode($dto);
     * // Expected JSON: {"page":1,"perPage":10,"total":100,...}
     * ```
     *
     * @return void
     */
    public function testDTOJsonSerialization(): void
    {
        $dto = new PaginationDTO(1, 10, 100, 10, true, false);
        $json = json_encode($dto, JSON_THROW_ON_ERROR);

        // ✅ Check JSON structure
        $this->assertStringContainsString('"page":1', $json);
        $this->assertStringContainsString('"perPage":10', $json);
        $this->assertStringContainsString('"total":100', $json);
        $this->assertStringContainsString('"hasNext":true', $json);
        $this->assertStringContainsString('"hasPrev":false', $json);
    }
}
