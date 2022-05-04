define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'SendCloud_SendCloud/js/servicePoint/mixins/payload-extender',
    'SendCloud_SendCloud/js/checkout/payload-extender',
], function (wrapper, quote, servicePoint, checkout) {
    'use strict';

    return function (payloadExtender) {
        return wrapper.wrap(payloadExtender, function (originalAction, payload) {
            payload = originalAction(payload);

            switch (quote.shippingMethod().carrier_code) {
                case 'sendcloud' :
                    return servicePoint.extendData(payload);
                case 'sendcloud_checkout' :
                    return checkout.extendData(payload);
            }

            return payload;
        });
    };
});
