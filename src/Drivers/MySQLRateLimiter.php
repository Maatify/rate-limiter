<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Drivers;

use Maatify\RateLimiter\Config\ActionRateLimitConfigProviderInterface;
use Maatify\RateLimiter\Contracts\BackoffPolicyInterface;
use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use PDO;

final class MySQLRateLimiter implements RateLimiterInterface
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly ActionRateLimitConfigProviderInterface $configProvider,
        private readonly ?BackoffPolicyInterface $backoffPolicy = null,
    ) {
    }

    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $config = $this->configProvider->getForAction($action);
        $compositeKey = "{$platform->value()}_{$action->value()}_{$key}";

        $stmt = $this->pdo->prepare('
            INSERT INTO ip_rate_limits (key_name, count, last_attempt)
            VALUES (:key, 1, NOW())
            ON DUPLICATE KEY UPDATE count = count + 1, last_attempt = NOW()
        ');
        if ($stmt) {
            $stmt->execute(['key' => $compositeKey]);
        }

        $stmt = $this->pdo->prepare('SELECT count FROM ip_rate_limits WHERE key_name = ?');
        $count = 0;
        if ($stmt) {
            $stmt->execute([$compositeKey]);
            $count = (int) $stmt->fetchColumn();
        }

        if ($count > $config->limit()) {
            throw new TooManyRequestsException(
                'Rate limit exceeded',
                429,
                new RateLimitStatusDTO(
                    limit: $config->limit(),
                    remaining: 0,
                    resetAfter: $config->interval(),
                    retryAfter: $config->interval(),
                    blocked: true,
                )
            );
        }

        return new RateLimitStatusDTO(
            limit: $config->limit(),
            remaining: max(0, $config->limit() - $count),
            resetAfter: $config->interval(),
            blocked: $count >= $config->limit(),
        );
    }

    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        $compositeKey = "{$platform->value()}_{$action->value()}_{$key}";
        $stmt = $this->pdo->prepare('DELETE FROM ip_rate_limits WHERE key_name = ?');
        if ($stmt) {
            return $stmt->execute([$compositeKey]);
        }

        return false;
    }

    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $config = $this->configProvider->getForAction($action);
        $compositeKey = "{$platform->value()}_{$action->value()}_{$key}";

        $stmt = $this->pdo->prepare('SELECT count FROM ip_rate_limits WHERE key_name = ?');
        $count = 0;
        if ($stmt) {
            $stmt->execute([$compositeKey]);
            $count = (int) $stmt->fetchColumn();
        }

        $remaining = max(0, $config->limit() - $count);

        return new RateLimitStatusDTO(
            limit: $config->limit(),
            remaining: $remaining,
            resetAfter: $config->interval(),
            blocked: $remaining <= 0,
        );
    }
}
