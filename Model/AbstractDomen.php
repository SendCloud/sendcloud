<?php

namespace SendCloud\SendCloud\Model;

use Magento\Framework\Model\AbstractModel;
use SendCloud\SendCloud\Model\ResourceModel\AbstractDomen as AbstractDomenResourceModel;

/**
 * Class AbstractDomen
 * @package SendCloud\SendCloud\Model
 */
class AbstractDomen extends AbstractModel
{

    /**
     * Model initialization.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(AbstractDomenResourceModel::class);
    }
}
