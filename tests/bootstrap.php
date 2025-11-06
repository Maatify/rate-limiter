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

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * ⚙️ Local Test Environment Bootstrap
 *
 * 🧩 Purpose:
 * This bootstrap file initializes the **maatify/rate-limiter** testing environment
 * by autoloading all Composer dependencies. It is executed before running
 * PHPUnit test suites to ensure proper class loading and configuration.
 *
 * 🎯 Responsibilities:
 * - Autoload project dependencies.
 * - Prepare environment for PHPUnit execution.
 * - Confirm setup through console output.
 *
 * ✅ Example usage:
 * ```bash
 * php tests/bootstrap.php
 * # Output: ✅ Local test environment loaded.
 * ```
 */

// 🧠 Notify developer that the test environment is successfully initialized
echo "✅ Local test environment loaded.\n";
