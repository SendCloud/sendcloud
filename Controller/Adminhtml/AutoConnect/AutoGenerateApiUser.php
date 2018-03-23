<?php

namespace CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect;

use Magento\User\Model\UserFactory;
use Psr\Log\LoggerInterface;

/**
 * Class AutoGenerateApiUser
 *
 * @package CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect
 */
class AutoGenerateApiUser
{
    private $apiUsername = 'sendcloud';
    private $userFactory;
    private $logger;

    /**
     * AutoGenerateApiUser constructor.
     *
     * @param UserFactory     $userFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserFactory $userFactory,
        LoggerInterface $logger
    )
    {
        $this->userFactory = $userFactory;
        $this->logger = $logger;
    }

    /**
     * @return $this|bool
     */
    public function getApiUser()
    {
        $userFactory = $this->userFactory->create();
        $apiUser = $userFactory->loadByUsername($this->apiUsername);

        if (!$apiUser) {
            $apiUser = false;
        }

        return $apiUser;
    }

    /**
     * @return mixed
     */
    public function createApiUser()
    {
        $apiUser = $this->generateApiUser();

        return $apiUser;
    }

    /**
     * @return mixed
     */
    private function generateApiUser()
    {
        // TODO: Zoek een andere manier om deze gegeven te verkrijgen. Dus niet als string
        $apiUserInfo = [
            'username'  => $this->apiUsername,
            'firstname' => 'rob',
            'lastname'    => 'api',
            'email'     => 'sendcloud@api.com',
            'password'  =>'welkom123',
            'interface_locale' => 'en_US',
            'is_active' => 1
        ];

        $userFactory = $this->userFactory->create();
        $apiUser = $userFactory->setData($apiUserInfo);
        $apiUser->setRoleId(1);

        try{
            $apiUser->save();
        } catch (\Exception $ex) {
            $this->logger->debug($ex->getMessage());
        }

        return $apiUser;
    }


}