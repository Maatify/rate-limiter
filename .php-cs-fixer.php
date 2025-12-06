<?php

declare(strict_types=1);

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/rate-limiter
 * @Project     maatify:rate-limiter
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-12-06 22:57:00
 * @see         https://www.maatify.dev
 * @link        https://github.com/Maatify/rate-limiter
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'no_extra_blank_lines' => true,
        'single_quote' => true
    ])
    ->setFinder($finder);
