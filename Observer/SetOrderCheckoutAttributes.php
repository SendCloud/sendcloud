<?php

namespace SendCloud\SendCloud\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\QuoteRepository;

/**
 * Class SetOrderAttributes
 */
class SetOrderCheckoutAttributes implements ObserverInterface
{
    private $quoteRepository;

    /**
     * SetOrderAttributes constructor.
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();

        try {
            $quote = $this->quoteRepository->get($order->getQuoteId());
        } catch (NoSuchEntityException $e) {
            return $this;
        }

        if ($order->getShippingMethod() && strpos($order->getShippingMethod(), 'sendcloud_checkout') !== false && !$order->getSendcloudData()) {
            $order->setSendcloudData($quote->getSendcloudCheckoutData());
        }

        return $this;
    }
}
