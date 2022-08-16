<?php


namespace SendCloud\SendCloud\Block\System\Config\Form;

class DynamicMessage extends \Magento\Config\Block\System\Config\Form\Field
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
        return __("This feature is not available in Global view. To access it, please select a store view.");
    }
}
