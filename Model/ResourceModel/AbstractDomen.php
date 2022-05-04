<?php

namespace SendCloud\SendCloud\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class AbstractDomen
 * @package SendCloud\SendCloud\Model\ResourceModel
 */
abstract class AbstractDomen extends AbstractDb
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * AbstractDomen constructor.
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     * @param string|null $connectionName
     */
    public function __construct(StoreManagerInterface $storeManager, Context $context, $connectionName = null)
    {
        $this->storeManager = $storeManager;
        parent::__construct($context, $connectionName);
    }

    /**
     * Set resource model table name.
     *
     * @param string $tableName Name of the database table.
     */
    public function setTableName($tableName)
    {
        $this->_init($tableName, 'id');
    }

    /**
     * Provides all domain entities.
     *
     * @return array
     * @throws LocalizedException
     */
    public function selectAll(): array
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('store_view_id=?', $this->storeManager->getStore()->getId());

        $result = $connection->fetchAll($select);
        return array_map([$this, 'transformToEntity'], $result);
    }

    /**
     * Finds domain object with external id.
     *
     * @param array $externalIds
     * @return array
     * @throws LocalizedException
     */
    public function select(array $externalIds): array
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('external_id IN (?)', $externalIds);

        $result = $connection->fetchAll($select);

        return array_map([$this, 'transformToEntity'], $result);
    }

    /**
     * Deletes all domain entities.
     *
     * @throws LocalizedException
     */
    public function deleteAll()
    {
        $connection = $this->getConnection();
        $rows = $connection->delete(
            $this->getMainTable(),
            ["store_view_id={$this->storeManager->getStore()->getId()}"]
        );
    }

    /**
     * Deletes domain entities.
     *
     * @param array $externalIds
     * @return void
     * @throws LocalizedException
     */
    public function deleteOne(array $externalIds)
    {
        if (empty($externalIds)) {
            return;
        }

        $connection = $this->getConnection();

        $externalIds = array_map(function ($id) {
            return "'" . $id . "'";
        }, $externalIds);

        $ids = implode(',', $externalIds);

        $connection->delete(
            $this->getMainTable(),
            ["external_id IN ($ids)"]
        );
    }

    /**
     * Create domen entity
     *
     * @param array $objects
     * @param array $additionalData
     * @return mixed
     */
    abstract public function create(array $objects, array $additionalData = []);

    /**
     * Update domen entity
     *
     * @param array $objects
     * @param array $additionalData
     * @return mixed
     */
    abstract public function update(array $objects, array $additionalData = []);

    /**
     * Transforms raw data to domain object.
     *
     * @param array $raw
     *
     * @return object
     */
    abstract protected function transformToEntity(array $raw);
}
