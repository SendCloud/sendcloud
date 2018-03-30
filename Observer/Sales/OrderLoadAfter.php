<?php
namespace CreativeICT\SendCloud\Observer\Sales;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class OrderLoadAfter implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->getOrderExtensionDependency();
        }

        $extensionAttributes->setSendcloudServicePointId($order->getData('sendcloud_service_point_id'));
        $extensionAttributes->setSendcloudServicePointName($order->getData('sendcloud_service_point_name'));
        $extensionAttributes->setSendcloudServicePointStreet($order->getData('sendcloud_service_point_street'));
        $extensionAttributes->setSendcloudServicePointHouseNumber($order->getData('sendcloud_service_point_house_number'));
        $extensionAttributes->setSendcloudServicePointZipCode($order->getData('sendcloud_service_point_zip_code'));
        $extensionAttributes->setSendcloudServicePointCity($order->getData('sendcloud_service_point_city'));

        $order->setExtensionAttributes($extensionAttributes);
    }

    private function getOrderExtensionDependency()
    {
        // TODO: Volgens mij hoeft dit niet via de bject manager. Je kan hem volgens mij gewoon mee geven in de construct
        $orderExtension = \Magento\Framework\App\ObjectManager::getInstance()->get(
            '\Magento\Sales\Api\Data\OrderExtension'
        );

        return $orderExtension;
    }
}