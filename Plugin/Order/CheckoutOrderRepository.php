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
class CheckoutOrderRepository
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
        $this->logger->info("SendCloud\SendCloud\Plugin\Order\CheckoutOrderRepository::afterGet(): order: " . $order->getSendcloudData());

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
            $this->logger->info("SendCloud\SendCloud\Plugin\Order\CheckoutOrderRepository::afterGetList(): order: " . $order->getSendcloudData());
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

        if ($extensionAttributes->getSendcloudData()) {
            return $this;
        }

        try {
            $extensionAttributes->setSendcloudData($order->getSendcloudData());

        } catch (NoSuchEntityException $e) {
            $this->logger->debug($e->getMessage());
        }

        return $this;
    }
}
