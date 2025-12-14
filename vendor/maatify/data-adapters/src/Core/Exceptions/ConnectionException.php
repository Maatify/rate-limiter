<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:29
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Core\Exceptions;

use RuntimeException;

/**
 * ⚠️ Class ConnectionException
 *
 * 🧩 Purpose:
 * Represents an exception thrown when a data adapter fails to establish
 * or maintain a valid connection to its target data source.
 *
 * ✅ Typical Scenarios:
 * - Missing required environment variables.
 * - Invalid connection credentials or configuration.
 * - Network or authentication issues with the target service.
 *
 * ⚙️ Example Usage:
 * ```php
 * throw new ConnectionException("Failed to connect to Redis server");
 * ```
 *
 * @package Maatify\DataAdapters\Core\Exceptions
 */
final class ConnectionException extends RuntimeException
{
}
