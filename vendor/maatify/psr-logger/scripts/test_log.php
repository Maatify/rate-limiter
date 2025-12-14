<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 16:28
 * Project: maatify-psr-logger
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);


use Dotenv\Dotenv;

/**
 * @return string
 */
function getDirname(): string
{
    $vendorPos = strpos(__DIR__, '/vendor/');

    if ($vendorPos !== false) {
        // 🧩 Library used inside a project
        $baseDir = dirname(__DIR__, 3);
    } else {
        // 🧪 Standalone development mode
        $baseDir = dirname(__DIR__);
    }

    // Autoload dependencies (project or library scope)
    $autoloadPath = file_exists($baseDir . '/vendor/autoload.php')
        ? $baseDir . '/vendor/autoload.php'
        : __DIR__ . '/../vendor/autoload.php';

    require $autoloadPath;

    return $baseDir;
}

$baseDir = getDirname();


// Load environment variables (if .env exists)
if (file_exists($baseDir . '/.env')) {
    Dotenv::createImmutable($baseDir)->load();
}
use Maatify\PsrLogger\Traits\LoggerContextTrait;
use Psr\Log\LogLevel;

class UserService
{
    use LoggerContextTrait;

    public function __construct()
    {
        $this->initLogger(); // auto: logs/UserService.log
        $this->logger->info('User service initialized');
        $this->logger->log(level: LogLevel::WARNING, message: 'test message', context: ['id'=>1]);
    }
}


new UserService();
