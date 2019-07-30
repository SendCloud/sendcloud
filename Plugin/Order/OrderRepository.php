<?php

namespace SendCloud\SendCloud\Plugin\Order;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Model\OrderRepository as MagentoOrderRepository;

/**
 * Class OrderRepository
 * @package SendCloud\SendCloud\Plugin\Order
 */
class OrderRepository
{
    /** @var OrderExtensionFactory */
    private $orderExtensionFactory;

    /**
     * OrderRepository constructor.
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(OrderExtensionFactory $orderExtensionFactory)
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * @param MagentoOrderRepository $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(MagentoOrderRepository $subject, OrderInterface $order)
    {
        $this->loadSendCloudExtensionAttributes($order);

        return $order;
    }

    /**
     * @param MagentoOrderRepository $subject
     * @param OrderSearchResultInterface $orderCollection
     * @return OrderSearchResultInterface
     */
    public function afterGetList(MagentoOrderRepository $subject, OrderSearchResultInterface $orderCollection)
    {
        foreach ($orderCollection->getItems() as $order) {
            $this->loadSendCloudExtensionAttributes($order);
        }

        return $orderCollection;
    }

    /**
     * @param OrderInterface $order
     * @return $this
     */
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
            $extensionAttributes->setSendcloudServicePointId($order->getSendcloudServicePointId());
            $extensionAttributes->setSendcloudServicePointName($order->getSendcloudServicePointName());
            $extensionAttributes->setSendcloudServicePointStreet($order->getSendcloudServicePointStreet());
            $extensionAttributes->setSendcloudServicePointHouseNumber($order->getSendcloudServicePointHouseNumber());
            $extensionAttributes->setSendcloudServicePointZipCode($order->getSendcloudServicePointZipCode());
            $extensionAttributes->setSendcloudServicePointCity($order->getSendcloudServicePointCity());
            $extensionAttributes->setSendcloudServicePointCountry($order->getSendcloudServicePointCountry());
        } catch (NoSuchEntityException $e) {
            return $this;
        }
    }
}