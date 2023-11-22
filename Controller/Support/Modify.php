<?php

namespace SendCloud\SendCloud\Controller\Support;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use SendCloud\SendCloud\Services\BusinessLogic\SupportService;

/**
 * Class Modify
 *
 * @package SendCloud\SendCloud\Controller\Adminhtml\Support
 */
class Modify extends Action implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var SupportService
     */
    private $supportService;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Modify Constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param SupportService $supportService
     * @param RequestInterface $request
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        SupportService $supportService,
        RequestInterface $request
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->supportService = $supportService;
        $this->request = $request;
    }

    /**
     * Modifies configuration data.
     *
     * @return mixed
     */
    public function execute()
    {
        $body = json_decode($this->request->getContent(), true);

        return $this->resultJsonFactory->create()->setData($this->supportService->update($body));
    }
}
