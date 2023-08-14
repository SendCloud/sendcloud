<?php


namespace SendCloud\SendCloud\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Zend_Db_Expr;

class UpdateServicePoint implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * PatchInitial constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $connection = $this->moduleDataSetup->getConnection();
        $connection->update(
            $this->moduleDataSetup->getTable('sendcloud_servicepointrate'),
            ['condition_name' => 'package_fixed'],
            [new Zend_Db_Expr('condition_name = \'sen_package_fixed\'')]
        );
        $connection->update(
            $this->moduleDataSetup->getTable('sendcloud_servicepointrate'),
            ['condition_name' => 'package_weight'],
            [new Zend_Db_Expr('condition_name = \'sen_package_weight\'')]
        );
        $connection->update(
            $this->moduleDataSetup->getTable('sendcloud_servicepointrate'),
            ['condition_name' => 'package_value_with_discount'],
            [new Zend_Db_Expr('condition_name = \'sen_package_value_with_discount\'')]
        );
        $connection->update(
            $this->moduleDataSetup->getTable('sendcloud_servicepointrate'),
            ['condition_name' => 'package_qty'],
            [new Zend_Db_Expr('condition_name = \'sen_package_qty\'')]
        );

        $connection->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['path' => 'carriers/sendcloud/condition_name'],
            [
                new Zend_Db_Expr('path = \'carriers/sendcloud/sen_condition_name\''),
            ]
        );
        $connection->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['path' => 'carriers/sendcloud/import'],
            [
                new Zend_Db_Expr('path = \'carriers/sendcloud/sen_import\''),
            ]
        );
        $connection->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['path' => 'carriers/sendcloud/export'],
            [
                new Zend_Db_Expr('path = \'carriers/sendcloud/sen_export\''),
            ]
        );
        $connection->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['path' => 'carriers/sendcloud/include_virtual_price'],
            [
                new Zend_Db_Expr('path = \'carriers/sendcloud/sen_include_virtual_price\''),
            ]
        );
        $connection->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['value' => 'package_fixed'],
            [new Zend_Db_Expr('value = \'sen_package_fixed\'')]
        );
        $connection->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['value' => 'package_weight'],
            [new Zend_Db_Expr('value = \'sen_package_weight\'')]
        );
        $connection->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['value' => 'package_value_with_discount'],
            [new Zend_Db_Expr('value = \'sen_package_value_with_discount\'')]
        );
        $connection->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['value' => 'package_qty'],
            [new Zend_Db_Expr('value = \'sen_package_qty\'')]
        );
        $this->moduleDataSetup->getConnection()->endSetup();

        $connection->endSetup();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
