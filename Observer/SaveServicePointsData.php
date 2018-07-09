<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 12-4-18
 * Time: 15:39
 */

namespace SendCloud\SendCloud\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;

class SaveServicePointsData implements ObserverInterface
{
    private $quoteRepository;

    public function __construct(
        QuoteRepository $quoteRepository
    )
    {
        $this->quoteRepository = $quoteRepository;
    }

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