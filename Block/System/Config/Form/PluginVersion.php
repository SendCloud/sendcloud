<?php


namespace SendCloud\SendCloud\Block\System\Config\Form;

use Magento\Backend\Block\Template\Context;
use SendCloud\SendCloud\Helper\Data;

class PluginVersion extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'system/config/form/plugin_version.phtml';
    /**
     * @var string
     */
    protected $_id = 'pluginVersion';
    /**
     * @var Data
     */
    private $helper;

    /**
     * PluginVersion constructor.
     * @param Data $helper
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Data $helper,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return __("Plugin version: ") . $this->helper->getVersion();
    }
}
