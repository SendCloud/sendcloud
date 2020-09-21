<?php

namespace SendCloud\SendCloud\Plugin\Order;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderItemSearchResultInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use Exception;

class OrderItemRepository
{
    const HS_CODE = 'hs_code';
    const COUNTRY_OF_MANUFACTURE = 'country_of_manufacture';

    /**
     * @var OrderItemExtensionFactory
     */
    private $extensionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SendCloudLogger
     */
    private $logger;

    /**
     * OrderItemRepository constructor.
     * @param OrderItemExtensionFactory $extensionFactory
     * @param ProductRepositoryInterface $productRepository
     * @param SendCloudLogger $logger
     */
    public function __construct(OrderItemExtensionFactory $extensionFactory, ProductRepositoryInterface $productRepository, SendCloudLogger $logger)
    {
        $this->extensionFactory = $extensionFactory;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    /**
     * @param OrderItemRepositoryInterface $subject
     * @param OrderItemInterface $orderItem
     * @return OrderItemInterface
     */
    public function afterGet(OrderItemRepositoryInterface $subject, OrderItemInterface $orderItem)
    {
        $this->setCustomAttributes($orderItem);

        return $orderItem;
    }

    /**
     * @param OrderItemRepositoryInterface $subject
     * @param OrderItemSearchResultInterface $searchResult
     * @return OrderItemSearchResultInterface
     */
    public function afterGetList(OrderItemRepositoryInterface $subject, OrderItemSearchResultInterface $searchResult)
    {
        $orderItems = $searchResult->getItems();

        foreach ($orderItems as $orderItem) {
            $this->setCustomAttributes($orderItem);
        }

        return $searchResult;
    }

    /**
     * @param $orderItem
     * @return bool
     */
    protected function setCustomAttributes($orderItem)
    {
        $product = $this->productRepository->getById($orderItem->getProductId());
        $countryOfManufacture = $product->getData(self::COUNTRY_OF_MANUFACTURE);
        $hsCode = $product->getData(self::HS_CODE);

        $extensionAttributes = $orderItem->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();

        try {
            $extensionAttributes->setCountryOfManufacture($countryOfManufacture);
            $extensionAttributes->setHsCode($hsCode);

            $orderItem->setExtensionAttributes($extensionAttributes);
        } catch (Exception $e) {
            return $this->logger->debug($e->getMessage());
        }
    }
}
