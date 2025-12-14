<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-17 10:30
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Core\Parser;

/**
 * 🧩 **MysqlDsnParser**
 *
 * 🎯 Responsible for parsing both **Doctrine-style** and **PDO-style** MySQL DSN strings
 * into a normalized associative array.
 *
 * Supported formats:
 * - `mysql://user:pass@host:3306/database`
 * - `mysql:host=127.0.0.1;port=3306;dbname=test`
 *
 * This utility ensures consistent extraction of host, port, database, username,
 * and password regardless of DSN syntax.
 *
 * ---
 *
 * ### ✔️ Example Usage
 * ```php
 * use Maatify\DataAdapters\Core\Parser\MysqlDsnParser;
 *
 * $dsn = "mysql://user:secret@localhost:3306/mydb";
 * $parsed = MysqlDsnParser::parse($dsn);
 *
 * // Result:
 * // [
 * //   'host' => 'localhost',
 * //   'port' => '3306',
 * //   'user' => 'user',
 * //   'pass' => 'secret',
 * //   'database' => 'mydb',
 * // ]
 * ```
 *
 * ---
 *
 * @package Maatify\DataAdapters\Core\Parser
 */
final class MysqlDsnParser
{
    /**
     * 🧠 **Parse MySQL DSN (PDO or Doctrine-style)**
     *
     * Extracts connection components from supported DSN formats and returns them
     * as a clean, normalized array.
     *
     * ---
     *
     * ### 🔹 Supported DSN Inputs
     * - `mysql://user:pass@host:port/dbname` (Doctrine style)
     * - `mysql:host=127.0.0.1;port=3306;dbname=test` (PDO style)
     *
     * ---
     *
     * @param string $dsn
     *     The raw DSN string provided by configuration.
     *
     * @return array{
     *     host?: string|null,
     *     port?: string|int|null,
     *     user?: string|null,
     *     pass?: string|null,
     *     database?: string|null,
     *     options?: mixed
     * }
     *     A normalized array of parsed properties. Missing components are returned as `null`.
     *
     * ---
     *
     * ### ✔️ Example
     * ```php
     * $config = MysqlDsnParser::parse("mysql:host=db;port=3306;dbname=shop");
     * ```
     */
    public static function parse(string $dsn): array
    {
        // 🧹 Remove query parameters while keeping only the database segment.
        // preg_replace may return string|null
        $clean = preg_replace('#/([A-Za-z0-9_\-]+)\?.*$#', '/$1', $dsn);
        $clean = is_string($clean) ? $clean : $dsn;

        // ---------------------------------------------
        // 🎯 Doctrine style:
        // Format: mysql://user:pass@host:port/db
        // ---------------------------------------------
        if (str_starts_with($clean, 'mysql://')) {

            $matches = [];

            // Extract components using a named-regex.
            $ok = preg_match(
                '#^mysql://(?P<user>[^:/]+):(?P<pass>.+)@(?P<host>[^:/]+):(?P<port>[0-9]+)/(?P<db>[A-Za-z0-9_\-]+)$#',
                $clean,
                $matches
            );

            // If it does not match, return empty array.
            if ($ok !== 1) {
                return [];
            }

            // Return normalized structure
            return [
                'host'     => $matches['host'],
                'port'     => $matches['port'],
                'user'     => $matches['user'],
                'pass'     => $matches['pass'],
                'database' => $matches['db'],
            ];
        }

        // ---------------------------------------------
        // 🎯 PDO DSN:
        // Format: mysql:host=...;port=...;dbname=...
        // ---------------------------------------------

        // Ensure $clean is a string before str_replace()
        $clean = str_replace('mysql:', '', (string)$clean);

        // Break into key=value pairs
        $pairs = explode(';', $clean);

        $out = [];
        foreach ($pairs as $pair) {
            if (!str_contains($pair, '=')) {
                continue; // Skip invalid fragments
            }

            [$key, $value] = explode('=', $pair, 2);

            $key   = strtolower(trim($key));
            $value = trim($value);

            if ($key !== '') {
                $out[$key] = $value; // Normalize into associative array
            }
        }

        // Final normalized return structure for PDO DSN
        return [
            'host'     => $out['host']   ?? null,
            'port'     => $out['port']   ?? null,
            'database' => $out['dbname'] ?? null,
        ];
    }
}
