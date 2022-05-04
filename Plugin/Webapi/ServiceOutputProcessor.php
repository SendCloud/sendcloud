<?php

namespace SendCloud\SendCloud\Plugin\Webapi;

use Magento\Framework\Webapi\ServiceOutputProcessor as MagentoServiceOutputProcessor;
use Magento\Framework\Serialize\Serializer\Json;

class ServiceOutputProcessor
{
    /**
     * @var Json
     */
    private $serializer;

    public function __construct(Json $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param MagentoServiceOutputProcessor $processor
     * @param array|int|string|bool|float $output
     * @param mixed $data
     * @param string $serviceClassName
     * @param string $serviceMethodName
     * @return array|int|string|bool|float
     */
    public function afterProcess(MagentoServiceOutputProcessor $processor, $output, $data, $serviceClassName, $serviceMethodName)
    {
        if (is_array($output) && array_key_exists('items', $output)) {
            foreach ($output['items'] as &$item) {
                if (array_key_exists('extension_attributes', $item) && array_key_exists('sendcloud_data', $item['extension_attributes'])) {
                    $item['extension_attributes']['sendcloud_data'] =
                        ['checkout_payload' => $this->serializer->unserialize($item['extension_attributes']['sendcloud_data'])];
                }
            }
        }

        return $output;
    }
}
