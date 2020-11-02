<?php

namespace SendCloud\SendCloud\Model\Config\Source;

/**
 * Class Servicepointrate
 * @package SendCloud\SendCloud\Model\Config\Source
 *
 * TODO: class Servicepointrate implements \Magento\Framework\Option\OptionSourceInterface
 * use OptionSourceInterface as ArrayInterface is deprecated, but OptionSourceInterface gives compile error when used.
 */
class Servicepointrate implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \SendCloud\SendCloud\Model\Carrier\SendCloud
     */
    protected $_carrierServicepointrate;


    /**
     * Servicepointrate constructor.
     * @param \SendCloud\SendCloud\Model\Carrier\SendCloud $carrierServicepointrate
     */
    public function __construct(\SendCloud\SendCloud\Model\Carrier\SendCloud $carrierServicepointrate)
    {
        $this->_carrierServicepointrate = $carrierServicepointrate;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        $arr = [];
        foreach ($this->_carrierServicepointrate->getCode('condition_name') as $k => $v) {
            $arr[] = ['value' => $k, 'label' => $v];
        }
        return $arr;
    }
}
