<?php
namespace SendCloud\SendCloud\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;

/**
 * Class Checkout
 * @package SendCloud\SendCloud\Helper
 */
class Checkout extends AbstractHelper
{
    private $_scopeConfig;

    private $sendCloudLogger;

    /**
     * Checkout constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param SendCloudLogger $sendCloudLogger
     */
    public function __construct(Context $context, ScopeConfigInterface $scopeConfig, SendCloudLogger $sendCloudLogger)
    {
        parent::__construct($context);

        $this->_scopeConfig = $scopeConfig;
        $this->sendCloudLogger = $sendCloudLogger;
    }

    /**
     * @return bool
     */
    public function checkForScriptUrl()
    {
        $isScriptUrlDefined = true;
        $scriptUrl = $this->_scopeConfig->getValue('sendcloud/sendcloud/script_url', ScopeInterface::SCOPE_STORE);

        if ($scriptUrl == '' || $scriptUrl == null) {
            $this->sendCloudLogger->debug('The option service point is not active in SendCloud');
            $isScriptUrlDefined = false;
        }

        return $isScriptUrlDefined;
    }

    /**
     * @return bool|mixed
     */
    public function checkIfModuleIsActive()
    {
        $isActive = $this->_scopeConfig->getValue(
            'sendcloud/general/enable',
            ScopeInterface::SCOPE_STORE
        );

        return $isActive;
    }
}
