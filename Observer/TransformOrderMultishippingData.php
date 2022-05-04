<?php

namespace SendCloud\SendCloud\Observer;

use http\Exception\RuntimeException;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class TransformOrderMultishippingData
 */
class TransformOrderMultishippingData implements ObserverInterface
{
    /**
     * @var Json
     */
    private $serializer;

    public function __construct(Json $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getData('order');
        $address = $observer->getData('address');
        $quote = $observer->getData('quote');
        $addressId = $address->getData('address_id');
        $multishippingData = $this->serializer->unserialize($quote->getData('sendcloud_multishipping_data'));
        if (array_key_exists($addressId, $multishippingData)) {
            $order->setSendcloudData($this->serializer->serialize($multishippingData[$addressId]));
        }

        return $this;
    }
}
