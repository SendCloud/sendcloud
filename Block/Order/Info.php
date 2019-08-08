<?php

namespace SendCloud\SendCloud\Block\Order;

use \Magento\Sales\Block\Order\Info as Original;

class Info extends Original
{
    /**
     * @var string
     */
    protected $_template = 'SendCloud_SendCloud::order/info.phtml';
}
