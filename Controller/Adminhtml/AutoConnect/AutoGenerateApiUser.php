<?php

namespace SendCloud\SendCloud\Controller\Adminhtml\AutoConnect;

use SendCloud\SendCloud\Logger\SendCloudLogger;
use Magento\Authorization\Model\RoleFactory;
use Magento\Authorization\Model\RulesFactory;
use Magento\Setup\Exception;
use Magento\User\Model\UserFactory;
use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Authorization\Model\ResourceModel\Role\CollectionFactory as RoleCollectionFactory;

/**
 * Class AutoGenerateApiUser
 *
 */
class AutoGenerateApiUser
{

    /** @var UserFactory */
    private $userFactory;
    /**
     * @var SendCloudLogger
     */
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
        'Magento_Sales::shipment',
        'Magento_Sales::ship'
    ];

    /** @var RoleFactory */
    private $roleFactory;

    /** @var RulesFactory */
    private $rulesFactory;
    /**
     * @var RoleCollectionFactory
     */
    protected $roleCollectionFactory;

    /**
     * @param UserFactory $userFactory
     * @param SendCloudLogger $logger
     * @param RoleFactory $roleFactory
     * @param RulesFactory $rulesFactory
     * @param RoleCollectionFactory $collectionFactory
     */
    public function __construct(
        UserFactory $userFactory,
        SendCloudLogger $logger,
        RoleFactory $roleFactory,
        RulesFactory $rulesFactory,
        RoleCollectionFactory $collectionFactory
    ) {
        $this->userFactory = $userFactory;
        $this->logger = $logger;
        $this->roleFactory = $roleFactory;
        $this->rulesFactory = $rulesFactory;
        $this->roleCollectionFactory = $collectionFactory;
    }

    /**
     * @param $password
     * @param int|null $store
     * @return array|false
     * @throws Exception
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getApiUser($password, $store)
    {
        $userFactory = $this->userFactory->create();

        if (!empty($store)) {
            $apiUser = $userFactory->loadByUsername("sendcloud_$store");
        } else {
            $apiUser = $userFactory->loadByUsername("sendcloud");
        }

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
            'username' => $apiUser->getUsername(),
            'password' => $password
        ];

        if (!empty($store)) {
            $apiUserArray['store_view_id'] = $store;
        }

        return $apiUserArray;
    }

    /**
     * @param $password
     * @param int|null $store
     * @return array
     * @throws Exception
     */
    public function createApiUser($password, $store)
    {

        $apiUserInfo = [
            'firstname' => 'rob',
            'lastname' => 'api',
            'password' => $password,
            'interface_locale' => 'en_US',
            'role_id' => $this->generateApiRole(),
            'is_active' => 1
        ];

        if (!empty($store)) {
            $apiUserInfo['username'] = "sendcloud_$store";
            $apiUserInfo['email'] = "sendcloud_$store@api.com";
        } else {
            $apiUserInfo['username'] = "sendcloud";
            $apiUserInfo['email'] = "sendcloud@api.com";
        }

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

        if (!empty($store)) {
            $apiUserArray['store_view_id'] = $store;
        }

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
        $roles = $this->getRoles();

        if (!empty($roles)) {
            return $roles[0]['role_id'];
        }

        $roleFactory = $this->roleFactory->create();
        $roles = $roleFactory->getData();
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

        return $role->getId();
    }

    /**
     * @return array|null
     */
    private function getRoles()
    {
        $roleCollectionFacotry = $this->roleCollectionFactory->create();
        $roleCollectionFacotry->addFieldToFilter('role_name', 'SendCloudApi');
        return $roleCollectionFacotry->getData();
    }
}
