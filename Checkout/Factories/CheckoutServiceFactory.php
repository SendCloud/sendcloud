<?php

namespace SendCloud\SendCloud\Checkout\Factories;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use SendCloud\SendCloud\Checkout\Contracts\CheckoutServiceFactoryInterface;
use SendCloud\SendCloud\Checkout\Services\DeliveryMethodSetupService;
use SendCloud\SendCloud\Checkout\Services\DeliveryZoneSetupService;
use SendCloud\SendCloud\Checkout\Services\ProxyService;
use SendCloud\SendCloud\Checkout\Storage\CheckoutStorage;
use SendCloud\SendCloud\CheckoutCore\CheckoutService;
use SendCloud\SendCloud\CheckoutCore\Contracts\Proxies\CheckoutProxy;
use SendCloud\SendCloud\CheckoutCore\Services\DeliveryMethodService;
use SendCloud\SendCloud\CheckoutCore\Services\DeliveryZoneService;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryMethod;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryZone;

/**
 * Class CheckoutServiceFactory
 * @package SendCloud\SendCloud\Checkout\Factories
 */
class CheckoutServiceFactory implements CheckoutServiceFactoryInterface
{
    /**
     * @var CheckoutStorage
     */
    private $storage;
    /**
     * @var DeliveryMethodService
     */
    private $deliveryMethodService;
    /**
     * @var DeliveryZoneService
     */
    private $deliveryZoneService;
    /**
     * @var DeliveryMethodSetupService
     */
    private $deliveryMethodSetupService;
    /**
     * @var DeliveryZoneSetupService
     */
    private $deliveryZoneSetupService;
    /**
     * @var CheckoutProxy
     */
    private $proxy;

    /**
     * CheckoutServiceFactory constructor.
     * @param SendcloudDeliveryZone $sendcloudDeliveryZone
     * @param SendcloudDeliveryMethod $sendcloudDeliveryMethod
     * @param SendCloudLogger $sendCloudLogger
     */
    public function __construct(
        SendcloudDeliveryZone $sendcloudDeliveryZone,
        SendcloudDeliveryMethod $sendcloudDeliveryMethod,
        SendCloudLogger $sendCloudLogger
    ) {
        $this->storage = new CheckoutStorage($sendcloudDeliveryZone, $sendcloudDeliveryMethod, $sendCloudLogger);
        $this->deliveryMethodService = new DeliveryMethodService($this->storage);
        $this->deliveryZoneService = new DeliveryZoneService($this->storage);
        $this->deliveryMethodSetupService = new DeliveryMethodSetupService();
        $this->deliveryZoneSetupService = new DeliveryZoneSetupService();
        $this->proxy = new ProxyService();
    }

    public function make(): CheckoutService
    {
        return new CheckoutService(
            $this->deliveryZoneService,
            $this->deliveryZoneSetupService,
            $this->deliveryMethodService,
            $this->deliveryMethodSetupService,
            $this->proxy
        );
    }
}
