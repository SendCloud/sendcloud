<?php

namespace SendCloud\SendCloud\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Api\Data\OrderInterface;

class AddSendCloudExpectedDateVariable implements ObserverInterface
{
    /**
     * @var Json
     */
    private $serializer;

    private $order = null;

    public function __construct(Json $serializer)
    {
        $this->serializer = $serializer;
    }

    public function execute(Observer $observer)
    {
        $transportObject = $observer->getEvent()->getData('transportObject');
        $this->order = $transportObject->getOrder();

        if ($this->order !== null && $this->order->getSendcloudCheckoutData()) {
            $this->getCheckoutData($transportObject);
        }
    }

    private function getCheckoutData($transportObject)
    {
        /** @var OrderInterface $order */
        $order = $this->order;

        $checkoutData = $this->serializer->unserialize($order->getSendcloudData());

        if (empty($checkoutData)) {
            return;
        }

        if ($checkoutData['delivery_method_type'] === 'nominated_day_delivery' ||
            $checkoutData['delivery_method_type'] === 'same_day_delivery'
        ) {
            $transportObject['sc_carrier'] = $checkoutData['carrier'] ? $checkoutData['carrier']['name'] : null;
            $transportObject['sc_expected_delivery_date'] =
                $checkoutData['delivery_method_data'] ?
                    $checkoutData['delivery_method_data']['formatted_delivery_date'] :
                    null;
        }
    }
}
