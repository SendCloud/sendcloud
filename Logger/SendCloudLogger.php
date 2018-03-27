<?php
namespace CreativeICT\SendCloud\Logger;

use Monolog\Logger;

class SendCloudLogger extends Logger
{
    /**
     * Add info data to SendCloud Log
     *
     * @param $type
     * @param $data
     */
    public function addInfoLog($type, $data)
    {
        if (is_array($data)) {
            $this->addInfo($type . ': ' . print_r($data, true));
        } elseif (is_object($data)) {
            $this->addInfo($type . ': ' . print_r($data, true));
        } else {
            $this->addInfo($type . ': ' . $data);
        }
    }

    /**
     * Add error data to SendCloud Log
     *
     * @param $type
     * @param $data
     */
    public function addErrorLog($type, $data)
    {
        if (is_array($data)) {
            $this->addError($type . ': ' . print_r($data, true));
        } elseif (is_object($data)) {
            $this->addError($type . ': ' . print_r($data));
        } else {
            $this->addError($type . ': ' . $data);
        }
    }
}