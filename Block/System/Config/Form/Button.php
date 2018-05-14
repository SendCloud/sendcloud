<?php

namespace CreativeICT\SendCloud\Block\System\Config\Form;

/**
 * Class Button
 *
 * @package CreativeICT\SendCloud\Block\System\Config\Form
 */
class Button extends \Magento\Config\Block\System\Config\Form\Field
{
    const BUTTON_TEMPLATE = 'system/config/button/button.phtml';

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     *
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    /**
     * @return string
     */
    public function getAjaxCheckUrl()
    {
        return $this->getUrl('creativeict_autoconnect/autoconnect/connector');
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::BUTTON_TEMPLATE);
        }
        return $this;
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->addData(
            [
                'id'        => 'connectToSendCloud',
                'button_label'     => __('Connect to SendCloud'),
            ]
        );
        return $this->_toHtml();
    }
}