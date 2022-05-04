<?php

namespace SendCloud\SendCloud\Ui\Component\Form;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class ModalDataProvider extends DataProvider
{
    public function getData()
    {
        return [];
    }
}
