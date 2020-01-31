define([
    'Magento_Checkout/js/model/quote',
], function (quote) {
    'use strict';

    return function (Component) {
        return Component.extend({
            validateShippingInformation: function() {
                var origResult = this._super(),
                    servicePointData = JSON.parse(window.sessionStorage.getItem('service-point-data'));

                if (quote.shippingMethod()['carrier_code'] === 'sendcloud' && !servicePointData) {
                    this.errorValidationMessage($t('Please select a service point'));

                    return false;
                }
                return origResult;
            }
        });
    }
});
