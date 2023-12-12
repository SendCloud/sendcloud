<?php

namespace SendCloud\SendCloud\Block\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field;

class DynamicCheckoutDeprecatedMessage extends Field
{
    protected $_template = 'system/config/form/general_message.phtml';

    /**
     * @return string
     */
    public function getText()
    {
        if (empty($this->_request->getParam('store'))) {
            return "";
        }

        return __("We allow store view connections to Sendcloud for legacy purposes. Dynamic Checkout delivery methods are deprecated and would not receive any updates in the future. It is recommended to use the global connection to enable service point delivery. Please be aware if you keep both global and store view connection to Sendcloud, you may have double orders for that store.");
    }
}
