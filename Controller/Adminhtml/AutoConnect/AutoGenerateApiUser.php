<?php

namespace SendCloud\SendCloud\Controller\Adminhtml\AutoConnect;

use SendCloud\SendCloud\Logger\SendCloudLogger;
use Magento\Authorization\Model\RoleFactory;
use Magento\Authorization\Model\RulesFactory;
use Magento\Setup\Exception;
use Magento\User\Model\UserFactory;
use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;
use Magento\Authorization\Model\UserContextInterface;

/**
 * Class AutoGenerateApiUser
 *
 * @package SendCloud\SendCloud\Controller\Adminhtml\AutoConnect
 */
class AutoGenerateApiUser
{
    /** @var UserFactory  */
    private $userFactory;

    private $logger;

    /**
     * Allowed resources for role
     */
    private $resource = [
        'Magento_Sales::sales',
        'Magento_Sales::sales_operation',
        'Magento_Sales::sales_order',
        'Magento_Sales::actions',
        'Magento_Sales::actions_view',
        'Magento_Sales::actions_edit',
        'Magento_Sales::shipment'
    ];


    /** @var RoleFactory  */
    private $roleFactory;

    /** @var RulesFactory  */
    private $rulesFactory;

    /**
     * AutoGenerateApiUser constructor.
     *
     * @param UserFactory $userFactory
     * @param SendCloudLogger $logger
     * @param RoleFactory $roleFactory
     * @param RulesFactory $rulesFactory
     */
    public function __construct(UserFactory $userFactory, SendCloudLogger $logger, RoleFactory $roleFactory, RulesFactory $rulesFactory)
    {
        $this->userFactory = $userFactory;
        $this->logger = $logger;
        $this->roleFactory = $roleFactory;
        $this->rulesFactory = $rulesFactory;
    }

    /**
     * @param $password
     *
     * @return array|bool
     * @throws Exception
     */
    public function getApiUser($password)
    {
        $userFactory = $this->userFactory->create();
        $apiUser = $userFactory->loadByUsername('sendcloud');

        if (!$apiUser->getUsername()) {
            return false;
        }
        try {
            $apiUser->setPassword($password);
            $apiUser->save();
        } catch (\Exception $ex) {
            $this->logger->debug($ex->getMessage());
            throw new Exception($ex->getMessage());
        }

        $apiUserArray = [
            'username' => 'sendcloud',
            'password' => $password
        ];

        return $apiUserArray;
    }

    /**
     * @param $password
     * @return mixed
     * @throws Exception
     */
    public function createApiUser($password)
    {
        $apiUserInfo = [
            'username'  => 'sendcloud',
            'firstname' => 'rob',
            'lastname'    => 'api',
            'email'     => 'sendcloud@api.com',
            'password'  => $password,
            'interface_locale' => 'en_US',
            'role_id' => $this->generateApiRole()->getId(),
            'is_active' => 1
        ];

        try {
            $userFactory = $this->userFactory->create();
            $apiUser = $userFactory->setData($apiUserInfo);
            $apiUser->save();
        } catch (\Exception $ex) {
            $this->logger->debug($ex->getMessage());
            throw new Exception($ex->getMessage());
        }

        $apiUserArray = [
            'username' => $apiUserInfo['username'],
            'password' => $apiUserInfo['password']
        ];

        return $apiUserArray;
    }

    /**
     * Create Api Role
     *
     * @return \Magento\Authorization\Model\Role
     * @throws Exception
     */
    private function generateApiRole()
    {
        $roleData = [
            'name' => 'SendCloudApi',
            'pid' => 0,
            'role_type' => RoleGroup::ROLE_TYPE,
            'user_type' => UserContextInterface::USER_TYPE_ADMIN
        ];

        try {
            $role = $this->roleFactory->create();
            $role->setData($roleData);
            $role->save();
        } catch (\Exception $ex) {
            $this->logger->debug($ex->getMessage());
            throw new Exception($ex->getMessage());
        }

        $this->rulesFactory->create()
            ->setRoleId($role->getId())
            ->setData('resources', $this->resource)
            ->saveRel();

        return $role;
    }
}
