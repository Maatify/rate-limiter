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

namespace Maatify\DataFakes\Tests\Simulation;

use Maatify\DataFakes\Adapters\Redis\FakeRedisAdapter;
use Maatify\DataFakes\Simulation\ErrorSimulator;
use Maatify\DataFakes\Simulation\FailureScenario;
use Maatify\DataFakes\Simulation\LatencySimulator;
use Maatify\DataFakes\Storage\FakeStorageLayer;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;

final class ErrorSimulatorTest extends TestCase
{
    public function testScenarioAlwaysTriggers(): void
    {
        $randomizer = new Randomizer(new Mt19937(1234));
        $simulator  = new ErrorSimulator($randomizer);
        $scenario   = new FailureScenario('forced', 1.0, \RuntimeException::class, 'boom');
        $simulator->registerScenario('redis.get', $scenario);

        $adapter = new FakeRedisAdapter();
        $adapter->setErrorSimulator($simulator);

        $this->expectExceptionMessage('boom');
        $adapter->get('anything');
    }

    public function testScenarioDoesNotTriggerWhenProbabilityZero(): void
    {
        $simulator = new ErrorSimulator(new Randomizer(new Mt19937(1)));
        $scenario  = new FailureScenario('never', 0.0);
        $simulator->registerScenario('redis.get', $scenario);

        $adapter = new FakeRedisAdapter();
        $adapter->setErrorSimulator($simulator);

        $adapter->set('key', 'value');
        self::assertSame('value', $adapter->get('key'));
    }

    public function testLatencySimulatorDelaysStorageOperations(): void
    {
        $latency = new LatencySimulator();
        $latency->setDefaultLatency(5);

        $storage = new FakeStorageLayer();
        $storage->setLatencySimulator($latency);

        $start = microtime(true);
        $storage->write('users', ['name' => 'slow']);
        $elapsedMs = (microtime(true) - $start) * 1000;

        self::assertGreaterThanOrEqual(4.0, $elapsedMs);
    }
}
