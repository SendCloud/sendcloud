<?php

namespace SendCloud\SendCloud\Block\System\Config\Modal;

use Magento\Backend\Block\Template\Context;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryMethod;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends \Magento\Config\Block\System\Config\Form\Field
{
    const SERVICE_POINT_DELIVERY = 'service_point_delivery';
    protected $_template = 'system/config/modal/data.phtml';

    private $data = [];
    private $scopeConfig;

    /**
     * @var SendcloudDeliveryMethod
     */
    private $sendcloudDeliveryMethodModel;

    /**
     * Data constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param SendcloudDeliveryMethod $sendcloudDeliveryMethodModel
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SendcloudDeliveryMethod $sendcloudDeliveryMethodModel,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->sendcloudDeliveryMethodModel = $sendcloudDeliveryMethodModel;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function getFreeShipping()
    {
        /**
         * @var DeliveryMethod $deliveryMethod
         */
        $deliveryMethod = $this->getMethodData();
        if (empty($deliveryMethod)) {
            return "";
        }

        $freeShipping = $deliveryMethod->getShippingRateData()->getFreeShipping();
        if ($freeShipping->isEnabled()) {
            return __("Free shipping above: ") . $freeShipping->getFromAmount() . ' ' . $deliveryMethod->getShippingRateData()->getCurrency();
        }

        return __("Free shipping: Not Enabled");
    }

    /**
     * @return string
     */
    public function getDefaultRate()
    {
        /**
         * @var DeliveryMethod $deliveryMethod
         */
        $deliveryMethod = $this->getMethodData();
        if (empty($deliveryMethod)) {
            return "";
        }

        $defaultRate = __("Not set");

        if ($this->isServicePoint() && $this->isServicePointLegacyRates()) {
            foreach ($deliveryMethod->getShippingRateData()->getShippingRates() as $shippingRate) {
                if ($shippingRate->isEnabled()) {
                    $defaultRate = $shippingRate->getRate();
                }
            }
        } else {
            foreach ($deliveryMethod->getShippingRateData()->getShippingRates() as $shippingRate) {
                if ($shippingRate->isDefault()) {
                    $defaultRate = $shippingRate->getRate();
                }
            }
        }

        return __("Default rate: ") . $defaultRate . ' ' . $deliveryMethod->getShippingRateData()->getCurrency();
    }

    /**
     * @return array
     */
    public function getRates()
    {
        /**
         * @var DeliveryMethod $deliveryMethod
         */
        $deliveryMethod = $this->getMethodData();
        $rates = [];
        if (empty($deliveryMethod)) {
            return $rates;
        }

        $shippingRates = $deliveryMethod->getShippingRateData();
        foreach ($shippingRates->getShippingRates() as $shippingRate) {
            if ($shippingRate->isEnabled()) {
                $rate['rate'] = "{$shippingRate->getRate()} {$deliveryMethod->getShippingRateData()->getCurrency()}";
                $minWeight = round($shippingRate->getMinWeight() / 1000);
                $maxWeight = round($shippingRate->getMaxWeight() / 1000);
                $rate['weight'] = "$minWeight - $maxWeight Kg";
                $rates[] = $rate;
            }
        }

        return $rates;
    }

    public function getCarriers()
    {
        /**
         * @var DeliveryMethod $deliveryMethod
         */
        $deliveryMethod = $this->getMethodData();
        $carriers = [];
        if (empty($deliveryMethod)) {
            return $carriers;
        }

        foreach ($deliveryMethod->getCarriers() as $carrier) {
            $carriers[] = $carrier->getName();
        }

        return $carriers;
    }

    public function isServicePointLegacyRates()
    {
        /**
         * @var DeliveryMethod $deliveryMethod
         */
        $deliveryMethod = $this->getMethodData();
        if (empty($deliveryMethod)) {
            return false;
        }

        $shippingRates = $deliveryMethod->getShippingRateData();
        foreach ($shippingRates->getShippingRates() as $shippingRate) {
            if ($shippingRate->isEnabled() && ($shippingRate->getMinWeight() === null || $shippingRate->getMaxWeight() === null)) {
                return true;
            }
        }

        return false;
    }

    public function isServicePoint()
    {
        /**
         * @var DeliveryMethod $deliveryMethod
         */
        $deliveryMethod = $this->getMethodData();
        if (empty($deliveryMethod)) {
            return false;
        }

        return $deliveryMethod->getType() === self::SERVICE_POINT_DELIVERY;
    }

    /**
     * @return string
     */
    public function getEditLink()
    {
        /**
         * @var DeliveryMethod $deliveryMethod
         */
        $deliveryMethod = $this->getMethodData();
        $integrationId = $this->getIntegrationId();

        return "https://app.sendcloud.com/v2/checkout/configurations/$integrationId/delivery-zones/{$deliveryMethod->getDeliveryZoneId()}/delivery-methods/{$deliveryMethod->getId()}?type={$deliveryMethod->getType()}";
    }

    private function getMethodData()
    {
        $entityId = $this->getEntityId();
        if (empty($this->data)) {
            $this->data = $this->sendcloudDeliveryMethodModel->select([$entityId]);
        }

        return !empty($this->data) ? $this->data[0] : null;
    }

    /**
     * @return mixed
     */
    private function getEntityId()
    {
        return $this->_request->getParam('entity_id');
    }

    /**
     * @return mixed
     */
    private function getIntegrationId()
    {
        return $this->_request->getParam('integration_id');
    }
}
