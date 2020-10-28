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
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Shipping\Model\Simplexml\ElementFactory;
use Magento\Shipping\Model\Tracking\Result\ErrorFactory as TrackErrorFactory;
use Magento\Shipping\Model\Tracking\Result\StatusFactory;
use Magento\Shipping\Model\Tracking\ResultFactory as TrackFactory;
use Psr\Log\LoggerInterface;
use SendCloud\SendCloud\Helper\Checkout;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use SendCloud\SendCloud\Model\ResourceModel\Carrier\ServicepointrateFactory;

/**
 * Class SendCloud
 * @package SendCloud\SendCloud\Model\Carrier
 */
class SendCloud extends AbstractCarrierOnline implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'sendcloud';

    //begin: SEN-166
    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var string
     */
    protected $_defaultConditionName = 'package_fixed';

    /**
     * @var array
     */
    protected $_conditionNames = [];

    /**
     * @var ServicepointrateFactory
     */
    protected $_servicepiontrateFactory;

    //end: SEN-166

    /** @var ResultFactory */
    private $_rateResultFactory;

    /** @var SendCloudLogger */
    private $sendCloudLogger;

    /**
     * @var ItemPriceCalculator
     */
    private $itemPriceCalculator;

    /**
     * @var Checkout
     */
    private $helper;

    /**
     * SendCloud constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param Security $xmlSecurity
     * @param ElementFactory $xmlElFactory
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param TrackFactory $trackFactory
     * @param TrackErrorFactory $trackErrorFactory
     * @param StatusFactory $trackStatusFactory
     * @param RegionFactory $regionFactory
     * @param CountryFactory $countryFactory
     * @param CurrencyFactory $currencyFactory
     * @param Data $directoryData
     * @param StockRegistryInterface $stockRegistry
     * @param ItemPriceCalculator $itemPriceCalculator
     * @param SendCloudLogger $sendCloudLogger
     * @param Checkout $helper
     * @param ServicepointrateFactory $servicepointrateFactory
     * @param array $data
     * @throws LocalizedException
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        Security $xmlSecurity,
        ElementFactory $xmlElFactory,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        TrackFactory $trackFactory,
        TrackErrorFactory $trackErrorFactory,
        StatusFactory $trackStatusFactory,
        RegionFactory $regionFactory,
        CountryFactory $countryFactory,
        CurrencyFactory $currencyFactory,
        Data $directoryData,
        StockRegistryInterface $stockRegistry,
        ItemPriceCalculator $itemPriceCalculator,
        SendCloudLogger $sendCloudLogger,
        Checkout $helper,
        ServicepointrateFactory $servicepointrateFactory,
        array $data = []
    )
    {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->itemPriceCalculator = $itemPriceCalculator;
        $this->sendCloudLogger = $sendCloudLogger;
        $this->helper = $helper;
        $this->_servicepiontrateFactory = $servicepointrateFactory;
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $xmlSecurity,
            $xmlElFactory,
            $rateResultFactory,
            $rateMethodFactory,
            $trackFactory,
            $trackErrorFactory,
            $trackStatusFactory,
            $regionFactory,
            $countryFactory,
            $currencyFactory,
            $directoryData,
            $stockRegistry,
            $data
        );
        foreach ($this->getCode('condition_name') as $k => $v) {
            $this->_conditionNames[] = $k;
        }
    }

    /**
     * @param DataObject $request
     * @return DataObject|void
     */
    protected function _doShipmentRequest(DataObject $request)
    {
    }

    /**
     * get allowed methods
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['sendcloud' => $this->getConfigData('name')];
    }

    /**
     * @param RateRequest $request
     * @return bool|Result|DataObject|null
     * @throws LocalizedException
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        if ($this->helper->checkForScriptUrl() == false || $this->helper->checkIfModuleIsActive() == false) {
            return false;
        };

        if (!$request->getConditionName()) {
            $conditionName = $this->getConfigData('sen_condition_name');
            $request->setConditionName($conditionName ? $conditionName : $this->_defaultConditionName);
        }

        if($request->getConditionName() !== $this->_defaultConditionName) {

            // exclude Virtual products price from Package value if pre-configured
            if (!$this->getConfigFlag('sen_include_virtual_price') && $request->getAllItems()) {
                foreach ($request->getAllItems() as $item) {
                    if ($item->getParentItem()) {
                        continue;
                    }
                    if ($item->getHasChildren() && $item->isShipSeparately()) {
                        foreach ($item->getChildren() as $child) {
                            if ($child->getProduct()->isVirtual()) {
                                $request->setPackageValue($request->getPackageValue() - $child->getBaseRowTotal());
                            }
                        }
                    } elseif ($item->getProduct()->isVirtual()) {
                        $request->setPackageValue($request->getPackageValue() - $item->getBaseRowTotal());
                    }
                }
            }

            // Free shipping by qty
            $freeQty = 0;
            $freePackageValue = 0;
            $freeBoxes = 0;
            if ($request->getAllItems()) {
                foreach ($request->getAllItems() as $item) {
                    if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                        continue;
                    }

                    if ($item->getHasChildren() && $item->isShipSeparately()) {
                        foreach ($item->getChildren() as $child) {
                            if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                                $freeShipping = is_numeric($child->getFreeShipping()) ? $child->getFreeShipping() : 0;
                                $freeQty += $item->getQty() * ($child->getQty() - $freeShipping);
                                $freeQty += $item->getQty() * ($child->getQty() - $freeShipping);
                                $freeBoxes += $item->getQty() * $child->getQty();
                            }
                        }
                    } elseif ($item->getFreeShipping() || $item->getAddress()->getFreeShipping()) {
                        $freeShipping = $item->getFreeShipping() ?
                            $item->getFreeShipping() : $item->getAddress()->getFreeShipping();
                        $freeShipping = is_numeric($freeShipping) ? $freeShipping : 0;
                        $freeQty += $item->getQty() - $freeShipping;
                        $freePackageValue += $item->getBaseRowTotal();
                        $freeBoxes += $item->getQty();
                    }
                }
                $oldValue = $request->getPackageValue();
                $newPackageValue = $oldValue - $freePackageValue;
                $request->setPackageValue($newPackageValue);
                $request->setPackageValueWithDiscount($newPackageValue);
            }

            $this->setFreeBoxes($freeBoxes);

            // Package weight and qty free shipping
            $oldWeight = $request->getPackageWeight();
            $oldQty = $request->getPackageQty();

            $request->setPackageWeight($request->getFreeMethodWeight());
            $request->setPackageQty($oldQty - $freeQty);

            /** @var \Magento\Shipping\Model\Rate\Result $result */
            $result = $this->_rateResultFactory->create();
            $rate = $this->getRate($request);

            $request->setPackageWeight($oldWeight);
            $request->setPackageQty($oldQty);

            if (!empty($rate) && $rate['price'] >= 0) {
                if ($request->getPackageQty() == $freeQty) {
                    $shippingPrice = 0;
                } else {
                    $shippingPrice = $this->getFinalPriceWithHandlingFee($rate['price']);
                }
                $method = $this->createShippingMethod($shippingPrice, $rate['cost']);
                $result->append($method);
            } elseif ($request->getPackageQty() == $freeQty) {

                /**
                 * Promotion rule was applied for the whole cart.
                 *  In this case all other shipping methods could be omitted
                 * Table rate shipping method with 0$ price must be shown if grand total is more than minimal value.
                 * Free package weight has been already taken into account.
                 */
                $request->setPackageValue($freePackageValue);
                $request->setPackageQty($freeQty);
                $rate = $this->getRate($request);
                if (!empty($rate) && $rate['price'] >= 0) {
                    $method = $this->createShippingMethod(0, 0);
                    $result->append($method);
                }
            } else {
                /** @var \Magento\Quote\Model\Quote\Address\RateResult\Error $error */
                $error = $this->_rateErrorFactory->create(
                    [
                        'data' => [
                            'carrier' => $this->_code,
                            'carrier_title' => $this->getConfigData('title'),
                            'error_message' => $this->getConfigData('specificerrmsg'),
                        ],
                    ]
                );
                $result->append($error);
            }

        }
        else { //default fixed behaviour

            $freeBoxes = $this->getFreeBoxesCount($request);
            $this->setFreeBoxes($freeBoxes);

            /** @var Result $result */
            $result = $this->_rateResultFactory->create();

            $shippingPrice = $this->getShippingPrice($request, $freeBoxes);

            if ($shippingPrice !== false) {
                $method = $this->createResultMethod($shippingPrice);
                $amount = $this->getConfigData('price');
                if ($this->getConfigData('free_shipping_enable') && $this->getConfigData('free_shipping_subtotal') <= $request->getBaseSubtotalInclTax()) {
                    $method->setPrice('0.00');
                    $method->setCost('0.00');
                } else {
                    $method->setPrice($amount);
                    $method->setCost($amount);
                }
                $result->append($method);
            }
        }

        return $result;
    }

    /**
     * @param RateRequest $request
     * @param $freeBoxes
     * @return float|string
     */
    private function getShippingPrice(RateRequest $request, $freeBoxes)
    {
        $configPrice = $this->getConfigData('price');

        $shippingPrice = $this->itemPriceCalculator->getShippingPricePerOrder($request, $configPrice, $freeBoxes);
        $shippingPrice = $this->getFinalPriceWithHandlingFee($shippingPrice);

        if ($shippingPrice !== false && $request->getPackageQty() == $freeBoxes) {
            $shippingPrice = '0.00';
        }
        return $shippingPrice;
    }

    /**
     * @param $shippingPrice
     * @param $cost
     * @return ShippingMethodInterface
     */
    private function createShippingMethod($shippingPrice, $cost)
    {
        /** @var ShippingMethodInterface $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($shippingPrice);
        $method->setCost($cost);
        return $method;
    }

    /**
     * @param $shippingPrice
     * @return ShippingMethodInterface
     */
    private function createResultMethod($shippingPrice)
    {
        /** @var ShippingMethodInterface $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);
        return $method;
    }

    /**
     * @param DataObject $request
     * @return bool
     */
    public function proccessAdditionalValidation(DataObject $request)
    {
        return $this->processAdditionalValidation($request);
    }

    /**
     * @param DataObject $request
     * @return bool
     */
    public function processAdditionalValidation(DataObject $request)
    {
        return true;
    }

    /**
     * @param RateRequest $request
     * @return int
     */
    private function getFreeBoxesCount(RateRequest $request)
    {
        $freeBoxes = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    $freeBoxes += $this->getFreeBoxesCountFromChildren($item);
                } elseif ($item->getFreeShipping()) {
                    $freeBoxes += $item->getQty();
                }
            }
        }
        return $freeBoxes;
    }

    /**
     * @param mixed $item
     * @return mixed
     */
    private function getFreeBoxesCountFromChildren($item)
    {
        $freeBoxes = 0;
        foreach ($item->getChildren() as $child) {
            if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                $freeBoxes += $item->getQty() * $child->getQty();
            }
        }
        return $freeBoxes;
    }

    /**
     * Get code.
     *
     * @param string $type
     * @param string $code
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCode($type, $code = '')
    {
        $codes = [
            'condition_name' => [
                'package_fixed' => __('Fixed fee shipping price'),
                'package_weight' => __('Weight vs. Destination'),
                'package_value_with_discount' => __('Price vs. Destination'),
                'package_qty' => __('# of Items vs. Destination'),
            ],
            'condition_name_short' => [
                'package_fixed' => __('Fixed fee shipping'),
                'package_weight' => __('Weight (and above)'),
                'package_value_with_discount' => __('Order Subtotal (and above)'),
                'package_qty' => __('# of Items (and above)'),
            ],
        ];

        if (!isset($codes[$type])) {
            throw new LocalizedException(
                __('The "%1" code type for Sendcloud Servicepoint is incorrect. Verify the type and try again.', $type)
            );
        }

        if ('' === $code) {
            return $codes[$type];
        }

        if (!isset($codes[$type][$code])) {
            throw new LocalizedException(
                __('The "%1: %2" code type for Sendcloud Servicepoint is incorrect. Verify the type and try again.', $type, $code)
            );
        }

        return $codes[$type][$code];
    }

    /**
     * Get rate.
     *
     * @param RateRequest $request
     * @return mixed
     * @throws LocalizedException
     */
    public function getRate(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        return $this->_servicepiontrateFactory->create()->getRate($request);
    }
}
