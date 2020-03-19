<?php

namespace SendCloud\SendCloud\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\QuoteRepository;

/**
 * Class SetOrderAttributes
 * @package SendCloud\SendCloud\Observer
 */
class SetOrderAttributes implements ObserverInterface
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

        $order->setSendcloudServicePointId($quote->getSendcloudServicePointId());
        $order->setSendcloudServicePointName($quote->getSendcloudServicePointName());
        $order->setSendcloudServicePointStreet($quote->getSendcloudServicePointStreet());
        $order->setSendcloudServicePointHouseNumber($quote->getSendcloudServicePointHouseNumber());
        $order->setSendcloudServicePointZipCode($quote->getSendcloudServicePointZipCode());
        $order->setSendcloudServicePointCity($quote->getSendcloudServicePointCity());
        $order->setSendcloudServicePointCountry($quote->getSendcloudServicePointCountry());
        $order->setSendcloudServicePointPostnumber($quote->getSendcloudServicePointPostnumber());

        return $this;
    }
}
