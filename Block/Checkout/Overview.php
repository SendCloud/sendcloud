<?php

namespace SendCloud\SendCloud\Block\Checkout;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Model\Quote\Address;

class Overview extends \Magento\Multishipping\Block\Checkout\Overview
{
    /**
     * @var Json
     */
    private $serializer;

    public function __construct(
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
    }

    /**
     * Get shipping address rate
     *
     * @param Address $address
     * @return bool
     */
    public function getShippingAddressSendcloudData($address)
    {
        $quote = $this->getQuote();
        $multishippingData = $this->serializer->unserialize($quote->getData('sendcloud_multishipping_data'));
        if ($multishippingData && array_key_exists($address->getId(), $multishippingData)) {
            return $multishippingData[$address->getId()];
        }
    }
}
