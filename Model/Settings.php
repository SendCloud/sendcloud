<?php
namespace CreativeICT\SendCloud\Model;

use CreativeICT\SendCloud\Api\SettingsInterface;
use Magento\Framework\Module\ResourceInterface;

class Settings implements SettingsInterface
{
    /** @var ModuleResource  */
    private $moduleResource;

    /**
     * Settings constructor.
     * @param ResourceInterface $moduleResource
     */
    public function __construct(
        ResourceInterface $moduleResource
    )
    {
        $this->moduleResource = $moduleResource;
    }

    /**
     * @return array
     */
    public function getModuleInformation()
    {
        $moduleInformation = [[
            'Version' => $this->moduleResource->getDbVersion('CreativeICT_SendCloud')
        ]];

        return $moduleInformation;
    }
}