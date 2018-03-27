<?php
namespace CreativeICT\SendCloud\Model;

use CreativeICT\SendCloud\Api\SettingsInterface;
use CreativeICT\SendCloud\Logger\SendCloudLogger;
use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class Settings implements SettingsInterface
{
    /** @var WriterInterface  */
    private $configWriter;

    /** @var ScopeConfigInterface  */
    private $scopeConfig;

    /** @var SendCloudLogger  */
    private $logger;

    /**
     * Settings constructor.
     * @param WriterInterface $configWriter
     * @param ScopeConfigInterface $scopeConfig
     * @param SendCloudLogger $logger
     */
    public function __construct(
        WriterInterface $configWriter,
        ScopeConfigInterface $scopeConfig,
        SendCloudLogger $logger
    )
    {
        $this->configWriter = $configWriter;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
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
        try {
            $this->configWriter->save('creativeict/sendcloud/script_url', $script_url, $scope = $this->scopeConfig::SCOPE_TYPE_DEFAULT, $scopeId = 0);
            return $script_url;
        } catch (Exception $ex) {
            $this->logger->addErrorlog('error', $ex->getMessage());
            throw new Exception($ex->getMessage());
        }


    }
}