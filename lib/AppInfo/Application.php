<?php

declare(strict_types=1);

namespace OCA\HstsManager\AppInfo;

use OCA\HstsManager\Service\HstsConfigService;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap
{
    public function __construct()
    {
        parent::__construct(HstsConfigService::APP_ID);
    }

    public function register(IRegistrationContext $context): void
    {
        // Nothing to do
    }

    public function boot(IBootContext $context): void
    {
        // Skip on CLI (e.g. occ commands) to avoid interfering with them.
        if (PHP_SAPI === 'cli') {
            return;
        }

        if (!$this->isModHeadersAvailable() && $this->isHTTPS()) {
            $context->injectFn(function (HstsConfigService $hstsConfig) {
                header($hstsConfig->buildHeaderValue());
            });
        }
    }

    private function isModHeadersAvailable()
    {
        return getenv('modHeadersAvailable') === 'true';
    }

    private function isHTTPS()
    {
        $direct = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1);
        $proxy = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https';

        return $direct || $proxy;
    }
}
