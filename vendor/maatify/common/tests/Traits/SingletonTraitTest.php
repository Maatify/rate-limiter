<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:34
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Traits;

use Maatify\Common\Traits\SingletonTrait;
use PHPUnit\Framework\TestCase;

/**
 * 🧠 **SingletonTraitTest**
 *
 * ✅ Unit tests for the {@see SingletonTrait} to ensure proper singleton behavior:
 * - Same instance is always returned via `obj()`.
 * - `reset()` creates a new instance.
 * - `getInstance()` acts as an alias of `obj()`.
 *
 * @package Maatify\Common\Tests\Traits
 *
 * @example
 * ```php
 * $instance1 = ExampleSingleton::obj();
 * $instance2 = ExampleSingleton::obj();
 * assert($instance1 === $instance2); // ✅ Same instance
 *
 * ExampleSingleton::reset();
 * $instance3 = ExampleSingleton::obj();
 * assert($instance1 !== $instance3); // ✅ New instance created
 * ```
 */
final class SingletonTraitTest extends TestCase
{
    /**
     * 🎯 Ensures that multiple calls to {@see ExampleSingleton::obj()}
     * return the *same* instance reference.
     *
     * @return void
     */
    public function testObjReturnsSameInstance(): void
    {
        $first = ExampleSingleton::obj();
        $second = ExampleSingleton::obj();

        // ✅ Expecting both instances to be identical (same memory reference)
        $this->assertSame($first, $second);
    }

    /**
     * 🔁 Ensures that calling {@see ExampleSingleton::reset()}
     * actually destroys the previous instance and creates a new one.
     *
     * @return void
     */
    public function testResetCreatesNewInstance(): void
    {
        $first = ExampleSingleton::obj();

        // 🧩 Reset the internal static instance
        ExampleSingleton::reset();

        $second = ExampleSingleton::obj();

        // ✅ Expecting new instance after reset
        $this->assertNotSame($first, $second);
    }

    /**
     * 🔹 Ensures that {@see ExampleSingleton::getInstance()}
     * is a valid alias for {@see ExampleSingleton::obj()}.
     *
     * @return void
     */
    public function testAliasGetInstance(): void
    {
        // ✅ Both methods must return the exact same singleton instance
        $this->assertSame(ExampleSingleton::obj(), ExampleSingleton::getInstance());
    }
}

/**
 * 🧩 **ExampleSingleton**
 *
 * A lightweight dummy class using {@see SingletonTrait}
 * for testing purposes within this test suite.
 *
 * @internal Used only by {@see SingletonTraitTest}.
 */
final class ExampleSingleton
{
    use SingletonTrait;

    /**
     * 💡 Arbitrary property used to verify instance persistence.
     *
     * @var int
     */
    public int $value = 42;
}
