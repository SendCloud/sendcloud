<?php

namespace SendCloud\SendCloud\Block\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Branding extends Field
{
    const SENDCLOUD_URL = 'https://www.sendcloud.com';

    protected $_template = 'SendCloud_SendCloud::system/config/branding.phtml';

    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element)
    {
        return $this->toHtml();
    }

    public function getSendCloudLogo()
    {
        return $this->getViewFileUrl('SendCloud_SendCloud::images/sendcloud-logo.svg');
    }

    public function getSendCloudUrl()
    {
        return self::SENDCLOUD_URL;
    }
}
