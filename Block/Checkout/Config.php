<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 5-4-18
 * Time: 15:44
 */

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
