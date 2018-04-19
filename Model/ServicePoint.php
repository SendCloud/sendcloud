<?php
/**
 * Created by PhpStorm.
 * User: wessel
 * Date: 27-02-18
 * Time: 21:46
 */

namespace CreativeICT\SendCloud\Model;


use CreativeICT\SendCloud\Api\ServicePointInterface;
use CreativeICT\SendCloud\Logger\SendCloudLogger;
use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Serialize\Serializer\Json;

class ServicePoint implements ServicePointInterface
{
    /** @var WriterInterface  */
    private $writer;

    /** @var ScopeConfigInterface  */
    private $scopeConfig;

    /** @var SendCloudLogger  */
    private $logger;

    /** @var Json  */
    private $json;

    public function __construct(
        WriterInterface $writer,
        ScopeConfigInterface $scopeConfig,
        Json $json,
        SendCloudLogger $logger
    )
    {
        $this->writer = $writer;
        $this->scopeConfig = $scopeConfig;
        $this->json = $json;
        $this->logger = $logger;
    }

    /**
     * @param $script_url
     * @return array|mixed
     */
    public function activate($script_url)
    {
        try {
            $this->writer->save('carriers/sendcloud/active', 1, $this->scopeConfig::SCOPE_TYPE_DEFAULT, 0);
            $this->writer->save('creativeict/sendcloud/script_url', $script_url, $this->scopeConfig::SCOPE_TYPE_DEFAULT, 0);
        } catch (Exception $ex) {
            $this->logger->debug($ex->getMessage());

            return array('message' => array('error' => 'Shipping method is not activated and script url is not set'));
        }

        return array('message' => array('success' => 'Shipping method is activated an script url is set'));
    }

    /**
     * @return array
     */
    public function deactivate()
    {
        try {
            $this->writer->save('carriers/sendcloud/active', 0, $this->scopeConfig::SCOPE_TYPE_DEFAULT, 0);
            $this->writer->save('creativeict/sendcloud/script_url', '', $this->scopeConfig::SCOPE_TYPE_DEFAULT, 0);
        } catch (Exception $ex) {
            $this->logger->debug($ex->getMessage());

            return array('message' => array('error' => 'Shipping method is not deactivated'));
        }
        return array('message' => array('success' => 'Shipping method is deactivated'));
    }

    /**
     * @param boolean $activate
     * @return array
     */
    public function shippingEmail($activate)
    {
        if ($activate) {
            $message = array('success' => 'Shipment email is activated');
        } else {
            $message = array('success' => 'Shipment email is deactivated');
        }

        try {
            $this->writer->save('sales_email/shipment/enabled', (int) $activate, $this->scopeConfig::SCOPE_TYPE_DEFAULT, 0);
        } catch (Exception $ex) {
            $this->logger->debug($ex->getMessage());
            $message = array('error' => 'Shipment email is not changed');
        }

        return array('message' => $message);
    }
}