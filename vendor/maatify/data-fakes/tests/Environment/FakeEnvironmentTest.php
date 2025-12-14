<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-22 03:01
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Tests\Environment;

use Maatify\DataFakes\Environment\FakeEnvironment;
use Maatify\DataFakes\Environment\ResetState;
use PHPUnit\Framework\TestCase;

final class FakeEnvironmentTest extends TestCase
{
    public function testAutoResetClearsStorageAndRedis(): void
    {
        $environment = new FakeEnvironment(state: new ResetState(true));
        $environment->getStorage()->write('users', ['name' => 'Alice']);
        $environment->getRedis()->set('token', 'abc');

        $environment->beforeTest();

        self::assertSame([], $environment->getStorage()->listAll('users'));
        self::assertNull($environment->getRedis()->get('token'));
    }

    public function testLoadFixturesFromFile(): void
    {
        $environment = new FakeEnvironment(state: new ResetState(false));
        $environment->loadFixturesFromFile(__DIR__ . '/../Fixtures/sample-fixtures.json');

        $rows = $environment->getStorage()->listAll('users');
        self::assertCount(2, $rows);

        $mongoRows = $environment->getMongo()->find('products', ['price' => ['$gt' => 120]]);
        self::assertCount(1, $mongoRows);

        self::assertSame('abc', $environment->getRedis()->get('token'));
    }
}
