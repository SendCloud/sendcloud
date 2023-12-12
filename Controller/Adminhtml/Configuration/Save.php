<?php


namespace SendCloud\SendCloud\Controller\Adminhtml\Configuration;

use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Action\Context;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;

class Save extends \Magento\Framework\App\Action\Action
{

    /**
     * @var WriterInterface
     */
    private $writer;

    /**
     * @var ReinitableConfigInterface
     */
    private $scopeConfig;

    /**
     * @var SendCloudLogger
     */
    private $logger;

    /**
     * Save constructor.
     * @param ReinitableConfigInterface $scopeConfig
     * @param WriterInterface $writer
     * @param Context $context
     * @param SendCloudLogger $logger
     */
    public function __construct(
        ReinitableConfigInterface $scopeConfig,
        WriterInterface $writer,
        Context $context,
        SendCloudLogger $logger
    ) {
        parent::__construct($context);
        $this->writer = $writer;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    public function execute()
    {
        $data = (array)$this->getRequest()->getPost();
        $this->logger->info("Save configuration request: " . json_encode($data));

        $storeId = $data['store_id'];

        if (empty($storeId)) {
            $this->writer->save('sendcloud/general/enable', $data['is_active']);
            $this->scopeConfig->reinit();

            return $this->_redirect('sendcloud/configuration/index/store/');
        }

        $this->writer->save('sendcloud/general/enable', $data['is_active'], ScopeInterface::SCOPE_STORES, (int)$data['store_id']);
        $this->scopeConfig->reinit();

        return $this->_redirect("sendcloud/configuration/index/store/$storeId");
    }
}
