<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Drivers;

use Maatify\RateLimiter\Config\ActionRateLimitConfigProviderInterface;
use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Redis;

final class RedisRateLimiter implements RateLimiterInterface
{
    public function __construct(
        private readonly Redis $redis,
        private readonly ActionRateLimitConfigProviderInterface $configProvider,
    ) {
    }

    private function key(string $key, RateLimitActionInterface $action, PlatformInterface $platform): string
    {
        return sprintf('rate:%s:%s:%s', $platform->value(), $action->value(), $key);
    }

    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $config = $this->configProvider->getForAction($action);
        $docKey = $this->key($key, $action, $platform);

        $limit = $config->limit();
        $interval = $config->interval();

        $current = (int) $this->redis->incr($docKey);

        if ($current === 1) {
            $this->redis->expire($docKey, $interval);
        }

        $ttl = (int) $this->redis->ttl($docKey);
        if ($ttl <= 0) {
            $ttl = $interval;
        }

        $remaining = $limit - $current;

        if ($current > $limit) {
            throw new TooManyRequestsException(
                'Rate limit exceeded',
                429,
                new RateLimitStatusDTO(
                    limit: $limit,
                    remaining: $remaining,
                    resetAfter: $ttl,
                    retryAfter: $ttl,
                    blocked: true,
                    source: 'action',
                )
            );
        }

        return new RateLimitStatusDTO(
            limit: $limit,
            remaining: $remaining,
            resetAfter: $ttl,
            blocked: $remaining <= 0,
            source: 'action',
        );
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        return (bool) $this->redis->del($this->key($key, $action, $platform));
    }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $config = $this->configProvider->getForAction($action);
        $docKey = $this->key($key, $action, $platform);

        $value = $this->redis->get($docKey);
        $count = is_numeric($value) ? (int) $value : 0;

        $ttl = (int) $this->redis->ttl($docKey);
        if ($ttl <= 0) {
            $ttl = $config->interval();
        }

        $remaining = $config->limit() - $count;

        return new RateLimitStatusDTO(
            limit: $config->limit(),
            remaining: $remaining,
            resetAfter: $ttl,
            blocked: $remaining <= 0,
            source: 'action',
        );
    }
}
