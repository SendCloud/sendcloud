<?php


namespace SendCloud\SendCloud\Checkout\Factories;

use Magento\Store\Model\StoreManagerInterface;
use SendCloud\SendCloud\Checkout\Contracts\CheckouConfiguratiorFactoryInterface;
use SendCloud\SendCloud\Checkout\Contracts\CheckoutConfiguratorFactoryInterface;
use SendCloud\SendCloud\Checkout\Contracts\CheckoutServiceFactoryInterface;
use SendCloud\SendCloud\Checkout\Services\CurrencyService;
use SendCloud\SendCloud\CheckoutCore\Configurator;
use SendCloud\SendCloud\CheckoutCore\Validators\NullRequestValidator;
use SendCloud\SendCloud\CheckoutCore\Validators\UpdateRequestValidator;
use SendCloud\SendCloud\Helper\Data;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryMethod;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryZone;

class CheckoutConfiguratorFactory implements CheckoutConfiguratorFactoryInterface
{
    protected $_moduleList;
    /**
     * @var CheckoutServiceFactoryInterface
     */
    private $serviceFactory;
    /**
     * @var RequestValidator
     */
    private $updateRequestValidator;
    /**
     * @var RequestValidator
     */
    private $deleteRequestValidator;
    /**
     * Default_Checkout_Service_Factory constructor.
     */
    public function __construct(
        SendcloudDeliveryZone $sendcloudDeliveryZone,
        SendcloudDeliveryMethod $sendcloudDeliveryMethod,
        StoreManagerInterface $storeManager,
        Data $helper
    ) {
        $this->serviceFactory = new CheckoutServiceFactory($sendcloudDeliveryZone, $sendcloudDeliveryMethod);
        $this->updateRequestValidator = new UpdateRequestValidator($helper->getVersion(), new CurrencyService($storeManager));
        $this->deleteRequestValidator = new NullRequestValidator();
    }

    public function make(): Configurator
    {
        return new Configurator(
            $this->updateRequestValidator,
            $this->deleteRequestValidator,
            $this->serviceFactory->make()
        );
    }
}
