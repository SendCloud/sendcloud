/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/form',
    'ko',
    'Magento_Customer/js/model/customer',
    'Magento_Customer/js/model/address-list',
    'Magento_Checkout/js/model/address-converter',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/create-shipping-address',
    'Magento_Checkout/js/action/select-shipping-address',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-address/form-popup-state',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Ui/js/modal/modal',
    'Magento_Checkout/js/model/checkout-data-resolver',
    'Magento_Checkout/js/checkout-data',
    'uiRegistry',
    'mage/translate',
    'SendCloud_SendCloud/js/model/cart/totals-processor/default',
    'SendCloud_SendCloud/js/model/shipping-registry',
    'Magento_Checkout/js/model/shipping-rate-service'
], function (
    $,
    _,
    Component,
    ko,
    customer,
    addressList,
    addressConverter,
    quote,
    createShippingAddress,
    selectShippingAddress,
    shippingRatesValidator,
    formPopUpState,
    shippingService,
    selectShippingMethodAction,
    rateRegistry,
    setShippingInformationAction,
    stepNavigator,
    modal,
    checkoutDataResolver,
    checkoutData,
    registry,
    $t,
    totalsProcessor,
    shippingRegistry
) {
    'use strict';

    var popUp = null;

    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/shipping',
            shippingFormTemplate: 'Magento_Checkout/shipping-address/form',
            shippingMethodListTemplate: 'Magento_Checkout/shipping-address/shipping-method-list',
            shippingMethodItemTemplate: 'Magento_Checkout/shipping-address/shipping-method-item'
        },
        visible: ko.observable(!quote.isVirtual()),
        errorValidationMessage: ko.observable(false),
        isCustomerLoggedIn: customer.isLoggedIn,
        isFormPopUpVisible: formPopUpState.isVisible,
        isFormInline: addressList().length === 0,
        isNewAddressAdded: ko.observable(false),
        saveInAddressBook: 1,
        quoteIsVirtual: quote.isVirtual(),

        /**
         * @return {exports}
         */
        initialize: function () {
            var self = this,
                hasNewAddress,
                fieldsetName = 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset';

            this._super();

            quote.shippingMethod.subscribe(this.shippingMethodObserver.bind(this));
            this.allowedDynamicalSave = true;

            if (!quote.isVirtual()) {
                stepNavigator.registerStep(
                    'shipping',
                    '',
                    $t('Shipping'),
                    this.visible, _.bind(this.navigate, this),
                    10
                );
            }
            checkoutDataResolver.resolveShippingAddress();

            hasNewAddress = addressList.some(function (address) {
                return address.getType() == 'new-customer-address'; //eslint-disable-line eqeqeq
            });

            this.isNewAddressAdded(hasNewAddress);

            this.isFormPopUpVisible.subscribe(function (value) {
                if (value) {
                    self.getPopUp().openModal();
                }
            });

            quote.shippingMethod.subscribe(function () {
                self.errorValidationMessage(false);
            });

            registry.async('checkoutProvider')(function (checkoutProvider) {
                var shippingAddressData = checkoutData.getShippingAddressFromData();

                if (shippingAddressData) {
                    checkoutProvider.set(
                        'shippingAddress',
                        $.extend(true, {}, checkoutProvider.get('shippingAddress'), shippingAddressData)
                    );
                }
                checkoutProvider.on('shippingAddress', function (shippingAddrsData) {
                    checkoutData.setShippingAddressFromData(shippingAddrsData);
                });
                shippingRatesValidator.initFields(fieldsetName);
            });

            return this;
        },

        /**
         * Navigator change hash handler.
         *
         * @param {Object} step - navigation step
         */
        navigate: function (step) {
            step && step.isVisible(true);
        },

        /**
         * @return {*}
         */
        getPopUp: function () {
            var self = this,
                buttons;

            if (!popUp) {
                buttons = this.popUpForm.options.buttons;
                this.popUpForm.options.buttons = [
                    {
                        text: buttons.save.text ? buttons.save.text : $t('Save Address'),
                        class: buttons.save.class ? buttons.save.class : 'action primary action-save-address',
                        click: self.saveNewAddress.bind(self)
                    },
                    {
                        text: buttons.cancel.text ? buttons.cancel.text : $t('Cancel'),
                        class: buttons.cancel.class ? buttons.cancel.class : 'action secondary action-hide-popup',

                        /** @inheritdoc */
                        click: this.onClosePopUp.bind(this)
                    }
                ];

                /** @inheritdoc */
                this.popUpForm.options.closed = function () {
                    self.isFormPopUpVisible(false);
                };

                this.popUpForm.options.modalCloseBtnHandler = this.onClosePopUp.bind(this);
                this.popUpForm.options.keyEventHandlers = {
                    escapeKey: this.onClosePopUp.bind(this)
                };

                /** @inheritdoc */
                this.popUpForm.options.opened = function () {
                    // Store temporary address for revert action in case when user click cancel action
                    self.temporaryAddress = $.extend(true, {}, checkoutData.getShippingAddressFromData());
                };
                popUp = modal(this.popUpForm.options, $(this.popUpForm.element));
            }

            return popUp;
        },

        /**
         * Revert address and close modal.
         */
        onClosePopUp: function () {
            checkoutData.setShippingAddressFromData($.extend(true, {}, this.temporaryAddress));
            this.getPopUp().closeModal();
        },

        /**
         * Show address form popup
         */
        showFormPopUp: function () {
            this.isFormPopUpVisible(true);
        },

        /**
         * Save new shipping address
         */
        saveNewAddress: function () {
            var addressData,
                newShippingAddress;

            this.source.set('params.invalid', false);
            this.triggerShippingDataValidateEvent();

            if (!this.source.get('params.invalid')) {
                addressData = this.source.get('shippingAddress');
                // if user clicked the checkbox, its value is true or false. Need to convert.
                addressData['save_in_address_book'] = this.saveInAddressBook ? 1 : 0;

                // New address must be selected as a shipping address
                newShippingAddress = createShippingAddress(addressData);
                selectShippingAddress(newShippingAddress);
                checkoutData.setSelectedShippingAddress(newShippingAddress.getKey());
                checkoutData.setNewCustomerShippingAddress($.extend(true, {}, addressData));
                this.getPopUp().closeModal();
                this.isNewAddressAdded(true);
            }
        },

        /**
         * Shipping Method View
         */
        rates: shippingService.getShippingRates(),
        isLoading: shippingService.isLoading,
        isSelected: ko.computed(function () {
            return quote.shippingMethod() ?
                quote.shippingMethod()['carrier_code'] + '_' + quote.shippingMethod()['method_code'] :
                null;
        }),

        /**
         * @param {Object} shippingMethod
         * @return {Boolean}
         */
        selectShippingMethod: function (shippingMethod) {
            selectShippingMethodAction(shippingMethod);
            checkoutData.setSelectedShippingRate(shippingMethod['carrier_code'] + '_' + shippingMethod['method_code']);

            return true;
        },

        /**
         * Set shipping information handler
         */
        setShippingInformation: function () {
            if (this.validateShippingInformation()) {
                setShippingInformationAction().done(
                    function () {
                        stepNavigator.next();
                    }
                );
            }

            this.allowedDynamicalSave = false;
            var result = this._super();
            this.allowedDynamicalSave = true;
            return result;
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
         * @return {Boolean}
         */
        validateShippingInformation: function () {
            var shippingAddress,
                addressData,
                loginFormSelector = 'form[data-role=email-with-possible-login]',
                emailValidationResult = customer.isLoggedIn(),
                field,
                servicePointData = JSON.parse(window.sessionStorage.getItem('service-point-data'));

            if (quote.shippingMethod()['carrier_code'] === 'sendcloud' && !servicePointData) {
                this.errorValidationMessage($t('Please select a service point'));

                return false;
            }

            if (!quote.shippingMethod()) {
                this.errorValidationMessage($t('Please specify a shipping method.'));

                return false;
            }

            if (!customer.isLoggedIn()) {
                $(loginFormSelector).validation();
                emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
            }

            if (this.isFormInline) {
                this.source.set('params.invalid', false);
                this.triggerShippingDataValidateEvent();

                if (emailValidationResult &&
                    this.source.get('params.invalid') ||
                    !quote.shippingMethod()['method_code'] ||
                    !quote.shippingMethod()['carrier_code']
                ) {
                    this.focusInvalid();

                    return false;
                }

                shippingAddress = quote.shippingAddress();
                addressData = addressConverter.formAddressDataToQuoteAddress(
                    this.source.get('shippingAddress')
                );

                //Copy form data to quote shipping address object
                for (field in addressData) {
                    if (addressData.hasOwnProperty(field) &&  //eslint-disable-line max-depth
                        shippingAddress.hasOwnProperty(field) &&
                        typeof addressData[field] != 'function' &&
                        _.isEqual(shippingAddress[field], addressData[field])
                    ) {
                        shippingAddress[field] = addressData[field];
                    } else if (typeof addressData[field] != 'function' &&
                        !_.isEqual(shippingAddress[field], addressData[field])) {
                        shippingAddress = addressData;
                        break;
                    }
                }

                if (customer.isLoggedIn()) {
                    shippingAddress['save_in_address_book'] = 1;
                }
                selectShippingAddress(shippingAddress);
            }

            if (!emailValidationResult) {
                $(loginFormSelector + ' input[name=username]').focus();

                return false;
            }

            return true;
        },

        /**
         * Trigger Shipping data Validate Event.
         */
        triggerShippingDataValidateEvent: function () {
            this.source.trigger('shippingAddress.data.validate');

            if (this.source.get('shippingAddress.custom_attributes')) {
                this.source.trigger('shippingAddress.custom_attributes.data.validate');
            }
        }
    });

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
                var instance = this;

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
});
