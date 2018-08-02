<?php
namespace SendCloud\SendCloud\Plugin\Checkout\Model\Checkout;

use SendCloud\SendCloud\Helper\Checkout;

class LayoutProcessor
{
    private $helper;

    public function __construct(Checkout $helper)
    {
        $this->helper = $helper;
    }

    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, array $jsLayout)
    {
        if ($this->helper->checkForScriptUrl()) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['step-config']['children']['shipping-rates-validation']['children']['servicepoint-rates-validation'] = [
                'component' => 'SendCloud_SendCloud/js/view/shipping-rates-validation'
            ];

            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['component'] = 'SendCloud_SendCloud/js/view/shipping';
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shippingAdditional'] = [
                'component' => 'uiComponent',
                'displayArea' => 'shippingAdditional',
                'children' => [
                    'service-point' => [
                        'component' => 'SendCloud_SendCloud/js/view/checkout/shipping/servicePoint'
                    ]
                ]
            ];

            $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information'] = [
                'component' => 'SendCloud_SendCloud/js/view/shipping-information'
            ];
        }

        return $jsLayout;
    }
}