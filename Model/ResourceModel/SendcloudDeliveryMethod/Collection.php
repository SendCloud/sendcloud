<?php

namespace SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryMethod;

use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SendCloud\SendCloud\Model\SendcloudDeliveryMethod;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryMethod as SendcloudResourceDeliveyMethodResourceModel;

class Collection extends AbstractCollection implements SearchResultInterface
{
    /**
     * @var AggregationInterface
     */
    private $aggregations;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            SendcloudDeliveryMethod::class,
            SendcloudResourceDeliveyMethodResourceModel::class
        );
    }

    /**
     * @inheritDoc
     * @return Collection|void
     */

    protected function _initSelect()
    {
        return parent::_initSelect();
    }

    /**
     * @inheritDoc
     * @return AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @inheritDoc
     * @param AggregationInterface $aggregations
     *
     * @return \SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryMethod\Collection|Collection
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;

        return $this;
    }

    /**
     * @inheritDoc
     * @return null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * @inheritDoc
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return $this|Collection
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria)
    {
        return $this;
    }

    /**
     * @inheritDoc
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * @inheirtDoc
     * @param int $totalCount
     *
     * @return $this|Collection.
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * @inheritDoc
     * @param array|null $items
     *
     * @return $this|Collection
     */
    public function setItems(array $items = null)
    {
        return $this;
    }
}
