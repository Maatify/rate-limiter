<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-06
 * Time: 21:17
 * Project: maatify/rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use Maatify\Bootstrap\Core\EnvironmentLoader;

require_once __DIR__ . '/../vendor/autoload.php';

$envPath = dirname(__DIR__);

/**
 * ⚙️ Environment Loader Script
 *
 * 🧩 Purpose:
 * Loads environment variables for local development, testing, or fallback configurations.
 * This script ensures the proper `.env` file is loaded based on priority:
 * 1️⃣ `.env.local` → Preferred local environment (private or developer setup).
 * 2️⃣ `.env.testing` → Used for CI or unit testing environments.
 * 3️⃣ `.env.example` → Default fallback for missing configurations.
 *
 * ✅ Example:
 * ```bash
 * php tests/bootstrap.php
 * ```
 *
 * 🧠 Behavior:
 * - Automatically detects and loads the first available `.env` file.
 * - Outputs a message indicating which environment file was used.
 * - Displays the detected `APP_ENV` variable.
 */

// ------------------------------------------------------------
// 1) Load composer autoload
// ------------------------------------------------------------
$autoload = dirname(__DIR__) . '/vendor/autoload.php';

if (! file_exists($autoload)) {
    fwrite(STDERR, "❌ Autoload not found: $autoload" . PHP_EOL);
    exit(1);
}

require_once $autoload;

// ------------------------------------------------------------
// 2) Load environment variables (testing/default)
// ------------------------------------------------------------
$loader = new EnvironmentLoader(dirname(__DIR__));
$loader->load();

// ------------------------------------------------------------
// 3) Normalize environment value for PHPStan level=max
// ------------------------------------------------------------
$envRaw = $_ENV['APP_ENV'] ?? 'unknown';

/*
 * PHPStan Safe Normalization
 * mixed → string (safe)
 */
$envString = is_scalar($envRaw)
    ? (string) $envRaw
    : 'unknown';

// ------------------------------------------------------------
// 4) Display current environment (deterministic, safe)
// ------------------------------------------------------------
echo '🧪 Environment: ' . $envString . PHP_EOL;

// ------------------------------------------------------------
// 5) Optional: Disable output buffering for CI
// ------------------------------------------------------------
if (function_exists('ini_set')) {
    ini_set('output_buffering', 'off');
    ini_set('implicit_flush', '1');
}
