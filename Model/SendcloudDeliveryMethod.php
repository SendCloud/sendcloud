<?php

namespace SendCloud\SendCloud\Model;

use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryMethod as SendcloudDeliveryMethodResourceModel;

/**
 * Class SendcloudDeliveryMethod
 * @package SendCloud\SendCloud\Model
 */
class SendcloudDeliveryMethod extends AbstractDomen
{

    /**
     * Model initialization.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(SendcloudDeliveryMethodResourceModel::class);
    }
}
