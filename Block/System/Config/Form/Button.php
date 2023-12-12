<?php

namespace SendCloud\SendCloud\Block\System\Config\Form;

class Button extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_template = 'system/config/button/button.phtml';
    protected $_id = 'connectToSendCloud';

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
        $params = [];
        if ($this->getRequest()->getParam('store')) {
            $params['store'] = $this->getRequest()->getParam('store');
        }
        return $this->getUrl('sendcloud_autoconnect/autoconnect/connector', $params);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getButtonLabel()
    {
        return __('Connect to Sendcloud');
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
                'id' => 'connectToSendCloud',
                'button_label' => __('Connect to Sendcloud'),
            ]
        );
        return $this->_toHtml();
    }
}
