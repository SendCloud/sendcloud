define([
    'Magento_Checkout/js/model/shipping-rate-registry',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/model/quote',
    'uiRegistry',
    'mage/translate',
    'jquery'
], function (rateRegistry, shippingService, selectShippingMethodAction, quote, uiRegistry, $t, $) {
    'use strict';

    return function (Component) {
        return Component.extend({
            successHandler: function (data) {

                if (!data.data) {
                    return;
                }

                if (typeof data.data.shippingMethod !== "undefined" && data.data.shippingMethod.length > 0) {
                    var equal = false;
                    var methods = data.data.shippingMethod;
                    var servicePointData = JSON.parse(window.sessionStorage.getItem('service-point-data'));
                    if (this.rateCacheKey) {
                        equal = _.isEqual(rateRegistry.get(this.rateCacheKey),methods);
                    }

                    if (!equal) {
                        rateRegistry.set(this.rateCacheKey, methods);
                        shippingService.setShippingRates(methods);
                    } else if (equal && this._has(data, "data.shippingMethod")) {
                        shippingService.setShippingRates(methods);
                    }
                    if (this._has(data, "data.selectedShippingMethod") ) {
                        var selectMethod, findMethod;
                        if(data.data.selectedShippingMethod === ""){
                            selectMethod = null;
                        } else {
                            findMethod = _.find(
                                methods,
                                function (obj) {
                                    return obj.carrier_code + '_' + obj.method_code === data.data.selectedShippingMethod;
                                }
                            );

                            if(typeof findMethod !== "undefined"){
                                selectMethod = findMethod;
                            }

                        }

                        selectShippingMethodAction(selectMethod);

                        if (selectMethod && $('#sendcloud-service-point .message.warning').length === 0) {
                            if (selectMethod.carrier_code === 'sendcloud' && !servicePointData) {
                                uiRegistry.async("checkout.steps.shipping-step.shippingAddress")(
                                    function (shippingValidation) {
                                        shippingValidation.errorValidationMessage($t('Please select a service point'));
                                    }
                                );
                            }
                        }

                        window.checkoutConfig.selectedShippingMethod = selectMethod;
                    }
                } else {
                    shippingService.setShippingRates([]);
                    selectShippingMethodAction(null);
                    window.checkoutConfig.selectedShippingMethod = [];
                }
            }
        })
    };
});
