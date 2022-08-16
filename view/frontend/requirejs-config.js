var config = {
    "map": {
        "*": {
            'Amasty_Checkout/js/model/shipping-save-processor/default': 'SendCloud_SendCloud/js/model/shipping-save-processor/amasty'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping-information': {
                'SendCloud_SendCloud/js/mixins/checkout/view/shipping-information': true
            },
            'Magento_Checkout/js/view/shipping': {
                'SendCloud_SendCloud/js/mixins/checkout/view/shipping-mixin': true
            },
            'Mageplaza_Osc/js/model/shipping-save-processor/checkout': {
                'SendCloud_SendCloud/js/model/shipping-save-processor/mageplaza': true
            },
            'Mageplaza_Osc/js/view/review/placeOrder': {
                'SendCloud_SendCloud/js/mixins/view/review/placeOrder': true
            },
            'Onestepcheckout_Iosc/js/shipping': {
                'SendCloud_SendCloud/js/mixins/shipping': true
            },
            'Magento_Checkout/js/model/quote': {
                'SendCloud_SendCloud/js/mixins/quote': true
            },
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'SendCloud_SendCloud/js/model/shipping-save-processor/payload-extender-mixin': true
            },
            'Magento_Checkout/js/action/select-shipping-method': {
                'SendCloud_SendCloud/js/action/select-shipping-method-mixin': true
            }
        }
    }
};
