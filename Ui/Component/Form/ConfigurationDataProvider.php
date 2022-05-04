<?php

namespace SendCloud\SendCloud\Ui\Component\Form;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use SendCloud\SendCloud\Helper\Checkout;

/**
 * Class ConfigurationDataProvider
 * @package SendCloud\SendCloud\Ui\Component\Form
 */
class ConfigurationDataProvider extends DataProvider
{
    /**
     * @var UrlInterface
     */
    private $_urlBuilder;

    /**
     * @var Checkout
     */
    private $helper;

    /**
     * @param Checkout $helper
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
        Checkout $helper,
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
        $this->helper = $helper;
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

    public function getMeta()
    {
        $meta = parent::getMeta();
        $meta['dynamic_checkout']['children']['dynamic_checkout_grid']['arguments']['data']['config']['render_url'] = $this->_urlBuilder
            ->getUrl('mui/index/render/id/' . $this->request->getParam('store'));

        if (empty($this->request->getParam('store'))) {
            $meta['dynamic_checkout']['arguments']['data']['config']['visible'] = 0;
            $meta['dynamic_message']['arguments']['data']['config']['visible'] = 1;
            if ($this->helper->checkIfModuleIsActive($this->request->getParam('store'))) {
                $meta['general']['custom_tab_container']['html_content']['visible'] = 1;
            }
        } else {
            $meta['dynamic_checkout']['arguments']['data']['config']['visible'] = 1;
            $meta['dynamic_message']['arguments']['data']['config']['visible'] = 0;
            if ($this->helper->checkIfModuleIsActive($this->request->getParam('store'))) {
                $meta['general']['custom_tab_container']['html_content']['visible'] = 1;
            }
        }

        return $meta;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            $this->request->getParam('store') => [
                'is_active' => $this->helper->checkIfModuleIsActive($this->request->getParam('store')),
                'store_id' => $this->request->getParam('store')
            ]
        ];
    }
}
