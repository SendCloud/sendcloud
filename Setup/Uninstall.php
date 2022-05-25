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
        $carrierData = $this->collectionFactory->create()
            ->addPathFilter('carriers/sendcloud');
        foreach ($carrierData as $config) {
            $this->configResource->delete($config);
        }
        $scripts = $this->collectionFactory->create()
            ->addPathFilter('sendcloud');
        foreach ($scripts as $script) {
            $this->configResource->delete($script);
        }
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
    }
}
