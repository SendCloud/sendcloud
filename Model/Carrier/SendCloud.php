<?php

namespace SendCloud\SendCloud\Model\Carrier;

use Magento\Framework\DataObject;
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
use Magento\Framework\Locale\FormatInterface;
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
     * @param FormatInterface $localeFormat
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
        FormatInterface $localeFormat,
        SendCloudLogger $sendCloudLogger,
        Checkout $helper,
        array $data = []
    )
    {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
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

    protected function _doShipmentRequest(DataObject $request)
    {
    }

    /**
     * get allowed methods
     * @return array
     */
    public function getAllowedMethods()
    {
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

        /** @var Result $result */
        $result = $this->_rateResultFactory->create();

        /** @var ShippingMethodInterface $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $amount = $this->getConfigData('price');

        if ($this->getConfigData('free_shipping_enable')
            && $this->getConfigData('free_shipping_subtotal') <= $request->getBaseSubtotalInclTax()
        ) {
            $method->setPrice('0.00');
            $method->setCost('0.00');
        } else {
            $method->setPrice($amount);
            $method->setCost($amount);
        }

        $result->append($method);

        return $result;
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
}
