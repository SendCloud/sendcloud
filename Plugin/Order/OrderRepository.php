<?php

namespace SendCloud\SendCloud\Plugin\Order;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Model\OrderRepository as MagentoOrderRepository;
use SendCloud\SendCloud\Logger\SendCloudLogger;

/**
 * Class OrderRepository
 */
class OrderRepository
{
    /** @var OrderExtensionFactory */
    private $orderExtensionFactory;

    /** @var SendCloudLogger */
    private $logger;

    /**
     * OrderRepository constructor.
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param SendCloudLogger $logger
     */
    public function __construct(OrderExtensionFactory $orderExtensionFactory, SendCloudLogger $logger)
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->logger = $logger;
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
            $extensionAttributes->setSendcloudServicePointPostnumber($order->getSendcloudServicePointPostnumber());

            $this->logger->info(
                "SendCloud\SendCloud\Plugin\Order\OrderRepository: " .
                "service point id: " . $order->getSendcloudServicePointId() .
                ", service point name: " . $order->getSendcloudServicePointName() .
                ", service point street: " . $order->getSendcloudServicePointStreet() .
                ", service point house number: " . $order->getSendcloudServicePointHouseNumber() .
                ", service point zip code: " . $order->getSendcloudServicePointZipCode() .
                ", service point city: " . $order->getSendcloudServicePointCity() .
                ", service point country: " . $order->getSendcloudServicePointCountry() .
                ", service point post number: " . $order->getSendcloudServicePointPostnumber()
            );
        } catch (NoSuchEntityException $e) {
            $this->logger->debug($e->getMessage());
        }

        return $this;
    }
}
