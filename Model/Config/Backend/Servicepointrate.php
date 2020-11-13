<?php

namespace SendCloud\SendCloud\Model\Config\Backend;

use Magento\Framework\Model\AbstractModel;

class Servicepointrate extends \Magento\Framework\App\Config\Value
{
    /**
     * @var \SendCloud\SendCloud\Model\ResourceModel\Carrier\ServicepointrateFactory
     */
    protected $_servicepointrateFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \SendCloud\SendCloud\Model\ResourceModel\Carrier\ServicepointrateFactory $servicepointrateFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \SendCloud\SendCloud\Model\ResourceModel\Carrier\ServicepointrateFactory $servicepointrateFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_servicepointrateFactory = $servicepointrateFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return Servicepointrate
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterSave()
    {
        /** @var \SendCloud\SendCloud\Model\ResourceModel\Carrier\Servicepointrate $servicepointRate */
        $servicepointRate = $this->_servicepointrateFactory->create();
        $servicepointRate->uploadAndImport($this);
        return parent::afterSave();
    }
}
