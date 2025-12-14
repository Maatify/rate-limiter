<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim
 * @since       2025-11-08 20:08
 * @link        https://github.com/Maatify/data-adapters
 */

declare(strict_types=1);

use Maatify\Bootstrap\Core\EnvironmentLoader;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * 🧩 **Environment Bootstrapping Script**
 *
 * 🎯 **Purpose:**
 * Provides a minimal executable test script to validate environment
 * loading functionality via {@see EnvironmentLoader}.
 *
 * 🧠 **Behavior:**
 * - Loads environment variables from the `.env` file located at the project root.
 * - Ensures that configuration values are correctly parsed and stored in `$_ENV`.
 * - Prints the currently active application environment (APP_ENV).
 *
 * ✅ **Usage:**
 * ```bash
 * php tests/test_environment_loader.php
 * ```
 * Expected output:
 * ```
 * 🧪 Environment: development
 * ```
 */

$loader = new EnvironmentLoader(dirname(__DIR__));
$loader->load();

// 🧪 Display active environment for verification
echo '🧪 Environment: ' . ($_ENV['APP_ENV'] ?? 'unknown') . PHP_EOL;
