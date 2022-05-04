<?php

namespace SendCloud\SendCloud\Plugin\Webapi\Rest;

use Exception;
use Magento\Framework\Webapi\Rest\Response as BaseResponse;
use Magento\Framework\Webapi\Rest\Response\RendererFactory;
use Magento\Framework\Webapi\Rest\Response\RendererInterface;
use SendCloud\SendCloud\Exceptions\SendcloudException;

class Response
{
    /**
     * @var RendererInterface
     */
    protected $_renderer;

    /**
     * Initialize dependencies.
     *
     * @param RendererFactory $rendererFactory
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function __construct(
        RendererFactory $rendererFactory
    ) {
        $this->_renderer = $rendererFactory->get();
    }

    /**
     * @param BaseResponse $response
     */
    public function beforeSendContent(BaseResponse $response): void
    {
        if ($response->isException()) {
            /** @var Exception $exception */
            foreach ($response->getException() as $exception) {
                if ($exception instanceof SendcloudException) {
                    $response->setBody($this->_renderer->render($exception->getDetails()));
                }
            }
        }
    }
}
