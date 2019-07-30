<?php

namespace SendCloud\SendCloud\Block\Checkout;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package SendCloud\SendCloud\Block\Checkout
 */
class Config extends Template
{
    /**
     * @return mixed
     */
    public function getScriptUrl()
    {
        return $this->_scopeConfig->getValue('sendcloud/sendcloud/script_url', ScopeInterface::SCOPE_STORE);
    }
}