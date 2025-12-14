<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-06
 * Time: 01:15
 * Project: maatify-common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use Maatify\Common\Security\InputSanitizer;
use Maatify\Common\Traits\SanitizesInputTrait;

// استخدام مباشر
$clean = InputSanitizer::sanitize($input, 'html');

// أو داخل كلاس يستخدم الـ Trait
class ExampleService {
    use SanitizesInputTrait;

    public function save(string $msg): void {
        $msg = $this->clean($msg, 'text');
        // ...
    }
}