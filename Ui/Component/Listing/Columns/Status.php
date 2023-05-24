<?php


namespace SendCloud\SendCloud\Ui\Component\Listing\Columns;

use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Status extends Column
{
    /**
     * @var SessionManagerInterface
     */
    private $session;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Status constructor.
     * @param SessionManagerInterface $session
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SessionManagerInterface $session,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->session = $session;
        $this->scopeConfig = $scopeConfig;
    }

    public function prepareDataSource(array $dataSource)
    {
        $integrationId = $this->getIntegrationId();

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');

                if ($item['rates_enabled']) {
                    $item[$name]['edit'] = ["callback" => [
                        [
                            'provider' => 'configuration_form.configuration_form.dynamic_checkout'
                                . '.rates_modal.shipping_rates_listing',
                            'target' => 'destroyInserted',
                        ],
                        [
                            'provider' => 'configuration_form.configuration_form.dynamic_checkout'
                                . '.rates_modal',
                            'target' => 'openModal',
                        ],
                        [
                            'provider' => 'configuration_form.configuration_form.dynamic_checkout'
                                . '.rates_modal.shipping_rates_listing',
                            'target' => 'render',
                            'params' => [
                                'entity_id' => $item['method_id'],
                                'integration_id' => $integrationId
                            ],
                        ],
                    ], 'label' => 'Yes', 'href' => '#'];
                } else {
                    $item[$name]['edit'] = ['label' => 'No', 'href' => "No"];
                }
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
            $integrationId = $this->scopeConfig->getValue('carriers/sendcloudcheckout/integration_id');
        } else {
            $integrationId = $this->scopeConfig->getValue(
                'carriers/sendcloudcheckout/integration_id',
                ScopeInterface::SCOPE_STORE,
                (int)$this->context->getRequestParam('id')
            );
        }
        return $integrationId;
    }
}
