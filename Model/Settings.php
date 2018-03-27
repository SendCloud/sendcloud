<?php
namespace CreativeICT\SendCloud\Model;

use CreativeICT\SendCloud\Api\SettingsInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class Settings implements SettingsInterface
{
    /** @var WriterInterface  */
    private $configWriter;

    /** @var ScopeConfigInterface  */
    private $scopeConfig;

    /**
     * Settings constructor.
     * @param WriterInterface $configWriter
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        WriterInterface $configWriter,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->configWriter = $configWriter;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed|string
     */
    public function getModuleInformation()
    {
        return "Module information";
    }

    /**
     * @api
     * @param string $script_url
     * @return string
     */
    public function setScriptUrl($script_url)
    {
        $this->configWriter->save('creativeict/sendcloud/script_url', $script_url, $scope = $this->scopeConfig::SCOPE_TYPE_DEFAULT, $scopeId = 0);

        return $script_url;
    }
}