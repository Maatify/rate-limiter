<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 15:31
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

declare(strict_types=1);

namespace Maatify\Bootstrap\Core;

use Dotenv\Dotenv;
use Exception;

/**
 * ⚙️ **Class EnvironmentLoader**
 *
 * 🎯 **Purpose:**
 * Provides a robust, unified mechanism for loading environment variables across
 * all **Maatify** libraries and applications, ensuring consistency, safety, and
 * predictable configuration behavior.
 *
 * 🧠 **Core Responsibilities:**
 * - Load environment variables from the correct file (`.env.local`, `.env.testing`, `.env`, or `.env.example`).
 * - Prevent overriding system or CI-defined environment variables (immutable mode).
 * - Apply the application timezone automatically after loading.
 * - Throw explicit errors when no `.env` file is found.
 *
 * 🧩 **Priority Load Order:**
 * 1️⃣ `.env.local` — local developer overrides
 * 2️⃣ `.env.testing` — test environment variables
 * 3️⃣ `.env` — default environment file
 * 4️⃣ `.env.example` — fallback for defaults or CI builds
 *
 * ✅ **Example Usage:**
 * ```php
 * use Maatify\Bootstrap\Core\EnvironmentLoader;
 *
 * // Initialize and load environment variables
 * $env = new EnvironmentLoader(__DIR__ . '/../');
 * $env->load();
 *
 * echo 'Environment: ' . ($_ENV['APP_ENV'] ?? 'unknown');
 * ```
 *
 * @package Maatify\Bootstrap\Core
 */
final class EnvironmentLoader
{
    /**
     * 📂 **Base Directory Path**
     *
     * The directory that contains the environment files. Typically the project root.
     *
     * @var string
     */
    public function __construct(private readonly string $basePath)
    {
    }

    /**
     * 🚀 **Load Environment Variables**
     *
     * Loads environment variables based on the Maatify priority rules.
     * The first matching file among `.env.local`, `.env.testing`, `.env`, or `.env.example`
     * is loaded using **immutable mode**, meaning system-level environment variables
     * (e.g., from Docker, CI/CD, or OS) will not be overridden.
     *
     * 🧠 **Post-Load Behavior:**
     * - Ensures system-defined variables remain intact.
     * - Automatically applies timezone via `date_default_timezone_set()`.
     * - Throws clear exception if no `.env` file exists.
     *
     * @throws Exception When no valid environment file is found.
     *
     * @return void
     */
    public function load(): void
    {
        // 🔍 Priority order for environment files
        $envFiles = ['.env.local', '.env.testing', '.env', '.env.example'];
        $loaded   = false;

        foreach ($envFiles as $file) {
            $path = $this->basePath . DIRECTORY_SEPARATOR . $file;

            // ✅ Load first available file, stop after success
            if (is_file($path)) {
                // 🧩 Load in immutable mode — prevents overriding pre-defined variables
                Dotenv::createImmutable($this->basePath, $file)->load();
                $loaded = true;
                break;
            }
        }

        // 🚫 Throw if no valid .env file found
        if (! $loaded) {
            throw new Exception('No .env file found in ' . $this->basePath);
        }

        // 🕒 Apply timezone setting (default: Africa/Cairo)
        $timezone = $_ENV['APP_TIMEZONE']
                    ?? $_SERVER['APP_TIMEZONE']
                       ?? 'Africa/Cairo';

        date_default_timezone_set($timezone);
    }
}
