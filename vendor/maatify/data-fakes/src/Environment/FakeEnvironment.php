<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-22 03:01
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-fakes
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataFakes\Environment;

use Maatify\DataFakes\Adapters\Mongo\FakeMongoAdapter;
use Maatify\DataFakes\Adapters\Redis\FakeRedisAdapter;
use Maatify\DataFakes\Fixtures\FakeFixturesLoader;
use Maatify\DataFakes\Fixtures\JsonFixtureParser;
use Maatify\DataFakes\Storage\FakeStorageLayer;

class FakeEnvironment
{
    private FakeMongoAdapter $mongoAdapter;

    public function __construct(
        private readonly FakeStorageLayer $storage = new FakeStorageLayer(),
        private readonly FakeRedisAdapter $redis = new FakeRedisAdapter(),
        private readonly ResetState $state = new ResetState(),
        ?FakeMongoAdapter $mongoAdapter = null,
        private ?FakeFixturesLoader $fixtures = null,
    ) {
        $mongoAdapter         = $mongoAdapter ?? new FakeMongoAdapter($this->storage);
        $this->fixtures       = $this->fixtures ?? new FakeFixturesLoader($this->storage, new JsonFixtureParser(), $mongoAdapter, $this->redis);
        $this->mongoAdapter   = $mongoAdapter;
    }

    public function beforeTest(): void
    {
        if ($this->state->isAutoResetEnabled()) {
            $this->reset();
        }
    }

    public function reset(): void
    {
        $this->storage->reset();
        $this->redis->reset();
    }

    public function loadFixturesFromFile(string $path): void
    {
        $this->fixtures?->loadFromFile($path);
    }

    /**
     * @param array<string, mixed> $fixtures
     */
    public function loadFixtures(array $fixtures): void
    {
        $this->fixtures?->loadFixtures($fixtures);
    }

    public function getStorage(): FakeStorageLayer
    {
        return $this->storage;
    }

    public function getRedis(): FakeRedisAdapter
    {
        return $this->redis;
    }

    public function getMongo(): FakeMongoAdapter
    {
        return $this->mongoAdapter;
    }

    public function getResetState(): ResetState
    {
        return $this->state;
    }
}
