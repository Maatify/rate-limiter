<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Config;

use Maatify\RateLimiter\Contracts\RateLimitActionInterface;

/**
 * In-memory provider supplying action specific configurations with a global fallback.
 */
final class InMemoryActionRateLimitConfigProvider implements ActionRateLimitConfigProviderInterface
{
    /**
     * @param array<string, ActionRateLimitConfig> $actionConfigs
     */
    public function __construct(
        private readonly GlobalRateLimitConfig $globalConfig,
        private readonly array $actionConfigs = [],
    ) {
    }

    public function getForAction(RateLimitActionInterface|string $action): ActionRateLimitConfig
    {
        $actionKey = $action instanceof RateLimitActionInterface ? $action->value() : (string) $action;

        if (array_key_exists($actionKey, $this->actionConfigs)) {
            return $this->actionConfigs[$actionKey];
        }

        return new ActionRateLimitConfig(
            $this->globalConfig->defaultLimit(),
            $this->globalConfig->defaultInterval(),
            $this->globalConfig->defaultBanTime(),
        );
    }
}
