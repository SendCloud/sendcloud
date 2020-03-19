define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use_strict';

    return function (checkout) {
        checkout.payloadExtender = wrapper.wrapSuper(checkout.payloadExtender, function (payload) {
            this._super(payload);

            if (quote.shippingMethod().method_code === 'sendcloud') {
                var sendCloudAttributes = {
                    sendcloud_service_point_id: $('[name="sendcloud_service_point_id"]').val(),
                    sendcloud_service_point_name: $('[name="sendcloud_service_point_name"]').val(),
                    sendcloud_service_point_street: $('[name="sendcloud_service_point_street"]').val(),
                    sendcloud_service_point_house_number: $('[name="sendcloud_service_point_house_number"]').val(),
                    sendcloud_service_point_zip_code: $('[name="sendcloud_service_point_zip_code"]').val(),
                    sendcloud_service_point_city: $('[name="sendcloud_service_point_city"]').val(),
                    sendcloud_service_point_country: $('[name="sendcloud_service_point_country"]').val()
                };

                payload.addressInformation.extension_attributes = $.extend(
                    payload.addressInformation.extension_attributes,
                    sendCloudAttributes
                );
            }
        });

        return checkout;
    };
});
