define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'SendCloud_SendCloud/js/servicePoint/mixins/mageplaza-servicepoint',
    'SendCloud_SendCloud/js/checkout/mageplaza'
], function ($, wrapper, quote, servicePoint, checkoutMageplaza) {
    'use strict';

    return function (checkout) {
        checkout.payloadExtender = wrapper.wrapSuper(checkout.payloadExtender, function (payload) {
            this._super(payload);

            switch (quote.shippingMethod().carrier_code) {
                case 'sendcloud' :
                    return servicePoint.extendData(payload);
                case 'sendcloudcheckout' :
                    return checkoutMageplaza.extendData(payload);
            }
        });

        return checkout;
    };
});
