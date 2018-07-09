<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 5-4-18
 * Time: 15:44
 */

namespace SendCloud\SendCloud\Block\Checkout;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Config extends Template
{
    /** @var ScopeConfigInterface  */
    private $scopeConfig;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        $this->scopeConfig = $scopeConfig;

        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getScriptUrl()
    {
        return $this->scopeConfig->getValue('sendcloud/sendcloud/script_url', ScopeInterface::SCOPE_STORE);
    }
}