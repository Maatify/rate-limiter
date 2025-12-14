<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 23:26
 * Project: maatify-common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use Maatify\Common\Lock\HybridLockManager;
use Maatify\Common\Lock\LockModeEnum;

$lock = new HybridLockManager('email_cron', LockModeEnum::EXECUTION);
if (! $lock->acquire()) exit;
//sendEmails();
$lock->release();