<?php

namespace SendCloud\SendCloud\Plugin\Cart;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Model\QuoteRepository;
use SendCloud\SendCloud\Logger\SendCloudLogger;

/**
 * Class CartRepository
 */
class CartRepository
{
    /** @var CartExtensionFactory */
    private $cartExtensionFactory;
    /**
     * @var SendCloudLogger
     */
    private $logger;

    /**
     * OrderRepository constructor.
     * @param CartExtensionFactory $cartExtensionFactory
     * @param SendCloudLogger $logger
     */
    public function __construct(CartExtensionFactory $cartExtensionFactory, SendCloudLogger $logger)
    {
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->logger = $logger;
    }

    /**
     * @param QuoteRepository $subject
     * @param CartInterface $cart
     * @return CartInterface
     */
    public function afterGet(QuoteRepository $subject, CartInterface $cart)
    {
        $this->loadSendCloudExtensionAttributes($cart);

        return $cart;
    }

    /**
     * @param QuoteRepository $subject
     * @param CartSearchResultsInterface $cartCollection
     * @return CartSearchResultsInterface
     */
    public function afterGetList(QuoteRepository $subject, CartSearchResultsInterface $cartCollection)
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
}
