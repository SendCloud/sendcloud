<?php

namespace SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryZone;

use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SendCloud\SendCloud\Model\SendcloudDeliveryZone;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryZone as SendcloudResourceDeliveyZoneResourceModel;

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
    protected function _construct(): void
    {
        $this->_init(
            SendcloudDeliveryZone::class,
            SendcloudResourceDeliveyZoneResourceModel::class
        );
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
     * @return $this|Collection
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
