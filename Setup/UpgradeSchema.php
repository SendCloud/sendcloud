<?php

namespace SendCloud\SendCloud\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.1.0', '<=')) {
            $setup = $this->addColumns($setup, 'sales_order');
            $setup = $this->addColumns($setup, 'sales_order_grid');
            $setup = $this->addColumns($setup, 'quote');
        }
        if (version_compare($context->getVersion(), '1.2.0', '<=')) {
            $setup = $this->addPostnumber($setup, 'sales_order');
            $setup = $this->addPostnumber($setup, 'sales_order_grid');
            $setup = $this->addPostnumber($setup, 'quote');
        }

        $setup->endSetup();
    }

    /**
     * @param $setup
     * @param $tableName
     * @return mixed
     */
    private function addColumns($setup, $tableName)
    {
        $connection = $setup->getConnection();
        $connection->addColumn(
            $setup->getTable($tableName),
            'sendcloud_service_point_id',
            [
                'type' => Table::TYPE_INTEGER,
                'length' => 255,
                'nullable' => true,
                'comment' => 'service point id'
            ]
        );
        $connection->addColumn(
            $setup->getTable($tableName),
            'sendcloud_service_point_name',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'service point name'
            ]
        );
        $connection->addColumn(
            $setup->getTable($tableName),
            'sendcloud_service_point_street',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'service point street'
            ]
        );
        $connection->addColumn(
            $setup->getTable($tableName),
            'sendcloud_service_point_house_number',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'service point house number'
            ]
        );
        $connection->addColumn(
            $setup->getTable($tableName),
            'sendcloud_service_point_zip_code',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'service point zip code'
            ]
        );
        $connection->addColumn(
            $setup->getTable($tableName),
            'sendcloud_service_point_city',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'service point city'
            ]
        );
        $connection->addColumn(
            $setup->getTable($tableName),
            'sendcloud_service_point_country',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'service point country'
            ]
        );
        $connection->addColumn(
            $setup->getTable($tableName),
            'sendcloud_service_point_postnumber',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'service point post number'
            ]
        );

        return $setup;
    }

    private function addPostnumber($setup, $tableName) {
        $connection = $setup->getConnection();
        $connection->addColumn(
            $setup->getTable($tableName),
            'sendcloud_service_point_postnumber',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'service point post number'
            ]
        );

        return $setup;
    }
}
