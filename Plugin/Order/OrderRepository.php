<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 5-4-18
 * Time: 10:42
 */

namespace SendCloud\SendCloud\Plugin\Order;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Model\OrderRepository as MagentoOrderRepository;

class OrderRepository
{
    /** @var OrderExtensionFactory  */
    private $orderExtensionFactory;

    public function __construct(
        OrderExtensionFactory $orderExtensionFactory
    )
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    public function afterGet(MagentoOrderRepository $subject, OrderInterface $order)
    {
        $this->loadSendCloudExtensionAttributes($order);

        return $order;
    }

    public function afterGetList(MagentoOrderRepository $subject, OrderSearchResultInterface $orderCollection)
    {
        foreach ($orderCollection->getItems() as $order) {
            $this->loadSendCloudExtensionAttributes($order);
        }

        return $orderCollection;
    }

    private function loadSendCloudExtensionAttributes(OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        if ($extensionAttributes->getSendcloudServicePointId() !== null) {
            return $this;
        }

        try {
            $extensionAttributes->setSendcloudServicePointId($order->getData('sendcloud_service_point_id'));
            $extensionAttributes->setSendcloudServicePointName($order->getData('sendcloud_service_point_name'));
            $extensionAttributes->setSendcloudServicePointStreet($order->getData('sendcloud_service_point_street'));
            $extensionAttributes->setSendcloudServicePointHouseNumber($order->getData('sendcloud_service_point_house_number'));
            $extensionAttributes->setSendcloudServicePointZipCode($order->getData('sendcloud_service_point_zip_code'));
            $extensionAttributes->setSendcloudServicePointCity($order->getData('sendcloud_service_point_city'));
            $extensionAttributes->setSendcloudServicePointCountry($order->getData('sendcloud_service_point_country'));
        } catch (NoSuchEntityException $e) {
            return $this;
        }
    }
}