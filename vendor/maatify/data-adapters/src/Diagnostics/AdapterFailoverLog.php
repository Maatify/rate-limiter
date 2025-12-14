<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 21:13
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Diagnostics;

/**
 * ⚙️ Class AdapterFailoverLog
 *
 * 🧩 Purpose:
 * Provides a lightweight, file-based logger for tracking adapter failures
 * and fallback events that occur during database or cache connectivity checks.
 *
 * ✅ Features:
 * - Writes structured log entries with timestamps and adapter identifiers.
 * - Creates directories automatically if missing.
 * - Supports environment-based custom log paths via `ADAPTER_LOG_PATH`.
 * - Enables quick debugging of connectivity and configuration issues.
 *
 * ⚙️ Example Usage:
 * ```php
 * AdapterFailoverLog::record('redis', 'Connection timeout');
 * AdapterFailoverLog::record('mysql', 'Invalid credentials');
 * ```
 *
 * 📄 Example Log Output:
 * ```
 * [2025-11-08 21:15:02] [REDIS] Connection timeout
 * [2025-11-08 21:15:03] [MYSQL] Invalid credentials
 * ```
 *
 * @package Maatify\DataAdapters\Diagnostics
 */
final class AdapterFailoverLog
{
    /**
     * 🗂️ Full file path to the failover log file.
     *
     * Example: `/path/to/storage/failover.log`
     *
     * @var string
     */
    private string $file;

    /**
     * 🧩 Constructor
     *
     * Initializes the logger, creating the directory if it does not exist.
     * The log path can be overridden via:
     * - A provided `$path` argument
     * - The environment variable `ADAPTER_LOG_PATH`
     * - Defaults to `storage/failover.log` within the project
     *
     * @param string|null $path Optional custom log directory path.
     *
     * ✅ Example:
     * ```php
     * $logger = new AdapterFailoverLog('/var/log/maatify');
     * $logger->write('mongo', 'Connection refused');
     * ```
     */
    public function __construct(?string $path = null)
    {
        $logPath = $path
                   ?? ($_ENV['ADAPTER_LOG_PATH']
                       ?? getenv('ADAPTER_LOG_PATH')
                ?: __DIR__ . '/../../storage');

        $this->file = rtrim($logPath, '/') . '/failover.log';

        // 🧱 Ensure directory exists
        @mkdir(dirname($this->file), 0777, true);
    }

    /**
     * 🧠 Static helper to record a single log entry.
     *
     * Internally instantiates a logger instance using the default or configured path.
     *
     * @param string $adapter Adapter type (e.g., `redis`, `mysql`, `mongo`).
     * @param string $message Descriptive message about the event or failure.
     *
     * ✅ Example:
     * ```php
     * AdapterFailoverLog::record('redis', 'Authentication failed');
     * ```
     */
    public static function record(string $adapter, string $message): void
    {
        (new self())->write($adapter, $message);
    }

    /**
     * 🖋️ Writes a formatted log entry into the failover log file.
     *
     * Includes timestamp, adapter name (uppercase), and message.
     * Automatically appends a newline character for readability.
     *
     * @param string $adapter Adapter name (e.g., `mysql`, `mongo`).
     * @param string $message Log message describing the error or event.
     *
     * ✅ Example:
     * ```php
     * $logger = new AdapterFailoverLog();
     * $logger->write('mysql', 'Lost connection to server');
     * ```
     */
    public function write(string $adapter, string $message): void
    {
        // 🔹 Compose a structured log line
        $line = sprintf(
            '[%s] [%s] %s%s',
            date('Y-m-d H:i:s'),
            strtoupper($adapter),
            $message,
            PHP_EOL
        );

        // 🧾 Append entry to log file, suppressing warnings
        @file_put_contents($this->file, $line, FILE_APPEND);
    }
}
