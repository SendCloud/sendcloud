var config = {
    "map": {
        "*": {
            'Magento_Checkout/js/model/shipping-save-processor/default': 'SendCloud_SendCloud/js/model/shipping-save-processor/servicepoint',
            'Amasty_Checkout/js/model/shipping-save-processor/default': 'SendCloud_SendCloud/js/model/shipping-save-processor/amasty-servicepoint',
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
            'Magento_Checkout/js/view/payment/default': {
                'SendCloud_SendCloud/js/mixins/checkout/view/payment/default-mixin': true
            },
            'Mageplaza_Osc/js/model/shipping-save-processor/checkout': {
                'SendCloud_SendCloud/js/model/shipping-save-processor/mageplaza-servicepoint': true
            }
        }
    }
};
