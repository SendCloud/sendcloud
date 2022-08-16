<?php

namespace SendCloud\SendCloud\Plugin\Carrier;

use Magento\Quote\Api\Data\ShippingMethodInterfaceFactory;

class ExtensionAttributes
{
    /**
     * @var ShippingMethodInterfaceFactory
     */
    protected $extensionFactory;

    /**
     * Description constructor.
     * @param ShippingMethodInterfaceFactory $extensionFactory
     */
    public function __construct(
        ShippingMethodInterfaceFactory $extensionFactory
    ) {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * @param $subject
     * @param $result
     * @param $rateModel
     * @return mixed
     */
    public function afterModelToDataObject($subject, $result, $rateModel)
    {
        $extensionAttribute = $result->getExtensionAttributes() ?
            $result->getExtensionAttributes()
            :
            $this->extensionFactory->create();
        
        $extensionAttribute->setMethodConfiguration($rateModel->getMethodConfiguration());
        
        $result->setExtensionAttributes($extensionAttribute);
        return $result;
    }
}
