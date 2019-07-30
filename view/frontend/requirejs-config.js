var config = {
    "map": {
        "*": {
            'Magento_Checkout/js/model/shipping-save-processor/default': 'SendCloud_SendCloud/js/model/shipping-save-processor/servicepoint',
            'Amasty_Checkout/js/model/shipping-save-processor/default': 'SendCloud_SendCloud/js/model/shipping-save-processor/amasty-servicepoint',
            'Magento_Checkout/js/view/payment/default': 'SendCloud_SendCloud/js/view/payment/default'
        }
    },
    "config": {
        mixins: {
            'Magento_Checkout/js/view/shipping-information': {
                'SendCloud_SendCloud/js/mixins/checkout/view/shipping-information': true
            },
        }
    }
};