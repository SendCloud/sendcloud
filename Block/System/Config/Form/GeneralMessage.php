<?php


namespace SendCloud\SendCloud\Block\System\Config\Form;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class GeneralMessage extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_template = 'system/config/form/general_message.phtml';

    /**
     * @return string
     */
    public function getText()
    {
        if (!empty($this->_request->getParam('store'))) {
            return "";
        }
        return __("We allow global connection to Sendcloud for legacy purposes. To use new delivery methods such as
        nominated day or embedded service points, please switch to store view and connect it to Sendcloud. Please be
        aware if you keep both global and store view connection to Sendcloud, you may have double orders for that store.");
    }
}
