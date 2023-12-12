<?php

namespace SendCloud\SendCloud\Controller\Multishipping;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryMethod;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var SendcloudDeliveryMethod
     */
    private $sendcloudDeliveryMethod;

    /**
     * @var SendCloudLogger
     */
    private $logger;

    /**
     * @param SendcloudDeliveryMethod $sendcloudDeliveryMethod
     * @param Context $context
     * @param SendCloudLogger $logger
     */
    public function __construct(
        SendcloudDeliveryMethod $sendcloudDeliveryMethod,
        Context $context,
        SendCloudLogger $logger
    ) {
        parent::__construct($context);
        $this->sendcloudDeliveryMethod = $sendcloudDeliveryMethod;
        $this->logger = $logger;
    }

    public function execute()
    {
        $this->logger->info("Multishipping: " . json_encode($this->getRequest()->getParams()));
        $result = [];
        $methodCodes = $this->getRequest()->getParam('methods');
        $methods = $this->sendcloudDeliveryMethod->select($methodCodes);
        /**
         * @var DeliveryMethod $method
         */
        foreach ($methods as $method) {
            $result[$method->getId()] = $method->getRawConfig();
        }
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($result);

        return $resultJson;
    }
}
