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

use Dotenv\Dotenv;

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

// 👇 Load `.env.local` first (highest priority), then fallbacks
if (file_exists($envPath . '/.env.local')) {
    Dotenv::createImmutable($envPath, '.env.local')->load();
    echo "✅ Loaded .env.local (private environment)\n";
} elseif (file_exists($envPath . '/.env.testing')) {
    Dotenv::createImmutable($envPath, '.env.testing')->load();
    echo "✅ Loaded .env.testing\n";
} elseif (file_exists($envPath . '/.env.example')) {
    Dotenv::createImmutable($envPath, '.env.example')->load();
    echo "✅ Loaded .env.example (fallback)\n";
} else {
    echo "⚠️ No environment file found.\n";
}

// 🧪 Display active environment for debugging
echo "🧪 Environment: " . ($_ENV['APP_ENV'] ?? 'unknown') . "\n";
