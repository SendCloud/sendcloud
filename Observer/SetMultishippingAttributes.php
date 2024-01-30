<?php

namespace SendCloud\SendCloud\Observer;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use SendCloud\SendCloud\Logger\SendCloudLogger;

/**
 * Class SetOrderAttributes
 */
class SetMultishippingAttributes implements ObserverInterface
{
    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var SendCloudLogger
     */
    private $logger;

    /**
     * @param Json $serializer
     * @param SendCloudLogger $logger
     */
    public function __construct(Json $serializer, SendCloudLogger $logger)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $request = $observer->getData('request');
        $quote = $observer->getData('quote');
        $shippingMethods = $request->getPost('shipping_method');
        $shippingMethodsData = $request->getPost('sc_delivery_method_data');
        $multishippingExtensionData = null;

        if ($shippingMethods === null) {
            $this->logger->info("Shipping methods missing.");

            return $this;
        }

        foreach ($shippingMethods as $key => $value) {
            if (strpos($value, 'sendcloudcheckout') !== false) {
                if (!$shippingMethodsData[$key]) {
                    throw new LocalizedException(__('Sendcloud checkout data missing'));
                }
                $shippingMethodData = $this->serializer->unserialize($shippingMethodsData[$key]);
                $this->logger->info("Shipping method data: " . json_encode($shippingMethodData));
                $this->velidateData($shippingMethodData);
                $multishippingExtensionData[$key] = $shippingMethodData;
            }
        }
        $quote->setSendcloudMultishippingData($this->serializer->serialize($multishippingExtensionData));
        $this->logger->info(
            "Sendcloud multishipping data: " . $this->serializer->serialize($multishippingExtensionData)
        );

        return $this;
    }

    /**
     * @param $shippingMethodData
     * @return void
     */
    private function velidateData($shippingMethodData): void
    {
        if ($shippingMethodData['delivery_method_type'] === 'nominated_day_delivery' &&
            (!$shippingMethodData['delivery_method_data'] || !$shippingMethodData['delivery_method_data']['delivery_date'])
        ) {
            throw new LocalizedException(__('Delivery day must be selected before order purchase. Please return to shipping and select delivery date.'));
        }
        if ($shippingMethodData['delivery_method_type'] === 'service_point_delivery' &&
            (!$shippingMethodData['delivery_method_data'] || !$shippingMethodData['delivery_method_data']['service_point_id'])
        ) {
            throw new LocalizedException(__('Service Point must be selected before order purchase. Please return to shipping and select service point.'));
        }
    }
}
