<?php

namespace SendCloud\SendCloud\Model;

use SendCloud\SendCloud\Api\ServicePointInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use Exception;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class ServicePoint implements ServicePointInterface
{
    /** @var WriterInterface  */
    private $writer;

    /** @var SendCloudLogger  */
    private $logger;

    /** @var TypeListInterface  */
    private $cache;

    public function __construct(WriterInterface $writer, SendCloudLogger $logger, TypeListInterface $cache)
    {
        $this->writer = $writer;
        $this->logger = $logger;
        $this->cache = $cache;
    }

    /**
     * @param $script_url
     * @return array|mixed
     */
    public function activate($script_url)
    {
        try {
            $this->writer->save('carriers/sendcloud/active', 1, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            $this->writer->save('sendcloud/sendcloud/script_url', $script_url, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            $this->cache->cleanType('config');
        } catch (Exception $ex) {
            $this->logger->debug($ex->getMessage());

            return ['message' => ['error' => 'Shipping method is not activated and script url is not set']];
        }

        return ['message' => ['success' => 'Shipping method is activated an script url is set']];
    }

    /**
     * @return array
     */
    public function deactivate()
    {
        try {
            $this->writer->save('carriers/sendcloud/active', 0, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            $this->writer->save('sendcloud/sendcloud/script_url', '', ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            $this->cache->cleanType('config');
        } catch (Exception $ex) {
            $this->logger->debug($ex->getMessage());

            return ['message' => ['error' => 'Shipping method is not deactivated']];
        }
        return ['message' => ['success' => 'Shipping method is deactivated']];
    }

    /**
     * @param boolean $activate
     * @return array
     */
    public function shippingEmail($activate)
    {
        if ($activate) {
            $message = ['success' => 'Shipment email is activated'];
        } else {
            $message = ['success' => 'Shipment email is deactivated'];
        }

        try {
            $this->writer->save('sales_email/shipment/enabled', (int) $activate, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            $this->cache->cleanType('config');
        } catch (Exception $ex) {
            $this->logger->debug($ex->getMessage());
            $message = ['error' => 'Shipment email is not changed'];
        }

        return ['message' => $message];
    }
}
