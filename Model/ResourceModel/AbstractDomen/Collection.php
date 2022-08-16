<?php

namespace SendCloud\SendCloud\Model\ResourceModel\SendclooudDeliveryZone;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SendCloud\SendCloud\Model\AbstractDomen;
use SendCloud\SendCloud\Model\ResourceModel\AbstractDomen as AbstractDomenResourceModel;

/**
 * Class Collection
 * @package SendCloud\SendCloud\Model\ResourceModel\SendclooudDeliveryZone
 */
class Collection extends AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            AbstractDomen::class,
            AbstractDomenResourceModel::class
        );
    }
}
