<?php


namespace SendCloud\SendCloud\Setup;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Config\Model\ResourceModel\Config\Data;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;

class Uninstall implements UninstallInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var Data
     */
    protected $configResource;

    /**
     * @param CollectionFactory $collectionFactory
     * @param Data $configResource
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Data $configResource
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->configResource    = $configResource;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.Generic.CodeAnalysis.UnusedFunctionParameter)
     */
    // @codingStandardsIgnoreStart
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
        // @codingStandardsIgnoreEnd
    {
        $this->dropSendcloudTable($setup);
        $this->cleanConfigData();
    }

    /**
     * Delete sendcloud core config data
     *
     * @throws \Exception
     */
    private function cleanConfigData()
    {
        $this->deleteByFilter('carriers/sendcloud');
        $this->deleteByFilter('carriers/sendcloudcheckout');
        $this->deleteByFilter('sendcloud');
    }

    /**
     * Drop sendcloud service point rate table
     *
     * @param SchemaSetupInterface $setup
     */
    private function dropSendcloudTable(SchemaSetupInterface $setup)
    {
        if ($setup->tableExists('sendcloud_servicepointrate')) {
            $setup->getConnection()->dropTable('sendcloud_servicepointrate');
        }
        if ($setup->tableExists('sendcloud_shipping_zones')) {
            $setup->getConnection()->dropTable('sendcloud_shipping_zones');
        }
        if ($setup->tableExists('sendcloud_shipping_methods')) {
            $setup->getConnection()->dropTable('sendcloud_shipping_methods');
        }
    }

    /**
     * @param string $filter
     * @return void
     */
    private function deleteByFilter(string $filter)
    {
        $data = $this->collectionFactory->create()->addPathFilter($filter);
        foreach ($data as $config) {
            $this->configResource->delete($config);
        }
    }
}
