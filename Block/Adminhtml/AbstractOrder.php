<?php

namespace SendCloud\SendCloud\Block\Adminhtml;

use Magento\Framework\App\ObjectManager;
use Magento\Sales\Block\Adminhtml\Order\AbstractOrder as Original;
use Magento\Shipping\Helper\Data as ShippingHelper;
use Magento\Tax\Helper\Data as TaxHelper;
use Magento\Framework\Serialize\Serializer\Json;

class AbstractOrder extends Original
{
    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param Json $json
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Helper\Admin $adminHelper
     * @param array $data
     * @param ShippingHelper|null $shippingHelper
     * @param TaxHelper|null $taxHelper
     */
    public function __construct(
        Json $json,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        array $data = [],
        ?ShippingHelper $shippingHelper = null,
        ?TaxHelper $taxHelper = null
    ) {
        $this->serializer = $json;
        parent::__construct($context, $registry, $adminHelper, $data);
    }
    
    /**
     * @return Json
     */
    public function getSerializer()
    {
        return $this->serializer;
    }
}
