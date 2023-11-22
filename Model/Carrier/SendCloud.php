<?php

namespace SendCloud\SendCloud\Model\Carrier;

use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Xml\Security;
use Magento\OfflineShipping\Model\Carrier\Flatrate\ItemPriceCalculator;
use Magento\OfflineShipping\Model\Carrier\Tablerate;
use Magento\OfflineShipping\Model\ResourceModel\Carrier\TablerateFactory;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Shipping\Model\Simplexml\ElementFactory;
use Magento\Shipping\Model\Tracking\Result\StatusFactory;
use SendCloud\SendCloud\Helper\Checkout;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use SendCloud\SendCloud\Model\ResourceModel\Carrier\ServicepointrateFactory;

/**
 * Class SendCloud
 */
class SendCloud extends Tablerate
{

    /**
     * @var string
     */
    protected $_code = 'sendcloud';

    /**
     * @var string
     */
    protected $_defaultConditionName = 'package_fixed';

    /**
     * @var ServicepointrateFactory
     */
    protected $_servicepiontrateFactory;

    /**
     * @var Checkout
     */
    private $helper;

    /**
     * @var SendCloudLogger
     */
    private $sendcloudLogger;

    /**
     * SendCloud constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param SendCloudLogger $sendCloudLogger
     * @param Checkout $helper
     * @param ServicepointrateFactory $servicepointrateFactory
     * @param array $data
     * @throws LocalizedException
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        SendCloudLogger $sendCloudLogger,
        Checkout $helper,
        ServicepointrateFactory $servicepointrateFactory,
        TablerateFactory $tablerateFactory,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->_servicepiontrateFactory = $servicepointrateFactory;
        $this->sendcloudLogger = $sendCloudLogger;
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $sendCloudLogger,
            $rateResultFactory,
            $rateMethodFactory,
            $tablerateFactory,
            $data
        );
    }

    /**
     * @inheritdoc
     */
    public function getAllowedMethods()
    {
        $this->sendcloudLogger->info("SendCloud\SendCloud\Model\Carrier\Sendcloud::getAllowedMethods: " . $this->getConfigData('name'));

        return ['sendcloud' => $this->getConfigData('name')];
    }

    /**
     * @inheritdoc
     */
    public function getRate(RateRequest $request)
    {
        $this->sendcloudLogger->info("SendCloud\SendCloud\Model\Carrier\Sendcloud::getRate() request: " . json_encode($request->toArray()));

        return $this->_servicepiontrateFactory->create()->getRate($request);
    }

    /**
     * @inheritdoc
     */
    public function getCode($type, $code = '')
    {
        $this->sendcloudLogger->info("SendCloud\SendCloud\Model\Carrier\Sendcloud::getCode(): type: " . $type . ', code: ' . $code);

        $codes = [
            'condition_name' => [
                'package_fixed' => __('Fixed fee shipping price')
            ],
            'condition_name_short' => [
                'package_fixed' => __('Fixed fee shipping')
            ],
        ];

        if (!isset($codes[$type])) {
            throw new LocalizedException(
                __('The "%1" code type for Sendcloud Servicepoint is incorrect. Verify the type and try again.', $type)
            );
        }

        if ('' === $code) {
            return array_merge($codes[$type], parent::getCode($type));
        }

        if ($code === $this->_defaultConditionName) {
            return $codes[$type][$code];
        }

        return parent::getCode($type, $code);
    }

    /**
     * @inheritdoc
     */
    public function collectRates(RateRequest $request)
    {
        $this->sendcloudLogger->info("SendCloud\SendCloud\Model\Carrier\Sendcloud::collectRates() request: " . json_encode($request->toArray()));

        if (!$this->getConfigFlag('active')) {
            return false;
        }

        if (!$this->helper->checkForScriptUrl() || !$this->helper->checkIfModuleIsActive()) {
            return false;
        }

        $conditionName = $this->getConfigData('condition_name');
        $request->setConditionName($conditionName ?: $this->_defaultConditionName);

        return $this->isFlatRateRequest($request) ? $this->collectFlatRate($request) : $this->collectTableRates($request);
    }

    /**
     * @param RateRequest $request
     * @return bool
     */
    private function isFlatRateRequest(RateRequest $request): bool
    {
        return $request->getConditionName() == $this->_defaultConditionName || !$request->getConditionName();
    }

    /**
     * @param RateRequest $request
     * @return Result
     */
    private function collectFlatRate(RateRequest $request): Result
    {
        /** @var Result $result */
        $result = $this->_rateResultFactory->create();
        $amount = $this->getConfigData('price');

        if ($amount === false) {
            return $result;
        }

        $method = $this->createShippingMethod($amount, $amount);
        if ($this->getConfigData('free_shipping_enable') &&
            $this->getConfigData('free_shipping_subtotal') <= $request->getBaseSubtotalInclTax()) {
            $method->setPrice('0.00');
            $method->setCost('0.00');
        }
        $method->setData('methodType', 'service_point_legacy');
        $result->append($method);

        return $result;
    }

    /**
     * @param RateRequest $request
     * @return mixed
     */
    private function collectTableRates(RateRequest $request)
    {
        $request = clone $request;

        /**
         * Since Tablerate directly change package value in request object without restoring the initial value,
         * it is necessary to set old package value in request object.
         */
        $this->setOldPackageValue($request);

        $result = parent::collectRates($request);

        /** @var ShippingMethodInterface[] $method */
        foreach ($result->getRatesByCarrier($this->_code) as $method) {
            $method->setMethod($this->_code);
            $method->setData('methodType', 'service_point_legacy');
        }

        //Magento 2.3.2 version bug fix
        /** @var ShippingMethodInterface[] $method */
        foreach ($result->getRatesByCarrier('tablerate') as $method) {
            $method->setMethod($this->_code);
            $method->setCarrier($this->_code);
        }

        return $result;
    }

    /**
     * @param $shippingPrice
     * @param $cost
     * @return ShippingMethodInterface
     */
    private function createShippingMethod($shippingPrice, $cost)
    {
        /** @var ShippingMethodInterface $method */
        $method = $this->_resultMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($shippingPrice);
        $method->setCost($cost);

        return $method;
    }

    /**
     * @param RateRequest $request
     */
    private function setOldPackageValue(RateRequest $request)
    {
        if ($request->getPackageValue() === 0.0 && $request->getPackageValue() !== $request->getPackagePhysicalValue()) {
            $request->setPackageValue($request->getPackagePhysicalValue());
            $request->setPackageValueWithDiscount($request->getPackagePhysicalValue());
        }
    }
}
