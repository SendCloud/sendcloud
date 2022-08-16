define([
    'Magento_Checkout/js/model/quote',
    'jquery',
    'uiRegistry',
    'mage/translate',
], function (quote, $, uiRegistry, $t) {
    'use strict';
    return {
        /**
         * Validate service point data
         *
         * @param origResult
         * @returns {boolean|*}
         */
        successHandler: function (selectMethod) {
            var servicePointData = quote.getExtensionAttributes();

            if (selectMethod && $('#sendcloud-service-point .message.warning').length === 0) {
                if (
                    selectMethod.carrier_code === 'sendcloud' &&
                    (!servicePointData || !servicePointData['sendcloud_service_point_id'])
                ) {
                    uiRegistry.async("checkout.steps.shipping-step.shippingAddress")(
                        function (shippingValidation) {
                            shippingValidation.errorValidationMessage($t('Please select a service point'));
                        }
                    );
                }
            }
        }
    };
});
