<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/psr-logger
 * @Project     maatify:psr-logger
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 10:12
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/psr-logger  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// set a writable temp folder for logs during tests
if (!getenv('LOG_PATH')) {
    putenv('LOG_PATH=' . sys_get_temp_dir() . '/maatify-psr-logger-tests');
}
