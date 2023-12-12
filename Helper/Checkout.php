<?php

namespace SendCloud\SendCloud\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;

class Checkout extends AbstractHelper
{
    /**
     * @var SendCloudLogger
     */
    private $sendCloudLogger;

    /**
     * @param Context $context
     * @param SendCloudLogger $sendCloudLogger
     */
    public function __construct(Context $context, SendCloudLogger $sendCloudLogger)
    {
        parent::__construct($context);
        $this->sendCloudLogger = $sendCloudLogger;
    }

    /**
     * @return bool
     */
    public function checkForScriptUrl()
    {
        $isScriptUrlDefined = true;
        $scriptUrl = $this->scopeConfig->getValue('sendcloud/sendcloud/script_url', ScopeInterface::SCOPE_STORE);

        if ($scriptUrl == '' || $scriptUrl == null) {
            $this->sendCloudLogger->debug('The option service point is not active in SendCloud');
            $isScriptUrlDefined = false;
        }

        return $isScriptUrlDefined;
    }

    /**
     * @param string|null $store
     * @return string
     */
    public function checkIfModuleIsActive(?string $store = null)
    {
        return $this->getConfigValue('sendcloud/general/enable', $store);
    }

    /**
     * @param string|null $store
     * @return string
     */
    public function checkIfChekoutIsActive(?string $store = null)
    {
        return $this->getConfigValue('carriers/sendcloudcheckout/active', $store);
    }

    /**
     * @param string $path
     * @param string|null $store
     * @return string
     */
    private function getConfigValue(string $path, ?string $store): string
    {
        if ($store === null) {
            $isActive = $this->scopeConfig->getValue($path);
        } else {
            $isActive = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $store);
        }

        return $isActive;
    }
}
