<?php

namespace SendCloud\SendCloud\Block\Checkout;

use Magento\Directory\Helper\Data;
use Magento\Framework\Filter\DataObject\GridFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Multishipping\Block\Checkout\Shipping;
use Magento\Store\Model\ScopeInterface;
use SendCloud\SendCloud\Helper\WeightConverter;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use SendCloud\SendCloud\Plugin\Quote\Address\Rate;

class MultiShipping extends Shipping
{
    /**
     * @var Data
     */
    private $magentoHelper;

    /**
     * SendCloudLogger
     */
    private $sendcloudLogger;

    /**
     * @param SendCloudLogger $sendCloudLogger
     * @param Data $magentoHelper
     * @param Context $context
     * @param GridFactory $filterGridFactory
     * @param \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        SendCloudLogger $sendCloudLogger,
        Data $magentoHelper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Filter\DataObject\GridFactory $filterGridFactory,
        \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping,
        \Magento\Tax\Helper\Data $taxHelper,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($context, $filterGridFactory, $multishipping, $taxHelper, $priceCurrency, $data);
        $this->magentoHelper = $magentoHelper;
        $this->sendcloudLogger = $sendCloudLogger;
    }

    public function getMethods()
    {
        $addresses = $this->getAddresses();
        $codes = [];
        foreach ($addresses as $address) {
            $rates = $address->getAllShippingRates();

            /**
             * @var Rate $rate
             */
            foreach ($rates as $rate) {
                if (strpos($rate->getCode(), 'sendcloudcheckout') === 0) {
                    $codes[$rate->getCode()] = $rate->getMethod();
                }
            }
        }

        $this->sendcloudLogger->info("SendCloud\SendCloud\Block\Checkout\MultiShipping::getMethods(): " . json_encode($codes));

        return $codes;
    }

    public function getAddressesFormated()
    {
        $addresses = $this->getAddresses();
        $formated = [];

        foreach ($addresses as $address) {
            $data = $address->getData();
            $data['base_currency_code'] = $address->getQuote()->getBaseCurrencyCode();
            $quoteExtensionAttributes = $address->getQuote()->getExtensionAttributes();
            $data['weight_in_grams'] = WeightConverter::convertWeightToGrams($address->getWeight(), $this->magentoHelper->getWeightUnit());
            $formated[$address->getData('address_id')] = $data;
        }

        $this->sendcloudLogger->info("SendCloud\SendCloud\Block\Checkout\MultiShipping::getAddressesFormated(): " . json_encode($formated));

        return $formated;
    }
}
