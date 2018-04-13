define([
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/resource-url-manager',
    'mage/storage',
    'Magento_Checkout/js/model/payment-service',
    'Magento_Checkout/js/model/payment/method-converter',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Checkout/js/action/select-billing-address',
    'Magento_Checkout/js/model/shipping-save-processor/payload-extender',
    'jquery'
], function (
    ko,
    quote,
    resourceUrlManager,
    storage,
    paymentService,
    methodConverter,
    errorProcessor,
    fullScreenLoader,
    selectBillingAddressAction,
    payloadExtender,
    $
) {
    'use_strict';

    return {
        saveShippingInformation: function() {
            var payload;

            if (!quote.billingAddress()) {
                selectBillingAddressAction(quote.shippingAddress());
            }

            payload = {
                addressInformation: {
                    'shipping_address': quote.shippingAddress(),
                    'billing_address': quote.billingAddress(),
                    'shipping_method_code': quote.shippingMethod()['method_code'],
                    'shipping_carrier_code': quote.shippingMethod()['carrier_code'],
                    extension_attributes: {
                        sendcloud_service_point_id: $('[name="sendcloud_service_point_id"]').val(),
                        sendcloud_service_point_name: $('[name="sendcloud_service_point_name"]').val(),
                        sendcloud_service_point_street: $('[name="sendcloud_service_point_street"]').val(),
                        sendcloud_service_point_house_number: $('[name="sendcloud_service_point_house_number"]').val(),
                        sendcloud_service_point_zip_code: $('[name="sendcloud_service_point_zip_code"]').val(),
                        sendcloud_service_point_city: $('[name="sendcloud_service_point_city"]').val(),
                        sendcloud_service_point_country: $('[name="sendcloud_service_point_country"]').val()
                    }
                }
            };

            fullScreenLoader.startLoader();

            return storage.post(
                resourceUrlManager.getUrlForSetShippingInformation(quote),
                JSON.stringify(payload)
            ).done(
                function (response) {
                    quote.setTotals(response.totals);
                    paymentService.setPaymentMethods(methodConverter(response['payment_methods']));
                    fullScreenLoader.stopLoader();
                }
            ).fail(
                function (response) {
                    errorProcessor.process(response);
                    fullScreenLoader.stopLoader();
                }
            );
        }
    };
});