<?php

namespace SendCloud\SendCloud\Model\Carrier;

use Magento\Framework\DataObject;
use Magento\OfflineShipping\Model\Carrier\Flatrate\ItemPriceCalculator;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use SendCloud\SendCloud\Helper\Checkout;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Xml\Security;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Shipping\Model\Simplexml\ElementFactory;
use Magento\Shipping\Model\Tracking\Result\ErrorFactory as TrackErrorFactory;
use Magento\Shipping\Model\Tracking\Result\StatusFactory;
use Magento\Shipping\Model\Tracking\ResultFactory as TrackFactory;
use Psr\Log\LoggerInterface;
use Magento\Shipping\Model\Rate\Result;

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
     * @param array $data
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
        array $data = []
    )
    {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->itemPriceCalculator = $itemPriceCalculator;
        $this->sendCloudLogger = $sendCloudLogger;
        $this->helper = $helper;
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
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        if ($this->helper->checkForScriptUrl() == false || $this->helper->checkIfModuleIsActive() == false) {
            return false;
        };

        $freeBoxes = $this->getFreeBoxesCount($request);
        $this->setFreeBoxes($freeBoxes);

        /** @var Result $result */
        $result = $this->_rateResultFactory->create();

        $shippingPrice = $this->getShippingPrice($request, $freeBoxes);

        if ($shippingPrice !== false) {
            $method = $this->createResultMethod($shippingPrice);
            $result->append($method);
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
}
