define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (checkout) {
        checkout.payloadExtender = wrapper.wrapSuper(checkout.payloadExtender, function (payload) {
            this._super(payload);

            if (quote.shippingMethod() && quote.shippingMethod().method_code === 'sendcloud') {
                var sendCloudAttributes = quote.getSendcloudServicePoint();

                payload.addressInformation.extension_attributes = $.extend(
                    payload.addressInformation.extension_attributes,
                    sendCloudAttributes
                );
            }
        });

        return checkout;
    };
});
