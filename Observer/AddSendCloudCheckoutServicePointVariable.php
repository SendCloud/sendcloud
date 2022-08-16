<?php

namespace SendCloud\SendCloud\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Api\Data\OrderInterface;

class AddSendCloudCheckoutServicePointVariable implements ObserverInterface
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

        if ($checkoutData['delivery_method_type'] === 'service_point_delivery') {
            $transportObject['sc_servicepoint_id'] = $checkoutData['delivery_method_data']['service_point']['id'];
            $transportObject['sc_servicepoint_name'] = $checkoutData['delivery_method_data']['service_point']['name'];
            $transportObject['sc_servicepoint_street'] = $checkoutData['delivery_method_data']['service_point']['street'];
            $transportObject['sc_servicepoint_house_no'] = $checkoutData['delivery_method_data']['service_point']['house_number'];
            $transportObject['sc_servicepoint_zipcode'] = $checkoutData['delivery_method_data']['service_point']['postal_code'];
            $transportObject['sc_servicepoint_city'] = $checkoutData['delivery_method_data']['service_point']['city'];
            $transportObject['sc_servicepoint_post_no'] = $checkoutData['delivery_method_data']['post_number'];
        }
    }
}
