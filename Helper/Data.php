<?php


namespace SendCloud\SendCloud\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\ModuleListInterface;

class Data extends AbstractHelper
{
    const MODULE_NAME = 'SendCloud_SendCloud';

    protected $_moduleList;

    public function __construct(
        Context $context,
        ModuleListInterface $moduleList
    ) {
        $this->_moduleList = $moduleList;
        parent::__construct($context);
    }

    public function getVersion()
    {
        return $this->_moduleList
            ->getOne(self::MODULE_NAME)['setup_version'];
    }
}
