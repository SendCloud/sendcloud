<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 17-4-18
 * Time: 13:56
 */

namespace SendCloud\SendCloud\Block\Order;

use \Magento\Sales\Block\Order\Info as Original;

class Info extends Original
{
    /**
     * @var string
     */
    protected $_template = 'SendCloud_SendCloud::order/info.phtml';
}
