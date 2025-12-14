<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/psr-logger
 * @Project     maatify:psr-logger
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 10:26
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/psr-logger  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->ignoreVCS(true)
    ->ignoreDotFiles(true);

return (new Config())
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setFinder($finder)
    ->setRules([
        '@PSR12'                             => true,
        'array_syntax'                       => ['syntax' => 'short'],
        'single_quote'                       => true,
        'no_unused_imports'                  => true,
        'no_trailing_whitespace'             => true,
        'no_blank_lines_after_class_opening' => true,
        'no_extra_blank_lines'               => ['tokens' => ['extra']],
        'concat_space'                       => ['spacing' => 'one'],
        'ordered_imports'                    => ['sort_algorithm' => 'alpha'],
        'phpdoc_align'                       => ['align' => 'vertical'],
        'phpdoc_indent'                      => true,
        'phpdoc_scalar'                      => true,
        'phpdoc_trim'                        => true,
        'phpdoc_var_without_name'            => false,
        'trailing_comma_in_multiline'        => ['elements' => ['arrays']],
        'declare_strict_types'               => true,
    ]);
