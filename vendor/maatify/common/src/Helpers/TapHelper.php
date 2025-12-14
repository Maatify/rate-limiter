<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-11 00:32
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Helpers;

/**
 * 🧩 **Class TapHelper**
 *
 * 🎯 **Purpose:**
 * Provides a fluent, functional-style utility to operate on a value
 * via a callback and return that same value — useful for inline configuration
 * or side-effect operations without breaking method chaining.
 *
 * 🧠 **Concept:**
 * Inspired by the `tap()` helper pattern in functional programming,
 * this method allows concise object setup and modification during initialization.
 *
 * ✅ **Example Usage:**
 * ```php
 * use Maatify\Common\Helpers\TapHelper;
 *
 * $client = TapHelper::tap(new Redis(), fn($r) => $r->connect('localhost'));
 *
 * $config = TapHelper::tap(['env' => 'prod'], function (&$cfg) {
 *     $cfg['debug'] = false;
 * });
 * ```
 *
 * 🧩 **Key Benefits:**
 * - Cleaner object initialization syntax.
 * - Promotes readability and immutability.
 * - Reduces boilerplate setup code across factories and adapters.
 */
final class TapHelper
{
    /**
     * 🔁 **Pass a value to a callback and return it unchanged.**
     *
     * Executes the provided callback with the given value, then
     * returns the same value — enabling expressive, fluent configuration flows.
     *
     * @template T
     *
     * @param T                 $value     The object or value to operate on.
     * @param callable(T): void $callback  The function to apply to the value.
     *
     * @return T Returns the same `$value`, after executing the callback.
     *
     * ✅ **Example:**
     * ```php
     * $pdo = TapHelper::tap(new PDO($dsn), fn($db) => $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));
     * ```
     */
    public static function tap(mixed $value, callable $callback): mixed
    {
        // 🧠 Invoke callback to perform side-effect without altering the return value
        $callback($value);

        // 🔁 Return the same value for chaining or reuse
        return $value;
    }
}
