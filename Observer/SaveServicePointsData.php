<?php

namespace SendCloud\SendCloud\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;

/**
 * Class SaveServicePointsData
 * @package SendCloud\SendCloud\Observer
 */
class SaveServicePointsData implements ObserverInterface
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
        $order->setSendcloudServicePointId($quote->getSendcloudServicePointId());
        $order->setSendcloudServicePointName($quote->getSendcloudServicePointName());
        $order->setSendcloudServicePointStreet($quote->getSendcloudServicePointStreet());
        $order->setSendcloudServicePointHouseNumber($quote->getSendcloudServicePointHouseNumber());
        $order->setSendcloudServicePointZipCode($quote->getSendcloudServicePointZipCode());
        $order->setSendcloudServicePointCity($quote->getSendcloudServicePointCity());
        $order->setSendcloudServicePointCountry($quote->getSendcloudServicePointCountry());

        return $this;
    }
}
