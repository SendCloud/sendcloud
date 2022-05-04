<?php

namespace SendCloud\SendCloud\Model\ResourceModel\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\App\RequestInterface;
use Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate;
use Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\RateQueryFactory;
use Magento\Store\Model\StoreManagerInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use SendCloud\SendCloud\Model\Carrier\SendCloud;
use SendCloud\SendCloud\Model\ResourceModel\Carrier\Servicepointrate\FilesystemDecorator;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @api
 * @since 100.0.2
 */
class Servicepointrate extends Tablerate
{
    /**
     * @var Filesystem
     */
    protected $filesystem;
    /**
     * @var Tablerate\Import
     */
    private $import;

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
     * @param Tablerate\Import $import
     * @param RateQueryFactory $rateQueryFactory
     * @param RequestInterface $request
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        SendCloudLogger $logger,
        ScopeConfigInterface $coreConfig,
        StoreManagerInterface $storeManager,
        SendCloud $carrierServicepointrate,
        Filesystem $filesystem,
        Tablerate\Import $import,
        RateQueryFactory $rateQueryFactory,
        RequestInterface $request,
        $connectionName = null
    ) {
        /**
         * Since Import have private uniqueHash variable without getters and setters whose value is not cleared
         * after inserting the file, we cloned the object because we cannot insert files for servicepoint and tablerates
         * with the same rows
         * @see \Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\Import
         */
        $servicePointImport = clone $import;

        parent::__construct(
            $context,
            $logger,
            $coreConfig,
            $storeManager,
            $carrierServicepointrate,
            $filesystem,
            $servicePointImport,
            $rateQueryFactory,
            $connectionName
        );
        $this->request = $request;
        $this->import = $servicePointImport;
        $this->filesystem = $filesystem;
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
     * @inheritdoc
     */
    public function uploadAndImport(DataObject $object)
    {
        $files = $this->request->getFiles()->toArray();

        if (!isset($files['groups']['sendcloud']['fields']['import']['value']) ||
            empty($files['groups']['sendcloud']['fields']['import']['value']['tmp_name'])) {
            return $this;
        }

        try {
            $this->originalUploadAndImport($object);

        } catch (\Exception $e) {
            $this->displayErrorMessage();
        }

        return $this;
    }

    /**
     * @param DataObject $object
     * @return mixed|string
     * @since 100.1.0
     */
    public function getConditionName(DataObject $object)
    {
        if ($object->getData('groups/sendcloud/fields/condition_name/inherit') == '1') {
            $conditionName = (string)$this->coreConfig->getValue('carriers/sendcloud/condition_name', 'default');
        } else {
            $conditionName = $object->getData('groups/sendcloud/fields/condition_name/value');
        }

        return $conditionName;
    }

    /**
     * START MAGENTO CODE
     * @see \Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate
     */

    /**
     * This is original method from Tablerate with the only modification in taking the sendcloud servicepoint rate file
     * instead  of the tablerate file.
     * Since Tablerate using global files, and we have no option to set servicepoint rate file insted of tablerate file
     * without breaking Magento coding standard by using global files, this method is copied from Tablerate with the only
     * modification in using the servicepoint rate as file
     * @see \Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate
     *
     * @param DataObject $object
     * @return $this
     * @throws LocalizedException
     */
    private function originalUploadAndImport(\Magento\Framework\DataObject $object)
    {
        $files = $this->request->getFiles()->toArray();

        if (!isset($files['groups']['sendcloud']['fields']['import']['value']) ||
            empty($files['groups']['sendcloud']['fields']['import']['value']['tmp_name'])) {
            return $this;
        }

        $filePath = $files['groups']['sendcloud']['fields']['import']['value']['tmp_name'];

        $websiteId = $this->storeManager->getWebsite($object->getScopeId())->getId();
        $conditionName = $this->getConditionName($object);

        $file = $this->getCsvFile($filePath);
        try {
            // delete old data by website and condition name
            $condition = [
                'website_id = ?' => $websiteId,
                'condition_name = ?' => $conditionName,
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
                __($e->getMessage())
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
     * @param string $filePath
     * @return \Magento\Framework\Filesystem\File\ReadInterface
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
                __('Something went wrong while importing table rates.')
            );
        }
        $connection->commit();
    }

    /**
     * END MAGENTO CODE
     */

    /**
     * @throws LocalizedException
     */
    private function displayErrorMessage()
    {
        if ($this->import->hasErrors()) {
            $error = __(
                'We couldn\'t import this file because of these errors: %1',
                implode(" \n", $this->import->getErrors())
            );
            throw new LocalizedException($error);
        } else {
            throw new LocalizedException(
                __('Something went wrong while importing servicepoint rates.')
            );
        }
    }
}
