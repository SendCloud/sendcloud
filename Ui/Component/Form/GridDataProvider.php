<?php

namespace SendCloud\SendCloud\Ui\Component\Form;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\DocumentInterface;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\StoreManagerInterface;
use SendCloud\SendCloud\Api\DeliveryMethodNames;
use SendCloud\SendCloud\Model\SendcloudDeliveryMethod;

class GridDataProvider extends DataProvider
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var UrlInterface
     */
    private $_urlBuilder;

    /**
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface $urlBuilder
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->_urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    protected function searchResultToOutput(SearchResultInterface $searchResult)
    {
        $arrItems = [];

        $arrItems['items'] = [];
        foreach ($searchResult->getItems() as $item) {
            $arrItems['items'][] = $this->createGridItem($item);
        }

        $arrItems['totalRecords'] = $searchResult->getTotalCount();

        return $arrItems;
    }

    public function getMeta()
    {
        $meta = parent::getMeta();
        $this->data['config']['update_url'] =
            $this->_urlBuilder->getUrl('mui/index/render/id/' . $this->request->getParam('id'));

        $this->storeManager->setCurrentStore($this->request->getParam('id'));

        return $meta;
    }

    /**
     * @param SendcloudDeliveryMethod $item
     * @return array
     */
    protected function createGridItem(SendcloudDeliveryMethod $item): array
    {
        $payload = json_decode($item->getData('data'), true);
        $ratesEnabled = isset($payload['shipping_rate_data']['enabled']) && $payload['shipping_rate_data']['enabled'] === true;
        $shippingProduct = isset($payload['shipping_product']) ? $payload['shipping_product']['name'] : '';
        return [
            'country' => $item->getData('country'),
            'internal_title' => $payload['internal_title'],
            'public_title' => $payload['external_title'],
            'shipping_product' => $shippingProduct,
            'rates_enabled' => $ratesEnabled,
            'delivery_method' => DeliveryMethodNames::NAMES[$payload['delivery_method_type']],
            'method_id' => $payload['id'],
            'zone_id' => $item->getData('delivery_zone_id')
        ];
    }
}
