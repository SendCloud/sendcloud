define(
    [
        'jquery',
        'Magento_Ui/js/lib/view/utils/async',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/model/quote',
        'Amasty_Checkout/js/model/cart/totals-processor/default',
        'Amasty_Checkout/js/model/shipping-registry',
        'uiRegistry'
    ],
    function (
        $,
        async,
        storage,
        checkoutData,
        shippingService,
        selectShippingMethod,
        setShippingInformationAction,
        quote,
        totalsProcessor,
        shippingRegistry,
        registry
    ) {
        'use strict';

        var instance = null;

        function removeAmazonPayButton() {
            var amazonPaymentButton = $('#PayWithAmazon_amazon-pay-button img');
            if (amazonPaymentButton.length > 1) {
                amazonPaymentButton.not(':first').remove();
            }
        }

        return function (Shipping) {
            return Shipping.extend({
                allowedDynamicalSave: false,
                allowedDynamicalValidation: true,
                initialize: function () {
                    this._super();
                    instance = this;

                    quote.shippingAddress.subscribe(this.shippingAddressObserver.bind(this));
                    quote.shippingMethod.subscribe(this.shippingMethodObserver.bind(this));
                    this.allowedDynamicalSave = true;

                    registry.get('checkout.steps.shipping-step.shippingAddress.before-form.amazon-widget-address.before-widget-address.amazon-checkout-revert',
                        function (component) {
                            component.isAmazonAccountLoggedIn.subscribe(function (loggedIn) {
                                if (!loggedIn) {
                                    registry.get('checkout.steps.shipping-step.shippingAddress', function (component) {
                                        if (component.isSelected()) {
                                            component.selectShippingMethod(quote.shippingMethod());
                                        }
                                    });
                                }
                            });
                        }
                    );

                    registry.get('checkout.steps.billing-step.payment.payments-list.amazon_payment', function (component) {
                        if (component.isAmazonAccountLoggedIn()) {
                            $('button.action-show-popup').hide();
                        }
                    });

                    registry.get('checkout.steps.shipping-step.shippingAddress.customer-email.amazon-button-region.amazon-button',
                        function (component) {
                            async.async({
                                selector: "#PayWithAmazon_amazon-pay-button img"
                            }, function () {
                                removeAmazonPayButton();
                            });

                            component.isAmazonAccountLoggedIn.subscribe(function (loggedIn) {
                                if (!loggedIn) {
                                    removeAmazonPayButton();
                                }
                            });
                        }
                    );
                },

                setShippingInformation: function () {
                    this.allowedDynamicalSave = false;
                    var result = this._super();
                    this.allowedDynamicalSave = true;
                    return result;
                },

                selectShippingMethod: function (method) {
                    window.loaderIsNotAllowed = true;
                    this._super();
                    instance.shippingAddressObserver();
                    delete window.loaderIsNotAllowed;

                    return true;
                },

                validateShippingInformation: function () {
                    this.allowedDynamicalValidation = false;
                    var result = this._super();
                    this.allowedDynamicalValidation = true;
                    return result;
                },

                /**
                 * Calculate Totals for changed shipping method.
                 * Necessary only if dynamical shipping save is not working (i.e. shipping is not valid)
                 *
                 * @param {Object} method
                 */
                shippingMethodObserver: function (method) {
                    if (method && this.isFormInline && !shippingRegistry.savedAddress) {
                        totalsProcessor(quote.shippingAddress());
                    }
                },

                /**
                 * Save Shipping data dynamically
                 * @param {null|Object} address
                 */
                shippingAddressObserver: function (address) {
                    if (this.isFormInline && $('#checkout-loader:visible').length) {
                        return;
                    }
                    if (shippingService.isLoading()) {
                        // avoid error "shipping method not available"
                        var saveSubscription = shippingService.isLoading.subscribe(function (isLoading) {
                            if (!isLoading) {
                                saveSubscription.dispose();
                                this.validateAndSaveIfChanged();
                            }
                        }.bind(this))
                    } else {
                        if (saveSubscription) {
                            saveSubscription.dispose();
                        }
                        this.validateAndSaveIfChanged();
                    }
                },

                validateAndSaveIfChanged: function () {
                    if (!this.allowedDynamicalSave || !shippingRegistry.isHaveUnsavedShipping()) {
                        return;
                    }
                    var isShippingValid = !this.allowedDynamicalValidation;
                    // allowedDynamicalValidation - for avoid circular dependency
                    if (this.allowedDynamicalValidation) {
                        this.allowedDynamicalSave = false;
                        isShippingValid = this.validateShippingInformation();
                        this.allowedDynamicalSave = true;
                    }

                    /*
                     if isFormInline = true, method validateShippingInformation
                     will set shipping address and this observer will be executed.
                     validateShippingInformation - also validate email, which is not part of Shipping information.
                    */
                    if (isShippingValid || (this.isFormInline && !this.source.get('params.invalid'))) {
                        window.loaderIsNotAllowed = true;
                        setShippingInformationAction();
                        delete window.loaderIsNotAllowed;
                    }
                },

                getNameShippingAddress: function () {
                    return window.checkoutConfig.quoteData.block_info.block_shipping_address['value'];
                },

                getNameShippingMethod: function () {
                    return window.checkoutConfig.quoteData.block_info.block_shipping_method['value'];
                },

                isPostNlEnable: function () {
                    return window.checkoutConfig.quoteData.posnt_nl_enable;
                },

                /**
                 * Trigger Shipping data Validate Event.
                 */
                triggerShippingDataValidateEvent: function () {
                    this.source.trigger('shippingAddress.data.validate');

                    if (this.source.get('shippingAddress.custom_attributes')) {
                        this.source.trigger('shippingAddress.custom_attributes.data.validate');
                    }
                },

                validatePlaceOrder: function () {
                    var loginFormSelector = 'form[data-role=email-with-possible-login]',
                        emailValidationResult = this.isCustomerLoggedIn();

                    if (!emailValidationResult) {
                        $(loginFormSelector).validation();
                        emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
                    }

                    if (!emailValidationResult) {
                        $(loginFormSelector + ' input[name=username]').focus();

                        return false;
                    }

                    if (this.isFormInline) {
                        this.source.set('params.invalid', false);
                        this.triggerShippingDataValidateEvent();

                        if (
                            this.source.get('params.invalid')
                        ) {
                            return false;
                        }
                    }

                    return true;
                }
            });
        }
    }
);
