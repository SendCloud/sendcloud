<?php


namespace SendCloud\SendCloud\Controller\Adminhtml\Configuration;

use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Action\Context;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;

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
     * Save constructor.
     * @param WriterInterface $writer
     * @param Context $context
     */
    public function __construct(ReinitableConfigInterface $scopeConfig, WriterInterface $writer, Context $context)
    {
        parent::__construct($context);
        $this->writer = $writer;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        $data = (array)$this->getRequest()->getPost();
        $storeId = $data['store_id'];

        if (empty($storeId)) {
            $this->writer->save('sendcloud/general/enable', $data['is_active']);
            $this->scopeConfig->reinit();

            $this->_redirect('sendcloud/configuration/index/store/');

        } else {
            $this->writer->save('sendcloud/general/enable', $data['is_active'], ScopeInterface::SCOPE_STORES, (int)$data['store_id']);
            $this->scopeConfig->reinit();

            $this->_redirect("sendcloud/configuration/index/store/$storeId");
        }
    }
}
