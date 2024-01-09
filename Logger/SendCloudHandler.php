<?php
namespace SendCloud\SendCloud\Logger;

use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base;

/**
 * Class SendCloudHandler
 */
class SendCloudHandler extends Base
{

    protected $loggerType = Logger::DEBUG;
    protected $fileName = '/var/log/sendcloud_exception.log';
}
