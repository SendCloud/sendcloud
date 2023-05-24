<?php

namespace SendCloud\SendCloud\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpdateConfigValues implements DataPatchInterface
{
    const CONFIG_VALUES = ['sort_order', 'price', 'title', 'name', 'integration_id', 'active'];

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Apply patch
     *
     * @return void
     */
    public function apply()
    {
        $connection = $this->moduleDataSetup->getConnection();
        $tableName = $this->moduleDataSetup->getTable('core_config_data');

        foreach (self::CONFIG_VALUES as $value) {
            $oldPath = 'carriers/sendcloud_checkout/' . $value;
            $newPath = 'carriers/sendcloudcheckout/' . $value;

            $connection->update($tableName, ['path' => $newPath], ['path = ?' => $oldPath]);
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
