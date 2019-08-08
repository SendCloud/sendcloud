<?php

namespace SendCloud\SendCloud\Block\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Branding extends Field
{
    const SENDCLOUD_URL = 'https://www.sendcloud.nl';
    const CREATIVEICT_URL = 'https://www.creativeict.nl';

    protected $_template = 'SendCloud_SendCloud::system/config/branding.phtml';

    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->addClass('creativeict');

        return $this->toHtml();
    }

    public function getSendCloudLogo()
    {
        return $this->getViewFileUrl('SendCloud_SendCloud::images/sendcloud-logo.png');
    }

    public function getCreativeICTLogo()
    {
        return $this->getViewFileUrl('SendCloud_SendCloud::images/creativeict-logo.png');
    }

    public function getCreativeICTUrl()
    {
        return self::CREATIVEICT_URL;
    }

    public function getSendCloudUrl()
    {
        return self::SENDCLOUD_URL;
    }
}
