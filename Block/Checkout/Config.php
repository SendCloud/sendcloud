<?php declare(strict_types=1);

namespace SendCloud\SendCloud\Block\Checkout;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Config extends Template
{
    public function getScriptUrl()
    {
        return $this->_scopeConfig->getValue('sendcloud/sendcloud/script_url', ScopeInterface::SCOPE_STORE);
    }
}
