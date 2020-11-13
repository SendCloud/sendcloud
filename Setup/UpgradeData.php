<?php

namespace SendCloud\SendCloud\Setup;

use Magento\Catalog\Model\Config as CatalogConfig;
use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeManagementInterface;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;

class UpgradeData implements UpgradeDataInterface
{
    const COUNTRY_OF_MANUFACTURE = 'country_of_manufacture';
    const ATTRIBUTE_GROUP = 'general';

    /**
     * Eav setup factory
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var CollectionFactory
     */
    private $attributeSetCollection;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var SendCloudLogger
     */
    private $logger;

    /**
     * @var CatalogConfig
     */
    private $catalogConfig;

    /**
     * @var AttributeManagementInterface
     */
    private $attributeManagement;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param CollectionFactory $attributeSetCollection
     * @param Config $eavConfig
     * @param CatalogConfig $catalogConfig
     * @param AttributeManagementInterface $attributeManagement
     * @param SendCloudLogger $logger
     */
    public function __construct(EavSetupFactory $eavSetupFactory, CollectionFactory $attributeSetCollection, Config $eavConfig, CatalogConfig $catalogConfig, AttributeManagementInterface $attributeManagement, SendCloudLogger $logger)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetCollection = $attributeSetCollection;
        $this->eavConfig = $eavConfig;
        $this->catalogConfig = $catalogConfig;
        $this->attributeManagement = $attributeManagement;
        $this->logger = $logger;
    }

    /**
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.5.0', '<=')) {
            $this->addHsCode();
            $this->assignsCountryOfManufactureToAllSets();
        }
    }

    private function addHsCode()
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->addAttribute(
            Product::ENTITY,
            'hs_code',
            [
                'group' => 'General',
                'type' => 'varchar',
                'label' => 'HS-Code',
                'input' => 'text',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'is_user_defined' => true,
                'visible' => true,
                'visible_on_front' => false
            ]
        );
    }

    private function assignsCountryOfManufactureToAllSets()
    {
        $attributeSets = $this->getAttributeSetList();

        foreach ($attributeSets as $attributeSet) {
            $attributeGroupId = $this->catalogConfig->getAttributeGroupId($attributeSet->getAttributeSetId(), self::ATTRIBUTE_GROUP);
            $this->attributeManagement->assign(
                Product::ENTITY,
                $attributeSet->getAttributeSetId(),
                $attributeGroupId,
                self::COUNTRY_OF_MANUFACTURE,
                999
            );
        }
    }

    private function getAttributeSetList()
    {
        $attributeSetCollection = $this->attributeSetCollection->create();
        try {
            $attributeSetCollection->setEntityTypeFilter($this->eavConfig->getEntityType(Product::ENTITY)->getEntityTypeId());

            return $attributeSetCollection->getItems();

        } catch (LocalizedException $e) {
            $this->logger->debug($e->getMessage());
        }

        return $this;
    }
}
