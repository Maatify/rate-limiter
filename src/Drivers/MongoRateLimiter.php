<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Drivers;

use Maatify\RateLimiter\Config\ActionRateLimitConfigProviderInterface;
use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;

final class MongoRateLimiter implements RateLimiterInterface
{
    public function __construct(
        private readonly Collection $collection,
        private readonly ActionRateLimitConfigProviderInterface $configProvider,
    ) {
    }

    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $config = $this->configProvider->getForAction($action);
        $now = new UTCDateTime();
        $docKey = "{$platform->value()}_{$action->value()}_{$key}";

        $this->collection->updateOne(
            ['_id' => $docKey],
            [
                '$inc' => ['count' => 1],
                '$setOnInsert' => ['created_at' => $now],
                '$set' => ['last_attempt' => $now],
            ],
            ['upsert' => true]
        );

        $record = (array) $this->collection->findOne(['_id' => $docKey]);
        $count = isset($record['count']) && is_numeric($record['count']) ? (int) $record['count'] : 1;

        $remaining = $config->limit() - $count;

        if ($count > $config->limit()) {
            throw new TooManyRequestsException(
                'Rate limit exceeded',
                429,
                new RateLimitStatusDTO(
                    limit: $config->limit(),
                    remaining: $remaining,
                    resetAfter: $config->interval(),
                    retryAfter: $config->interval(),
                    blocked: true,
                    source: 'action',
                )
            );
        }

        return new RateLimitStatusDTO(
            limit: $config->limit(),
            remaining: $remaining,
            resetAfter: $config->interval(),
            blocked: $remaining <= 0,
            source: 'action',
        );
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        $docId = "{$platform->value()}_{$action->value()}_{$key}";

        return (bool) $this->collection
            ->deleteOne(['_id' => $docId])
            ->getDeletedCount();
    }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $config = $this->configProvider->getForAction($action);
        $docId = "{$platform->value()}_{$action->value()}_{$key}";
        $record = (array) $this->collection->findOne(['_id' => $docId]);
        $count = isset($record['count']) && is_numeric($record['count']) ? (int) $record['count'] : 0;

        $remaining = $config->limit() - $count;

        return new RateLimitStatusDTO(
            limit: $config->limit(),
            remaining: $remaining,
            resetAfter: $config->interval(),
            blocked: $remaining <= 0,
            source: 'action',
        );
    }
}
