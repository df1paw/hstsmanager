<?php

declare(strict_types=1);

namespace OCA\HstsManager\Settings;

use OCA\HstsManager\Service\HstsConfigService;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;
use OCP\Util;

class Admin implements ISettings
{
    public function __construct(private HstsConfigService $hstsConfig)
    {
    }

    public function getForm(): TemplateResponse
    {
        $parameters = [
            'maxAge' => $this->hstsConfig->getMaxAge(),
            'includeSubDomains' => $this->hstsConfig->getIncludeSubDomains(),
            'preload' => $this->hstsConfig->getPreload(),
            'overriddenByConfig' => $this->hstsConfig->isOverriddenByConfig(),
        ];

        Util::addScript(HstsConfigService::APP_ID, 'admin');
        Util::addStyle(HstsConfigService::APP_ID, 'admin');

        return new TemplateResponse(HstsConfigService::APP_ID, 'admin', $parameters, '');
    }

    public function getSection(): string
    {
        return 'security';
    }

    public function getPriority(): int
    {
        return 50;
    }
}
