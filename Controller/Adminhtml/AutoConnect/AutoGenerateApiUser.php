<?php

namespace CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect;

use Magento\Authorization\Model\RoleFactory;
use Magento\Authorization\Model\RulesFactory;
use Magento\User\Model\UserFactory;
use Psr\Log\LoggerInterface;
use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;
use Magento\Authorization\Model\UserContextInterface;

/**
 * Class AutoGenerateApiUser
 *
 * @package CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect
 */
class AutoGenerateApiUser
{
    /** @var UserFactory  */
    private $userFactory;

    /** @var LoggerInterface  */
    private $logger;

    /** @var RoleFactory  */
    private $roleFactory;

    /** @var RulesFactory  */
    private $rulesFactory;

    /**
     * AutoGenerateApiUser constructor.
     *
     * @param UserFactory $userFactory
     * @param LoggerInterface $logger
     * @param RoleFactory $roleFactory
     * @param RulesFactory $rulesFactory
     */
    public function __construct(
        UserFactory $userFactory,
        LoggerInterface $logger,
        RoleFactory $roleFactory,
        RulesFactory $rulesFactory
    )
    {
        $this->userFactory = $userFactory;
        $this->logger = $logger;
        $this->roleFactory = $roleFactory;
        $this->rulesFactory = $rulesFactory;
    }

    /**
     * @param $password
     * @return array
     */
    public function getApiUser($password)
    {
        $userFactory = $this->userFactory->create();
        $apiUser = $userFactory->loadByUsername('sendcloud');

        if (!$apiUser) {
            $apiUser = false;
        } else {
            try {
                $apiUser->setPassword($password);
                $apiUser->save();
            } catch (\Exception $ex) {
                $this->logger->debug($ex->getMessage());
            }
        }

        $apiUserArray = [
            'username' => $apiUser->getUserName(),
            'userId' => $apiUser->getId(),
            'password' => $password
        ];

        return $apiUserArray;
    }

    /**
     * @param $password
     * @return mixed
     */
    public function createApiUser($password)
    {
        $apiUserArray = $this->generateApiUser($password);

        return $apiUserArray;
    }

    /**
     * @param $password
     * @return mixed
     */
    private function generateApiUser($password)
    {
        // TODO: Zoek een andere manier om deze gegeven te verkrijgen. Dus niet als string
        $apiUserInfo = [
            'username'  => 'sendcloud',
            'firstname' => 'rob',
            'lastname'    => 'api',
            'email'     => 'sendcloud@api.com',
            'password'  => $password,
            'interface_locale' => 'en_US',
            'is_active' => 1
        ];

        $userFactory = $this->userFactory->create();
        $apiUser = $userFactory->setData($apiUserInfo);

        $roleID = $this->generateApiRole()->getId();

        $apiUser->setRoleId($roleID);

        try{
            $apiUser->save();
        } catch (\Exception $ex) {
            $this->logger->debug($ex->getMessage());
        }

        $apiUserArray = [
            'username' => $apiUser->getUserName(),
            'userId' => $apiUser->getId(),
            'password' => $password
        ];

        return $apiUserArray;
    }

    /**
     * Create Api Role
     *
     * @return \Magento\Authorization\Model\Role
     */
    private function generateApiRole()
    {
        /**
         * Create role
         */
        $role = $this->roleFactory->create();
        $role->setName('SendCloudApi')
            ->setPid(0)
            ->setRoleType(RoleGroup::ROLE_TYPE)
            ->setUsertype(UserContextInterface::USER_TYPE_ADMIN);
        try {
            $role->save();
        } catch (\Exception $ex) {
            $this->logger->debug($ex->getMessage());
        }

        /**
         * Allowed resources for role
         */
        $resource = [
            'Magento_Sales::sales',
            'Magento_Sales::sales_operation',
            'Magento_Sales::sales_order',
            'Magento_Sales::actions',
            'Magento_Sales::actions_view',
            'Magento_Sales::actions_edit',
            'Magento_Sales::shipment'
        ];

        $this->rulesFactory->create()->setRoleId($role->getId())->setResources($resource)->saveRel();

        return $role;
    }
}