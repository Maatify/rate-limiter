<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Traits;

/**
 * 🧠 **Trait SingletonTrait**
 *
 * 🎯 **Purpose:**
 * Implements a robust, reusable Singleton pattern to ensure a single shared instance
 * of a class throughout the application’s lifecycle.
 *
 * 🧩 **Key Features:**
 * - Prevents direct construction, cloning, or unserialization.
 * - Provides global static access through `obj()` or `getInstance()`.
 * - Allows instance reset for testing or reinitialization scenarios.
 *
 * 🧱 **Best Practices:**
 * - ✅ Ideal for stateless global managers or immutable configurations.
 * - ⚠️ Avoid using in request-scoped or mutable services.
 *
 * ⚙️ **Example:**
 * ```php
 * final class ConfigManager
 * {
 *     use SingletonTrait;
 * }
 *
 * $config = ConfigManager::obj();  // Returns same instance
 * ConfigManager::reset();          // Resets to a new instance
 * ```
 *
 * @package Maatify\Common\Traits
 */
trait SingletonTrait
{
    /**
     * 🧩 **Singleton instance holder**
     *
     * Stores the unique instance for the class using this trait.
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * 🚫 **Prevent direct instantiation**
     *
     * Ensures controlled instance creation via `obj()`.
     */
    private function __construct()
    {
    }

    /**
     * 🚫 **Prevent object cloning**
     *
     * Blocks duplication of the Singleton instance.
     */
    private function __clone()
    {
    }

    /**
     * 🚫 **Prevent unserialization**
     *
     * Protects Singleton integrity by disallowing deserialization.
     *
     * @throws \RuntimeException Always thrown to prevent unserialization.
     */
    final public function __wakeup(): void
    {
        throw new \RuntimeException('Cannot unserialize singleton');
    }

    /**
     * 🔁 **Retrieve or create the Singleton instance**
     *
     * Lazily initializes the instance if not already created.
     *
     * @return self Singleton instance.
     *
     * ✅ **Example:**
     * ```php
     * $logger = Logger::obj();
     * ```
     */
    final public static function obj(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * ♻️ **Reset the Singleton instance**
     *
     * Recreates a new instance, useful for testing or reinitialization.
     *
     * @return void
     *
     * ✅ **Example:**
     * ```php
     * ConfigManager::reset();
     * ```
     */
    final public static function reset(): void
    {
        self::$instance = new self();
    }

    /**
     * 🧱 **Alias of `obj()`**
     *
     * Provides semantic clarity for frameworks or legacy codebases.
     *
     * @return self Singleton instance.
     */
    final public static function getInstance(): self
    {
        return self::obj();
    }
}
