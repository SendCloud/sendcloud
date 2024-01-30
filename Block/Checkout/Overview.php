<?php

namespace SendCloud\SendCloud\Block\Checkout;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Model\Quote\Address;
use SendCloud\SendCloud\Logger\SendCloudLogger;

class Overview extends \Magento\Multishipping\Block\Checkout\Overview
{
    /**
     * @var Json
     */
    private $serializer;

    /**
     * SendCloudLogger
     */
    private $sendcloudLogger;

    public function __construct(
        SendCloudLogger $sendCloudLogger,
        Json $serializer,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping,
        \Magento\Tax\Helper\Data $taxHelper,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector,
        \Magento\Quote\Model\Quote\TotalsReader $totalsReader,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $multishipping,
            $taxHelper,
            $priceCurrency,
            $totalsCollector,
            $totalsReader,
            $data
        );
        $this->serializer = $serializer;
        $this->sendcloudLogger = $sendCloudLogger;
    }

    /**
     * Get shipping address rate
     *
     * @param Address $address
     * @return bool
     */
    public function getShippingAddressSendcloudData($address)
    {
        $this->sendcloudLogger->info("SendCloud\SendCloud\Block\Checkout\Overview::getShippingAddressSendcloudData(): " . json_encode($address->toArray()));

        $quote = $this->getQuote();
        $multishippingData = $this->serializer->unserialize($quote->getData('sendcloud_multishipping_data'));
        if ($multishippingData && array_key_exists($address->getId(), $multishippingData)) {
            return $multishippingData[$address->getId()];
        }
    }
}
