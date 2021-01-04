<?php

namespace SendCloud\SendCloud\Model\ResourceModel\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Backend\App\Action;
use Magento\Store\Model\StoreManagerInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use SendCloud\SendCloud\Model\Carrier\SendCloud;
use SendCloud\SendCloud\Model\ResourceModel\Carrier\Servicepointrate\Import;
use SendCloud\SendCloud\Model\ResourceModel\Carrier\Servicepointrate\RateQuery;
use SendCloud\SendCloud\Model\ResourceModel\Carrier\Servicepointrate\RateQueryFactory;


/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @api
 * @since 100.0.2
 */
class Servicepointrate extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Import Service point rates website ID
     *
     * @var int
     */
    protected $_importWebsiteId = 0;

    /**
     * Errors in import process
     *
     * @var array
     */
    protected $_importErrors = [];

    /**
     * Count of imported Service point rates
     *
     * @var int
     */
    protected $_importedRows = 0;

    /**
     * Array of unique table rate keys to protect from duplicates
     *
     * @var array
     */
    protected $_importUniqueHash = [];

    /**
     * Array of countries keyed by iso2 code
     *
     * @var array
     */
    protected $_importIso2Countries;

    /**
     * Array of countries keyed by iso3 code
     *
     * @var array
     */
    protected $_importIso3Countries;

    /**
     * Associative array of countries and regions
     * [country_id][region_code] = region_id
     *
     * @var array
     */
    protected $_importRegions;

    /**
     * Import Table Rate condition name
     *
     * @var string
     */
    protected $_importConditionName;

    /**
     * Array of condition full names
     *
     * @var array
     */
    protected $_conditionFullNames = [];

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     * @since 100.1.0
     */
    protected $coreConfig;

    /**
     * @var SendCloudLogger
     */
    protected $logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     * @since 100.1.0
     */
    protected $storeManager;

    /**
     * @var \SendCloud\SendCloud\Model\ResourceModel\Carrier\Servicepointrate
     * @since 100.1.0
     */
    protected $carrierServicepointrate;

    /**
     * Filesystem instance
     *
     * @var \Magento\Framework\Filesystem
     * @since 100.1.0
     */
    protected $filesystem;

    /**
     * @var Import
     */
    private $import;

    /**
     * @var RateQueryFactory
     */
    private $rateQueryFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Servicepointrate constructor.
     * @param Context $context
     * @param SendCloudLogger $logger
     * @param ScopeConfigInterface $coreConfig
     * @param StoreManagerInterface $storeManager
     * @param SendCloud $carrierServicepointrate
     * @param Filesystem $filesystem
     * @param RateQueryFactory $rateQueryFactory
     * @param Import $import
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        SendCloudLogger $logger,
        ScopeConfigInterface $coreConfig,
        StoreManagerInterface $storeManager,
        SendCloud $carrierServicepointrate,
        Filesystem $filesystem,
        Import $import,
        RateQueryFactory $rateQueryFactory,
        Action $action,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->coreConfig = $coreConfig;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->carrierServicepointrate = $carrierServicepointrate;
        $this->filesystem = $filesystem;
        $this->import = $import;
        $this->rateQueryFactory = $rateQueryFactory;
        $this->request = $action;
    }

    /**
     * Define main table and id field name
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sendcloud_servicepointrate', 'pk');
    }

    /**
     * Return table rate array or false by rate request
     *
     * @param \Magento\Quote\Model\Quote\Address\RateRequest $request
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRate(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from($this->getMainTable());
        /** @var RateQuery $rateQuery */
        $rateQuery = $this->rateQueryFactory->create(['request' => $request]);

        $rateQuery->prepareSelect($select);
        $bindings = $rateQuery->getBindings();

        $result = $connection->fetchRow($select, $bindings);
        // Normalize destination zip code
        if ($result && $result['dest_zip'] == '*') {
            $result['dest_zip'] = '';
        }

        return $result;
    }

    /**
     * @param array $condition
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function deleteByCondition(array $condition)
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();
        $connection->delete($this->getMainTable(), $condition);
        $connection->commit();
        return $this;
    }

    /**
     * @param array $fields
     * @param array $values
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    private function importData(array $fields, array $values)
    {

        $connection = $this->getConnection();
        $connection->beginTransaction();

        try {
            if (count($fields) && count($values)) {

                $this->getConnection()->insertArray($this->getMainTable(), $fields, $values);
                $this->_importedRows += count($values);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $connection->rollBack();
            throw new \Magento\Framework\Exception\LocalizedException(__('Unable to import data'), $e);
        } catch (\Exception $e) {
            $connection->rollBack();
            $this->logger->critical($e);
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while importing Sendcloud servicepoint rates.')
            );
        }
        $connection->commit();
    }

    /**
     * Upload SendCloud Servicepoint rate file and import data from it
     *
     * @param \Magento\Framework\DataObject $object
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \SendCloud\SendCloud\Model\ResourceModel\Carrier\Servicepointrate
     * @todo: this method should be refactored as soon as updated design will be provided
     * @see https://wiki.corp.x.com/display/MCOMS/Magento+Filesystem+Decisions
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function uploadAndImport(\Magento\Framework\DataObject $object)
    {
        $request   = $this->getController()->getRequest();
        $files = $request->getFiles()->toArray();
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info(var_export($files,true));
        if(!isset($files['groups']['sendcloud']['fields']['sen_import']['value'])){
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while importing Sendcloud Servicepoint rates.')
            );
        }
        $filePath = $files['groups']['sendcloud']['fields']['sen_import']['value'];
        $websiteId = $this->storeManager->getWebsite($object->getScopeId())->getId();
        $conditionName = $this->getSenConditionName($object);

        $file = $this->getCsvFile($filePath);
        try {
            // delete old data by website and condition name
            $condition = [
                'website_id = ?' => $websiteId,
                'sen_condition_name = ?' => $conditionName,
            ];
            $this->deleteByCondition($condition);

            $columns = $this->import->getColumns();
            $conditionFullName = $this->_getConditionFullName($conditionName);
            foreach ($this->import->getData($file, $websiteId, $conditionName, $conditionFullName) as $bunch) {
                $this->importData($columns, $bunch);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while importing Sendcloud Servicepoint rates.')
            );
        } finally {
            $file->close();
        }

        if ($this->import->hasErrors()) {
            $error = __(
                'We couldn\'t import this file because of these errors: %1',
                implode(" \n", $this->import->getErrors())
            );
            throw new \Magento\Framework\Exception\LocalizedException($error);
        }
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return mixed|string
     * @since 100.1.0
     */
    public function getSenConditionName(\Magento\Framework\DataObject $object)
    {
        if ($object->getData('groups/sendcloud/fields/sen_condition_name/inherit') == '1') {
            $conditionName = (string)$this->coreConfig->getValue('carriers/sendcloud/sen_condition_name', 'default');
        } else {
            $conditionName = $object->getData('groups/sendcloud/fields/sen_condition_name/value');
        }
        return $conditionName;
    }

    /**
     * @param string $filePath
     * @return Filesystem\File\ReadInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function getCsvFile($filePath)
    {
        $pathInfo = pathinfo($filePath);
        $dirName = isset($pathInfo['dirname']) ? $pathInfo['dirname'] : '';
        $fileName = isset($pathInfo['basename']) ? $pathInfo['basename'] : '';

        $directoryRead = $this->filesystem->getDirectoryReadByPath($dirName);

        return $directoryRead->openFile($fileName);
    }


    /**
     * Return import condition full name by condition name code
     *
     * @param string $conditionName
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getConditionFullName($conditionName)
    {
        if (!isset($this->_conditionFullNames[$conditionName])) {
            $name = $this->carrierServicepointrate->getCode('sen_condition_name_short', $conditionName);
            $this->_conditionFullNames[$conditionName] = $name;
        }

        return $this->_conditionFullNames[$conditionName];
    }

    /**
     * Save import data batch
     *
     * @param array $data
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _saveImportData(array $data)
    {
        if (!empty($data)) {
            $columns = [
                'website_id',
                'dest_country_id',
                'dest_region_id',
                'dest_zip',
                'sen_condition_name',
                'condition_value',
                'price',
            ];
            $this->getConnection()->insertArray($this->getMainTable(), $columns, $data);
            $this->_importedRows += count($data);
        }

        return $this;
    }
}
