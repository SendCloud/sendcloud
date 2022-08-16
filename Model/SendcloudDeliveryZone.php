<?php

namespace SendCloud\SendCloud\Model;

use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryZone as SendcloudDeliveryZoneResourceModel;

/**
 * Class SendcloudDeliveryZone
 * @package SendCloud\SendCloud\Model
 */
class SendcloudDeliveryZone extends AbstractDomen
{

    /**
     * Model initialization.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(SendcloudDeliveryZoneResourceModel::class);
    }
}
