<?php

namespace SendCloud\SendCloud\Model;

use SendCloud\SendCloud\Api\SettingsInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Module\ResourceInterface;

class Settings implements SettingsInterface
{
    /** @var ModuleResource  */
    private $moduleResource;

    /** @var ProductMetadataInterface  */
    private $productMetaData;

    /**
     * Settings constructor.
     * @param ResourceInterface $moduleResource
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(ResourceInterface $moduleResource, ProductMetadataInterface $productMetadata)
    {
        $this->moduleResource = $moduleResource;
        $this->productMetaData = $productMetadata;
    }

    /**
     * @return array
     */
    public function getModuleInformation()
    {
        $moduleInformation = [[
            'php' => phpversion(),
            'magento' => $this->productMetaData->getVersion(),
            'SendCloud-plugin' => $this->moduleResource->getDbVersion('SendCloud_SendCloud')
        ]];

        return $moduleInformation;
    }
}
