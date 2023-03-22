<?php

namespace SendCloud\SendCloud\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;

/**
 * Class SaveServicePointsData
 */
class SaveCheckoutData implements ObserverInterface
{
    private $quoteRepository;

    /**
     * SaveServicePointsData constructor.
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(QuoteRepository $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $quote = $this->quoteRepository->get($order->getQuoteId());
        if ($order->getShippingMethod() && strpos($order->getShippingMethod(), 'sendcloud_checkout') !== false && !$order->getSendcloudData()) {
            $order->setSendcloudData($quote->getSendcloudCheckoutData());
        }

        return $this;
    }
}
