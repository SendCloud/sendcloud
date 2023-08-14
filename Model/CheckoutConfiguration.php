<?php


namespace SendCloud\SendCloud\Model;

use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Config\Scope;
use Magento\Framework\Exception\NoSuchEntityException;
use SendCloud\SendCloud\Api\CheckoutConfigurationInterface;
use SendCloud\SendCloud\Checkout\Contracts\CheckoutConfiguratorFactoryInterface;
use SendCloud\SendCloud\Checkout\Factories\CheckoutConfiguratorFactory;
use SendCloud\SendCloud\CheckoutCore\Exceptions\Domain\FailedToDeleteCheckoutConfigurationException;
use SendCloud\SendCloud\CheckoutCore\Exceptions\Domain\FailedToUpdateCheckoutConfigurationException;
use SendCloud\SendCloud\CheckoutCore\Exceptions\ValidationException;
use SendCloud\SendCloud\CheckoutCore\HTTP\Request;
use SendCloud\SendCloud\Exceptions\SendcloudException;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryMethod;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryZone;
use Sendcloud\Shipping\Checkout\Factories\Default_Checkout_Configurator_Factory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;
use SendCloud\SendCloud\Helper\Data;

class CheckoutConfiguration implements CheckoutConfigurationInterface
{
    /** @var WriterInterface */
    private $writer;

    /** @var SendCloudLogger */
    private $logger;

    /**
     * @var CheckoutConfiguratorFactoryInterface
     */
    protected $configuratorFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ReinitableConfigInterface
     */
    private $scopeConfig;
    /**
     * Checkout_Api_Service constructor.
     */
    public function __construct(
        ReinitableConfigInterface $scopeConfig,
        WriterInterface $writer,
        SendCloudLogger $logger,
        SendcloudDeliveryZone $sendcloudDeliveryZone,
        SendcloudDeliveryMethod $sendcloudDeliveryMethod,
        StoreManagerInterface $storeManager,
        Data $helper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->writer = $writer;
        $this->configuratorFactory =
            new CheckoutConfiguratorFactory($sendcloudDeliveryZone, $sendcloudDeliveryMethod, $storeManager, $helper);
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }

    /**
     * Activate checkout integration
     *
     * @param int $store_view_id
     * @param mixed $checkout_configuration
     * @return array|mixed
     * @throws ValidationException
     * @throws FailedToUpdateCheckoutConfigurationException
     */
    public function update($store_view_id, $checkout_configuration)
    {
        $request = new Request(['checkout_configuration' => $checkout_configuration], []);
        $this->setStore($store_view_id);

        $configurator = $this->configuratorFactory->make();

        try {
            $configurator->update($request);
            $this->writer->save(
                'carriers/sendcloudcheckout/active',
                1,
                ScopeInterface::SCOPE_STORES,
                $store_view_id
            );
            $this->writer->save(
                'carriers/sendcloudcheckout/integration_id',
                $checkout_configuration['integration_id'],
                ScopeInterface::SCOPE_STORES,
                $store_view_id
            );
            $this->scopeConfig->reinit();
        } catch (ValidationException $e) {
            throw new SendcloudException(
                __('Failed to update checkout configuration.'),
                0,
                \Magento\Framework\Webapi\Exception::HTTP_BAD_REQUEST,
                $this->formatErrorData($e->getValidationErrors())
            );
        } catch (\Exception $e) {
            $this->logger->error(('Failed to update checkout configuration: ' . $e->getMessage()));
            throw new \Magento\Framework\Webapi\Exception(
                __('Invalid checkout payload.'),
                0,
                \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR
            );
        }

        return ['message' => 'Configuration updated'];
    }

    /**
     * @param int $store_view_id
     * @return string[]
     * @throws ValidationException
     * @throws FailedToDeleteCheckoutConfigurationException
     */
    public function delete($store_view_id)
    {
        $configurator = $this->configuratorFactory->make();
        $this->setStore($store_view_id);

        try {
            $configurator->deleteAll(new Request([], []));

            $this->writer->save(
                'carriers/sendcloudcheckout/active',
                0,
                ScopeInterface::SCOPE_STORES,
                $store_view_id
            );
            $this->writer->save(
                'carriers/sendcloudcheckout/integration_id',
                '',
                ScopeInterface::SCOPE_STORES,
                $store_view_id
            );
            $this->scopeConfig->reinit();
        } catch (\Exception $e) {
            $this->logger->error('Failed to delete checkout configuration: ' . $e->getMessage());
            throw new \Magento\Framework\Webapi\Exception(
                __('Failed to delete checkout configuration.'),
                0,
                \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR
            );
        }

        return ['message' => 'Configuration deleted'];
    }

    /**
     * @param int $store_view_id
     * @return string[]
     * @throws ValidationException
     * @throws FailedToDeleteCheckoutConfigurationException
     */
    public function deleteIntegration($store_view_id)
    {
        try {
            $this->delete($store_view_id);
            $this->writer->save(
                'sendcloud/general/enable',
                0,
                ScopeInterface::SCOPE_STORES,
                $store_view_id
            );
            $this->scopeConfig->reinit();
        } catch (\Exception $e) {
            $this->logger->error('Failed to delete integration: ' . $e->getMessage());
            throw new \Magento\Framework\Webapi\Exception(
                __('Failed to delete integration.'),
                0,
                \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR
            );
        }

        return ['message' => 'Integration deleted'];
    }

    /**
     * Set current store if exist
     *
     * @param int $store_view_id
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    private function setStore(int $store_view_id)
    {
        if (!$this->isValidStoreId($store_view_id)) {
            throw new ValidationException(['Invalid store view id']);
        }
        $this->storeManager->setCurrentStore($this->storeManager->getStore($store_view_id));
    }

    /**
     * Formats error response.
     *
     * @param array $data
     *
     * @return array
     */
    private function formatErrorData(array $data)
    {
        $details = array_map(function ($item) {
            return [
                'path' => [$item['path']],
                'message' => $item['message'],
            ];
        }, $data);

        return ['errors' => $details];
    }

    /**
     * @param int $store_view_id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function isValidStoreId(int $store_view_id): bool
    {
        $stores = $this->storeManager->getStores();
        foreach ($stores as $store) {
            if ((int)$store->getId() === $store_view_id) {
                return true;
            }
        }
        return false;
    }
}
