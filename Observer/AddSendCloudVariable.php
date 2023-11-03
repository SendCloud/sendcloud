<?php

namespace SendCloud\SendCloud\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Api\Data\OrderInterface;

class AddSendCloudVariable implements ObserverInterface
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
        if ($transportObject === null) {
            return;
        }

        $this->order = $transportObject->getOrder();
        if ($this->order === null) {
            return;
        }

        if ($this->order->getSendcloudServicePointId()) {
            $this->getServicePointVariables($transportObject);
        } else if ($this->order->getSendcloudCheckoutData()) {
            $this->getCheckoutData($transportObject);
        }
    }

    private function getServicePointVariables($transportObject)
    {
        /** @var OrderInterface $order */
        $order = $this->order;

        // Set Sendcloud Service Point variables
        $transportObject['sc_servicepoint_id'] = $order->getSendcloudServicePointId();
        $transportObject['sc_servicepoint_name'] = $order->getSendcloudServicePointName();
        $transportObject['sc_servicepoint_street'] = $order->getSendcloudServicePointStreet();
        $transportObject['sc_servicepoint_house_no'] = $order->getSendcloudServicePointHouseNumber();
        $transportObject['sc_servicepoint_zipcode'] = $order->getSendcloudServicePointZipCode();
        $transportObject['sc_servicepoint_city'] = $order->getSendcloudServicePointCity();
        $transportObject['sc_servicepoint_post_no'] = $order->getSendcloudServicePointPostnumber();
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
        } else if ($checkoutData['delivery_method_type'] === 'service_point_delivery') {
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
