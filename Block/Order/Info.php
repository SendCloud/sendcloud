<?php

namespace SendCloud\SendCloud\Block\Order;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Block\Order\Info as Original;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;
use Magento\Framework\Serialize\Serializer\Json;

class Info extends Original
{
    /**
     * @var Json
     */
    private $serializer;
    
    /**
     * @param Json $json
     * @param TemplateContext $context
     * @param Registry $registry
     * @param PaymentHelper $paymentHelper
     * @param AddressRenderer $addressRenderer
     * @param array $data
     */
    public function __construct(
        Json $json,
        TemplateContext $context,
        Registry $registry,
        PaymentHelper $paymentHelper,
        AddressRenderer $addressRenderer,
        array $data = []
    ) {
        $this->serializer = $json;
        parent::__construct($context, $registry, $paymentHelper, $addressRenderer, $data);
    }
    /**
     * @var string
     */
    protected $_template = 'SendCloud_SendCloud::order/info.phtml';

    /**
     * @return Json
     */
    public function getSerializer()
    {
        return $this->serializer;
    }
}
