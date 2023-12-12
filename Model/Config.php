<?php

namespace SendCloud\SendCloud\Model;

use SendCloud\SendCloud\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Cache\Manager;

class Config implements ConfigInterface
{
    const MIN_LOG_LEVEL_PATH = 'sendcloud/general/minimal_log_level';

    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Manager
     */
    protected $cacheManager;

    /**
     * Config Constructor.
     *
     * @param WriterInterface $configWriter
     * @param ScopeConfigInterface $scopeConfig
     * @param Manager $cacheManager
     */
    public function __construct(
        WriterInterface $configWriter,
        ScopeConfigInterface $scopeConfig,
        Manager $cacheManager
    ) {
        $this->configWriter = $configWriter;
        $this->scopeConfig = $scopeConfig;
        $this->cacheManager = $cacheManager;
    }

    /**
     * Retrieves minimal log level.
     *
     * @return string|null
     */
    public function getMinimalLogLevel(): ?string
    {
        return $this->scopeConfig->getValue(self::MIN_LOG_LEVEL_PATH);
    }

    /**
     * Sets minimal log level.
     *
     * @param int $logLevel
     *
     * @return void
     */
    public function saveMinimalLogLevel(int $logLevel): void
    {
        $this->configWriter->save(self::MIN_LOG_LEVEL_PATH, $logLevel);
        $this->cacheManager->flush(['config']);
    }
}
