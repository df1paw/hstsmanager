<?php

declare(strict_types=1);

namespace OCA\HstsManager\Service;

use OCP\IConfig;

/**
 * Single source of truth for reading and writing the HSTS settings.
 *
 * A value set in config.php always wins over the value chosen in the admin
 * settings GUI, so a sysadmin can still lock this down via the config file
 * if desired.
 */
class HstsConfigService
{
    public const APP_ID = 'hstsmanager';

    private const DEFAULT_MAX_AGE = 15768000;

    public function __construct(private IConfig $config)
    {
    }

    public function getMaxAge(): int
    {
        return (int) $this->config->getSystemValue(
            'hsts.maxAge',
            (int) $this->config->getAppValue(self::APP_ID, 'maxAge', (string) self::DEFAULT_MAX_AGE)
        );
    }

    public function getIncludeSubDomains(): bool
    {
        return (bool) $this->config->getSystemValue(
            'hsts.includeSubDomains',
            $this->config->getAppValue(self::APP_ID, 'includeSubDomains', 'no') === 'yes'
        );
    }

    public function getPreload(): bool
    {
        return (bool) $this->config->getSystemValue(
            'hsts.preload',
            $this->config->getAppValue(self::APP_ID, 'preload', 'no') === 'yes'
        );
    }

    public function isOverriddenByConfig(): bool
    {
        return $this->config->getSystemValue('hsts.maxAge', null) !== null
            || $this->config->getSystemValue('hsts.includeSubDomains', null) !== null
            || $this->config->getSystemValue('hsts.preload', null) !== null;
    }

    public function buildHeaderValue(): string
    {
        $maxAge = $this->getMaxAge();
        $includeSubDomains = $this->getIncludeSubDomains() ? '; includeSubDomains' : '';
        $preload = $this->getPreload() ? '; preload' : '';

        return "Strict-Transport-Security: max-age={$maxAge}{$includeSubDomains}{$preload}";
    }

    public function saveGuiValues(int $maxAge, bool $includeSubDomains, bool $preload): void
    {
        $this->config->setAppValue(self::APP_ID, 'maxAge', (string) $maxAge);
        $this->config->setAppValue(self::APP_ID, 'includeSubDomains', $includeSubDomains ? 'yes' : 'no');
        $this->config->setAppValue(self::APP_ID, 'preload', $preload ? 'yes' : 'no');
    }
}
