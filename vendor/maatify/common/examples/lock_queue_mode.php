<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 23:27
 * Project: maatify-common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use Maatify\Common\Lock\HybridLockManager;
use Maatify\Common\Lock\LockModeEnum;

$lock = new HybridLockManager("purchase_user_{$userId}", LockModeEnum::QUEUE);
$lock->waitAndAcquire();
processPurchase();
$lock->release();


////
$lock = new HybridLockManager("order_{$orderId}", LockModeEnum::QUEUE);
$lock->run(function () {
    processOrder();
});