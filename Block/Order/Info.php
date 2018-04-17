<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 17-4-18
 * Time: 13:56
 */

namespace CreativeICT\SendCloud\Block\Order;

use \Magento\Sales\Block\Order\Info as Original;

class Info extends Original
{
    /**
     * @var string
     */
    protected $_template = 'CreativeICT_SendCloud::order/info.phtml';
}