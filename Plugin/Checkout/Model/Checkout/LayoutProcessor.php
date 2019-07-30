<?php

namespace SendCloud\SendCloud\Plugin\Checkout\Model\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor as TargetLayoutProcessor;
use SendCloud\SendCloud\Helper\Checkout;

/**
 * Class LayoutProcessor
 * @package SendCloud\SendCloud\Plugin\Checkout\Model\Checkout
 */
class LayoutProcessor
{
    /**
     * @var Checkout
     */
    private $helper;

    /**
     * LayoutProcessor constructor.
     * @param Checkout $helper
     */
    public function __construct(Checkout $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param TargetLayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(TargetLayoutProcessor $subject, array $jsLayout)
    {
        if ($this->helper->checkForScriptUrl() && $this->helper->checkIfModuleIsActive()) {
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

        }

        return $jsLayout;
    }
}
