<?php

namespace SendCloud\SendCloud\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Module\ModuleListInterface;

class AddResourcesToRole implements DataPatchInterface
{
    const ROLE_NAME = 'SendCloudApi';
    const RESOURCES = [
        'Magento_Catalog::catalog',
        'Magento_Catalog::inventory',
        'Magento_Catalog::products'
    ];

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ModuleListInterface $moduleList
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup, ModuleListInterface $moduleList)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->moduleList = $moduleList;
    }

    /**
     * Apply patch
     *
     * @return void
     */
    public function apply()
    {
        $moduleVersion = $this->moduleList->getOne('SendCloud_SendCloud')['setup_version'];

        if (version_compare($moduleVersion, '2.0.23', '<=')) {
            $connection = $this->moduleDataSetup->getConnection();
            $aclRoleTable = $this->moduleDataSetup->getTable('authorization_role');
            $aclRuleTable = $this->moduleDataSetup->getTable('authorization_rule');

            $roleId = $connection->fetchOne(
                $connection->select()
                    ->from($aclRoleTable, ['role_id'])
                    ->where('role_name = ?', self::ROLE_NAME)
            );

            if ($roleId) {
                $resourcesToAdd = [];

                foreach (self::RESOURCES as $resource) {
                    $resourcesToAdd[] = [
                        'role_id' => $roleId,
                        'resource_id' => $resource,
                        'permission' => 'allow'
                    ];
                }

                $connection->insertOnDuplicate($aclRuleTable, $resourcesToAdd);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Get array of patches that have to be executed prior to this
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }
}
