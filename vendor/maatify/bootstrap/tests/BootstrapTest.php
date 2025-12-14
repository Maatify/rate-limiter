<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 15:59
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

use Maatify\Bootstrap\Core\Bootstrap;
use PHPUnit\Framework\TestCase;

final class BootstrapTest extends TestCase
{
    public function testInitIsIdempotent(): void
    {
        Bootstrap::init(__DIR__ . '/../');
        $this->assertTrue(Bootstrap::isInitialized());

        Bootstrap::init(__DIR__ . '/../'); // should not throw or re-init
        $this->assertTrue(Bootstrap::isInitialized());
    }
}