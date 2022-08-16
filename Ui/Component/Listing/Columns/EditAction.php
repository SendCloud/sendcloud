<?php


namespace SendCloud\SendCloud\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use SendCloud\SendCloud\Api\DeliveryMethodNames;

class EditAction extends Column
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * EditAction constructor.
     * @param $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $integrationId = $this->getIntegrationId();

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                $type = array_search($item['delivery_method'], DeliveryMethodNames::NAMES);

                $item[$name]['edit'] = [
                    'href' => "https://panel.sendcloud.sc/v2/checkout/configurations/$integrationId/delivery-zones/{$item['zone_id']}/delivery-methods/{$item['method_id']}?type=$type",
                    'label' => __('Edit in Sendcloud'),
                    'target' => '_blank',
                ];
            }

        }

        return $dataSource;
    }

    /**
     * @return mixed
     */
    protected function getIntegrationId()
    {
        if ($this->context->getRequestParam('id') === null) {
            $integrationId = $this->scopeConfig->getValue('carriers/sendcloud_checkout/integration_id');
        } else {
            $integrationId = $this->scopeConfig->getValue(
                'carriers/sendcloud_checkout/integration_id',
                ScopeInterface::SCOPE_STORE,
                (int)$this->context->getRequestParam('id')
            );
        }
        return $integrationId;
    }
}
