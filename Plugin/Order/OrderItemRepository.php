<?php

namespace SendCloud\SendCloud\Plugin\Order;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderItemSearchResultInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;

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
     * @throws NoSuchEntityException
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
     * @throws NoSuchEntityException
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
     * @return OrderItemRepository
     * @throws NoSuchEntityException
     */
    protected function setCustomAttributes($orderItem)
    {
        try {
            $product = $this->productRepository->getById($orderItem->getProductId());
            $countryOfManufacture = $product->getData(self::COUNTRY_OF_MANUFACTURE);
            $hsCode = $product->getData(self::HS_CODE);

            $extensionAttributes = $orderItem->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();

            $extensionAttributes->setCountryOfManufacture($countryOfManufacture);
            $extensionAttributes->setHsCode($hsCode);

            $orderItem->setExtensionAttributes($extensionAttributes);
        } catch (NoSuchEntityException $e) {
            $this->logger->debug($e->getMessage());

            return $this;
        }
    }
}
