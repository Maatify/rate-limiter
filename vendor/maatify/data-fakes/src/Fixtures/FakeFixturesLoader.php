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

namespace Maatify\DataFakes\Fixtures;

use Maatify\DataFakes\Adapters\Mongo\FakeMongoAdapter;
use Maatify\DataFakes\Adapters\Redis\FakeRedisAdapter;
use Maatify\DataFakes\Storage\FakeStorageLayer;

class FakeFixturesLoader
{
    public function __construct(
        private readonly FakeStorageLayer $storage,
        private readonly JsonFixtureParser $parser,
        private readonly ?FakeMongoAdapter $mongoAdapter = null,
        private readonly ?FakeRedisAdapter $redisAdapter = null
    ) {
    }

    public function loadFromFile(string $path): void
    {
        $fixtures = $this->parser->parseFile($path);

        $this->loadFixtures($fixtures);
    }

    /**
     * @param array<string, mixed> $fixtures
     */
    public function loadFixtures(array $fixtures): void
    {
        $tables      = $this->normalizeTableFixtures($fixtures['mysql'] ?? $fixtures['dbal'] ?? []);
        $collections = $this->normalizeTableFixtures($fixtures['mongo'] ?? []);
        $redis       = $this->normalizeRedisFixtures($fixtures['redis'] ?? []);

        $this->loadSqlFixtures($tables);
        $this->loadMongoFixtures($collections);
        $this->loadRedisFixtures($redis);
    }

    /**
     * @param array<string, array<int|string, array<string, mixed>>> $tables
     */
    private function loadSqlFixtures(array $tables): void
    {
        foreach ($tables as $table => $rows) {
            $this->storage->writeTable($table, $rows);
        }
    }

    /**
     * @param array<string, array<int|string, array<string, mixed>>> $collections
     */
    private function loadMongoFixtures(array $collections): void
    {
        if ($collections === [] || $this->mongoAdapter === null) {
            return;
        }

        $mongoStorage = $this->mongoAdapter->getDriver();

        foreach ($collections as $collection => $rows) {
            $mongoStorage->writeTable($collection, $rows);
        }
    }

    /**
     * @param array{
     *     strings?: array<string, mixed>,
     *     hashes?: array<string, array<string, mixed>>,
     *     lists?: array<string, array<int, mixed>>,
     *     counters?: array<string, int|float|string|bool>
     * } $redis
     */
    private function loadRedisFixtures(array $redis): void
    {
        if ($redis === [] || $this->redisAdapter === null) {
            return;
        }

        $strings  = $redis['strings'] ?? [];
        $hashes   = $redis['hashes'] ?? [];
        $lists    = $redis['lists'] ?? [];
        $counters = $redis['counters'] ?? [];

        foreach ($strings as $key => $value) {
            $this->redisAdapter->set((string) $key, $value);
        }

        foreach ($hashes as $key => $fields) {
            foreach ($fields as $field => $value) {
                $this->redisAdapter->hset((string) $key, (string) $field, $value);
            }
        }

        foreach ($lists as $key => $values) {
            foreach ($values as $value) {
                $this->redisAdapter->rpush((string) $key, $value);
            }
        }

        foreach ($counters as $key => $value) {
            $this->redisAdapter->set((string) $key, $value);
        }
    }

    /**
     * @param mixed $raw
     * @return array<string, array<int|string, array<string, mixed>>>
     */
    private function normalizeTableFixtures(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $normalized = [];

        foreach ($raw as $table => $rows) {
            if (! is_array($rows)) {
                continue;
            }

            $tableKey = (string) $table;
            $normalized[$tableKey] = [];

            foreach ($rows as $id => $row) {
                if (! is_array($row)) {
                    continue;
                }

                $normalized[$tableKey][is_int($id) ? $id : (string) $id] = $row;
            }
        }

        return $normalized;
    }

    /**
     * @param mixed $raw
     * @return array{
     *     strings?: array<string, mixed>,
     *     hashes?: array<string, array<string, mixed>>,
     *     lists?: array<string, array<int, mixed>>,
     *     counters?: array<string, int|float|string|bool>
     * }
     */
    private function normalizeRedisFixtures(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $strings  = is_array($raw['strings'] ?? null) ? $raw['strings'] : [];
        $hashes   = is_array($raw['hashes'] ?? null) ? $raw['hashes'] : [];
        $lists    = is_array($raw['lists'] ?? null) ? $raw['lists'] : [];
        $counters = is_array($raw['counters'] ?? null) ? $raw['counters'] : [];

        return [
            'strings' => $strings,
            'hashes' => $hashes,
            'lists' => $lists,
            'counters' => $counters,
        ];
    }
}
