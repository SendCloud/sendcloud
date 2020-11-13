<?php

namespace SendCloud\SendCloud\Block\Adminhtml\Carrier\Servicepointrate;

use \Magento\Backend\Block\Template\Context;
use \Magento\Backend\Block\Widget\Grid\Extended;
use \Magento\Backend\Helper\Data;
use \SendCloud\SendCloud\Model\Carrier\SendCloud;
use \SendCloud\SendCloud\Model\ResourceModel\Carrier\Servicepointrate\CollectionFactory;

/**
 * Shipping carrier service point rate grid block
 * WARNING: This grid is used for export SendCloud Servicepoint rates
 */
class Grid extends Extended
{
    /**
     * Website filter
     *
     * @var int
     */
    protected $_websiteId;

    /**
     * Condition filter
     *
     * @var string
     */
    protected $_conditionName;

    /**
     * @var SendCloud
     */
    protected $_servicepointrate;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param CollectionFactory $collectionFactory
     * @param SendCloud $sendcloud
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $collectionFactory,
        SendCloud $sendcloud,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_servicepointrate = $sendcloud;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Define grid properties
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('shippingServicepointrateGrid');
        $this->_exportPageSize = 10000;
    }

    /**
     * Set current website
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId($websiteId)
    {
        $this->_websiteId = $this->_storeManager->getWebsite($websiteId)->getId();
        return $this;
    }

    /**
     * Retrieve current website id
     *
     * @return int
     */
    public function getWebsiteId()
    {
        if ($this->_websiteId === null) {
            $this->_websiteId = $this->_storeManager->getWebsite()->getId();
        }
        return $this->_websiteId;
    }

    /**
     * Set current website
     *
     * @param string $name
     * @return $this
     */
    public function setSenConditionName($name)
    {
        $this->_conditionName = $name;
        return $this;
    }

    /**
     * Retrieve current website id
     *
     * @return int
     */
    public function getSenConditionName()
    {
        return $this->_conditionName;
    }

    /**
     * Prepare shipping table rate collection
     *
     * @return Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        $collection->setConditionFilter($this->getSenConditionName())->setWebsiteFilter($this->getWebsiteId());

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }


    /**
     * Prepare table columns
     *
     * @return Grid
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'dest_country',
            ['header' => __('Country'), 'index' => 'dest_country', 'default' => '*']
        );

        $this->addColumn(
            'dest_region',
            ['header' => __('Region/State'), 'index' => 'dest_region', 'default' => '*']
        );

        $this->addColumn(
            'dest_zip',
            ['header' => __('Zip/Postal Code'), 'index' => 'dest_zip', 'default' => '*']
        );

        $label = $this->_servicepointrate->getCode('sen_condition_name_short', $this->getSenConditionName());
        $this->addColumn('condition_value', ['header' => $label, 'index' => 'condition_value']);

        $this->addColumn('price', ['header' => __('Shipping Price'), 'index' => 'price']);

        return parent::_prepareColumns();
    }
}
