<?php

namespace SendCloud\SendCloud\Plugin\Cart;

use Magento\Directory\Helper\Data;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Model\QuoteRepository;
use SendCloud\SendCloud\Helper\WeightConverter;
use SendCloud\SendCloud\Logger\SendCloudLogger;

/**
 * Class CartRepository
 */
class CheckoutCartRepository
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

        if ($extensionAttributes->getSendcloudCheckoutData()) {
            return $this;
        }

        try {
            $extensionAttributes->setSendcloudCheckoutData($cart->getSendcloudCheckoutData());
            $extensionAttributes->setSendcloudMultishippingData($cart->getSendcloudMultishippingData());
            $extensionAttributes->setWeightInGrams($this->getWeightInGrams($cart));

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
