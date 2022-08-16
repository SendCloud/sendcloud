<?php
namespace SendCloud\SendCloud\Controller\Adminhtml\Exportrates;

use Magento\Backend\App\Action\Context;
use Magento\Config\Controller\Adminhtml\System\AbstractConfig;
use Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker;
use Magento\Config\Model\Config\Structure;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use SendCloud\SendCloud\Block\Adminhtml\Carrier\Servicepointrate\Grid;

class Exportrates extends AbstractConfig
{
    /**
     * @var FileFactory
     */
    protected $_fileFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Exportrates constructor.
     * @param Context $context
     * @param Structure $configStructure
     * @param ConfigSectionChecker $sectionChecker
     * @param FileFactory $fileFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Structure $configStructure,
        ConfigSectionChecker $sectionChecker,
        FileFactory $fileFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        $this->_fileFactory = $fileFactory;
        parent::__construct($context, $configStructure, $sectionChecker);
    }

    /**
     * Export Servicepoint rates in csv format
     *
     * @return ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $fileName = 'sendcloud_servicepointrates.csv';
        /** @var $gridBlock Grid */
        $gridBlock = $this->_view->getLayout()->createBlock(Grid::class);
        $website = $this->_storeManager->getWebsite($this->getRequest()->getParam('website'));
        if ($this->getRequest()->getParam('conditionName')) {
            $conditionName = $this->getRequest()->getParam('conditionName');
        } else {
            $conditionName = $website->getConfig('carriers/sendcloud/condition_name');
        }
        $gridBlock->setWebsiteId($website->getId())->setConditionName($conditionName);
        $content = $gridBlock->getCsvFile();
        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
