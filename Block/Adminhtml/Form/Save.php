<?php

namespace SendCloud\SendCloud\Block\Adminhtml\Form;

use Magento\Config\Block\System\Config\Edit;

class Save extends Edit
{
    /**
     * Retrieve config save url
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('sendcloud/configuration/save', ['_current' => true]);
    }
}
