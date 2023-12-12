<?php

namespace SendCloud\SendCloud\Plugin\Cart;

use Magento\Directory\Helper\Data;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use SendCloud\SendCloud\Helper\WeightConverter;
use SendCloud\SendCloud\Logger\SendCloudLogger;

class CartRepository
{
    /**
     * @var CartExtensionFactory
     */
    private $cartExtensionFactory;
    /**
     * @var SendCloudLogger
     */
    private $logger;
    /**
     * @var Data
     */
    private $magentoHelper;

    /**
     * OrderRepository constructor.
     * @param CartExtensionFactory $cartExtensionFactory
     * @param SendCloudLogger $logger
     */
    public function __construct(CartExtensionFactory $cartExtensionFactory, SendCloudLogger $logger, Data $magentoHelper)
    {
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->logger = $logger;
        $this->magentoHelper = $magentoHelper;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $cart
     * @return CartInterface
     */
    public function afterGet(CartRepositoryInterface $subject, CartInterface $cart)
    {
        $this->loadSendCloudExtensionAttributes($cart);

        return $cart;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartSearchResultsInterface $cartCollection
     * @return CartSearchResultsInterface
     */
    public function afterGetList(CartRepositoryInterface $subject, CartSearchResultsInterface $cartCollection)
    {
        foreach ($cartCollection->getItems() as $cart) {
            $this->loadSendCloudExtensionAttributes($cart);
        }

        return $cartCollection;
    }

    /**
     * @param CartInterface $cart
     * @return $this
     */
    private function loadSendCloudExtensionAttributes(CartInterface $cart)
    {
        $extensionAttributes = $cart->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->cartExtensionFactory->create();
        }

        if ($extensionAttributes->getSendcloudServicePointId() !== null) {
            return $this;
        }

        try {
            $extensionAttributes->setSendcloudServicePointId($cart->getSendcloudServicePointId());
            $extensionAttributes->setSendcloudServicePointName($cart->getSendcloudServicePointName());
            $extensionAttributes->setSendcloudServicePointStreet($cart->getSendcloudServicePointStreet());
            $extensionAttributes->setSendcloudServicePointHouseNumber($cart->getSendcloudServicePointHouseNumber());
            $extensionAttributes->setSendcloudServicePointZipCode($cart->getSendcloudServicePointZipCode());
            $extensionAttributes->setSendcloudServicePointCity($cart->getSendcloudServicePointCity());
            $extensionAttributes->setSendcloudServicePointCountry($cart->getSendcloudServicePointCountry());
            $extensionAttributes->setSendcloudServicePointPostnumber($cart->getSendcloudServicePointPostnumber());

        } catch (NoSuchEntityException $e) {
            $this->logger->debug($e->getMessage());
        }

        return $this;
    }

    /**
     * @param CartInterface $cart
     * @return float|int
     */
    private function getWeightInGrams(CartInterface $cart)
    {
        $items = $cart->getItems();
        $weight = 0;
        if ($items) {
            foreach ($items as $item) {
                $weight += $item->getQty() * (float)$item->getData('weight');
            }
        }

        return WeightConverter::convertWeightToGrams($weight, $this->magentoHelper->getWeightUnit());
    }
}
