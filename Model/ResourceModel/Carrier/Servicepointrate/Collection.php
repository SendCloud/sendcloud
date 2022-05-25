<?php

namespace SendCloud\SendCloud\Model\ResourceModel\Carrier\Servicepointrate;

/**
 * Shipping table rates collection
 *
 * @api
 * @since 100.0.2
 */
class Collection extends \Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\Collection
{

    /**
     * Define resource model and item
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \SendCloud\SendCloud\Model\Carrier\SendCloud::class,
            \SendCloud\SendCloud\Model\ResourceModel\Carrier\Servicepointrate::class
        );
        $this->_countryTable = $this->getTable('directory_country');
        $this->_regionTable = $this->getTable('directory_country_region');
    }
}
