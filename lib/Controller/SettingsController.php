<?php

declare(strict_types=1);

namespace OCA\HstsManager\Controller;

use OCA\HstsManager\Service\HstsConfigService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\IGroupManager;
use OCP\IRequest;
use OCP\IUserSession;

class SettingsController extends Controller
{
    public function __construct(
        string $appName,
        IRequest $request,
        private HstsConfigService $hstsConfig,
        private IUserSession $userSession,
        private IGroupManager $groupManager
    ) {
        parent::__construct($appName, $request);
    }

    public function update(int $maxAge, bool $includeSubDomains, bool $preload): DataResponse
    {
        $user = $this->userSession->getUser();

        if ($user === null || !$this->groupManager->isAdmin($user->getUID())) {
            return new DataResponse(['message' => 'Forbidden'], Http::STATUS_FORBIDDEN);
        }

        if ($maxAge < 0) {
            return new DataResponse(['message' => 'maxAge must not be negative'], Http::STATUS_BAD_REQUEST);
        }

        $this->hstsConfig->saveGuiValues($maxAge, $includeSubDomains, $preload);

        return new DataResponse(['status' => 'success']);
    }
}
