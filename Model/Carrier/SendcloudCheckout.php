<?php


namespace SendCloud\SendCloud\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use SendCloud\SendCloud\CheckoutCore\Contracts\Storage\CheckoutStorage;
use SendCloud\SendCloud\CheckoutCore\Contracts\Utility\WeightUnits;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\Order;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\OrderItem;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\Weight;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod;
use SendCloud\SendCloud\CheckoutCore\Domain\Search\Query;
use SendCloud\SendCloud\CheckoutCore\Services\DeliveryMethodService;
use SendCloud\SendCloud\CheckoutCore\Services\DeliveryZoneService;
use SendCloud\SendCloud\Helper\Checkout;
use Magento\Directory\Helper\Data;
use SendCloud\SendCloud\Helper\WeightConverter;

class SendcloudCheckout extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'sendcloud_checkout';

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var ResultFactory
     */
    private $rateResultFactory;

    /**
     * @var MethodFactory
     */
    private $rateMethodFactory;

    /**
     * @var DeliveryZoneService
     */
    private $deliveryZoneService;

    /**
     * @var DeliveryMethodService
     */
    private $deliveryMethodService;

    /**
     * @var CheckoutStorage
     */
    private $checkoutStorage;
    /**
     * @var Checkout
     */
    private $helper;
    /**
     * @var Data
     */
    private $magentoHelper;

    /**
     * @param Data $magentoHelper
     * @param Checkout $helper
     * @param CheckoutStorage $checkoutStorage
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param DeliveryZoneService $deliveryZoneService
     * @param DeliveryMethodService $deliveryMethodService
     * @param array $data
     */
    public function __construct(
        Data $magentoHelper,
        Checkout $helper,
        CheckoutStorage $checkoutStorage,
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        DeliveryZoneService $deliveryZoneService,
        DeliveryMethodService $deliveryMethodService,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->helper = $helper;
        $this->magentoHelper = $magentoHelper;
        $this->checkoutStorage = $checkoutStorage;
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->deliveryZoneService = $deliveryZoneService;
        $this->deliveryMethodService = $deliveryMethodService;
    }

    /**
     * Custom Shipping Rates Collector
     *
     * @param RateRequest $request
     * @return Result|bool
     * @throws \Exception
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        if (!$this->helper->checkIfModuleIsActive($request->getStoreId()) || !$this->helper->checkIfChekoutIsActive($request->getStoreId())) {
            return false;
        }

        /** @var Result $result */
        $result = $this->rateResultFactory->create();

        $query = new Query();

        $query->setCountry($request->getDestCountryId());

        $deliveryZones = $this->deliveryZoneService->search($query);

        if (empty($deliveryZones)) {
            return false;
        }

        $deliveryZoneIds = [];

        foreach ($deliveryZones as $deliveryZone) {
            $deliveryZoneIds[] = $deliveryZone->getId();
        }

        $deliveryMethods = $this->deliveryMethodService->findInZones($deliveryZoneIds);

        foreach ($deliveryMethods as $deliveryMethod) {
            if ($deliveryMethod->isAvailable($this->getOrder($request))) {

                $method = $this->createShippingMethod($deliveryMethod, $request);

                if ($this->isFreeShipping($deliveryMethod, $request)) {
                    $method->setPrice('0.00');
                    $method->setCost('0.00');
                }
                $result->append($method);
            }

        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedMethods(): array
    {
        $methods = [];

        foreach ($this->checkoutStorage->findAllMethodConfigs() as $deliveryMethod) {
            $methods[$deliveryMethod->getId()] = $deliveryMethod->getExternalTitle();
        }

        return $methods;
    }

    /**
     * Create shipping method
     *
     * @param DeliveryMethod $deliveryMethod
     * @param RateRequest $request
     * @return Method
     */
    protected function createShippingMethod(DeliveryMethod $deliveryMethod, RateRequest $request): Method
    {
        /** @var Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('name'));

        $method->setMethod($deliveryMethod->getId());
        $method->setMethodTitle($deliveryMethod->getExternalTitle());
        $shippingCost = $this->getRate($deliveryMethod, $request);

        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);

        $method->setMethodDescription($deliveryMethod->getDescription());
        $method->setData('methodConfiguration', $deliveryMethod->getRawConfig());

        return $method;
    }

    /**
     * Get shipping rate price
     *
     * @param DeliveryMethod $deliveryMethod
     * @param RateRequest $request
     * @return float|string
     */
    protected function getRate(DeliveryMethod $deliveryMethod, RateRequest $request)
    {
        $shippingCost = (float)$this->getConfigData('price');

        if ($deliveryMethod->getShippingRateData()->isEnabled()) {

            $shippingRates = $deliveryMethod->getShippingRateData()->getShippingRates();
            $defaultRate = null;
            foreach ($shippingRates as $shippingRate) {
                $packageWeight = WeightConverter::convertWeightToGrams($request->getPackageWeight(), $this->magentoHelper->getWeightUnit());
                if (!$shippingRate->isEnabled()) {
                    continue;
                }
                if (($packageWeight >= $shippingRate->getMinWeight() && $packageWeight < $shippingRate->getMaxWeight()) ||
                    ($shippingRate->getMinWeight() === null && $shippingRate->getMaxWeight() === null)
                ) {
                    return $shippingRate->getRate();
                }

                if ($shippingRate->isDefault()) {
                    $defaultRate = $shippingRate->getRate();
                }
            }

            if ($defaultRate) {
                return $defaultRate;
            }
        }

        return $shippingCost;
    }

    /**
     * Is free shipping condition satisfied
     *
     * @param DeliveryMethod $deliveryMethod
     * @param RateRequest $request
     */
    protected function isFreeShipping(DeliveryMethod $deliveryMethod, RateRequest $request): bool
    {
        if ($deliveryMethod->getShippingRateData()->getFreeShipping()->isEnabled() &&
            (float)$deliveryMethod->getShippingRateData()->getFreeShipping()->getFromAmount() < $request->getPackageValueWithDiscount()) {
            return true;
        }

        if ($request->getFreeShipping()) {
            return true;
        }

        return (float)$request->getPackageWeight() !== (float)$request->getFreeMethodWeight() && (float)$request->getFreeMethodWeight() === 0;
    }

    /**
     * @param RateRequest $request
     * @return Order
     */
    protected function getOrder(RateRequest $request): Order
    {
        return new Order(
            '',
            [new OrderItem('', new Weight(WeightConverter::convertWeightToGrams($request->getPackageWeight(), $this->magentoHelper->getWeightUnit()), WeightUnits::GRAM), 1)]
        );
    }
}
