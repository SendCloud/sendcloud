<?php

namespace SendCloud\SendCloud\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;
use SendCloud\SendCloud\Logger\SendCloudLogger;

class SaveServicePointsData implements ObserverInterface
{
    private $quoteRepository;

    /**
     * @var SendCloudLogger
     */
    private $logger;

    /**
     * SaveServicePointsData constructor.
     * @param QuoteRepository $quoteRepository
     * @param SendCloudLogger $logger
     */
    public function __construct(QuoteRepository $quoteRepository, SendCloudLogger $logger)
    {
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $quote = $this->quoteRepository->get($order->getQuoteId());
        if ($order->getShippingMethod() === 'sendcloud_sendcloud') {
            $order->setSendcloudServicePointId($quote->getSendcloudServicePointId());
            $order->setSendcloudServicePointName($quote->getSendcloudServicePointName());
            $order->setSendcloudServicePointStreet($quote->getSendcloudServicePointStreet());
            $order->setSendcloudServicePointHouseNumber($quote->getSendcloudServicePointHouseNumber());
            $order->setSendcloudServicePointZipCode($quote->getSendcloudServicePointZipCode());
            $order->setSendcloudServicePointCity($quote->getSendcloudServicePointCity());
            $order->setSendcloudServicePointCountry($quote->getSendcloudServicePointCountry());
            $order->setSendcloudServicePointPostnumber($quote->getSendcloudServicePointPostnumber());

            $this->logger->info("Saved service point info: " . json_encode([
                    "id" => $quote->getSendcloudServicePointId(),
                    "name" => $quote->getSendcloudServicePointName(),
                    "street" => $quote->getSendcloudServicePointStreet(),
                    "house number" => $quote->getSendcloudServicePointHouseNumber(),
                    "zip code" => $quote->getSendcloudServicePointZipCode(),
                    "city" => $quote->getSendcloudServicePointCity(),
                    "country" => $quote->getSendcloudServicePointCountry(),
                    "post number" => $quote->getSendcloudServicePointPostnumber()
                ]));
        } elseif ($order->getShippingMethod() && strpos($order->getShippingMethod(), 'sendcloudcheckout') !== false && !$order->getSendcloudData()) {
            $order->setSendcloudData($quote->getSendcloudCheckoutData());

            $this->logger->info("Saved service point info: " . $quote->getSendcloudCheckoutData());
        }

        return $this;
    }
}
