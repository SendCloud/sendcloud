<?php

namespace SendCloud\SendCloud\Plugin\Model;

use Magento\Integration\Api\AdminTokenServiceInterface;
use Magento\TwoFactorAuth\Model\AdminAccessTokenService;

class AdminAccessTokenServicePlugin
{
    /**
     * @var AdminTokenServiceInterface
     */
    private $adminTokenService;

    /**
     * AdminAccessTokenServicePlugin constructor.
     * @param AdminTokenServiceInterface $adminTokenService
     */
    public function __construct(
        AdminTokenServiceInterface $adminTokenService
    )
    {
        $this->adminTokenService = $adminTokenService;
    }

    /**
     * Bypass Two factor authentication for Sendcloud
     *
     * @param AdminAccessTokenService $subject
     * @param callable $proceed
     * @param $username
     * @param $password
     * @return string
     * @throws \Magento\Framework\Exception\AuthenticationException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundCreateAdminAccessToken(AdminAccessTokenService $subject, callable $proceed, $username, $password)
    {
        if ($username === 'sendcloud') {
            return $this->adminTokenService->createAdminAccessToken($username, $password);
        }

        $proceed($username, $password);
    }
}
