define([
        'jquery',
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/action/set-shipping-information',
        'uiRegistry',
        'mage/translate',
        'https://embed.sendcloud.sc/spp/1.0.0/api.min.js',
        'https://cdn.jsdelivr.net/npm/@sendcloud/checkout-plugin-ui@^2.0.0/dist/checkout-plugin-ui-loader.js'
    ], function ($, ko, Component, quote, customer, setShippingInformationAction, registry) {
        'use strict';

        const widgetShowingDeliveryMethodTypes = ['nominated_day_delivery', 'service_point_delivery'];

        let renderedWidgetDestructor = null;
        let selectedDeliveryMethodId = null;
        let mountElement = null;

        let widgetStates = {};
        function getWidgetState() {
            let state = quote.getState();

            if (widgetStates[quote.getSendcloudDeliveryMethodId()]) {
                state = widgetStates[quote.getSendcloudDeliveryMethodId()]
            }

            return state;
        }

        function setWidgetState(state) {
            widgetStates[selectedDeliveryMethodId] = state;
            quote.setState({state: state});
        }

        return Component.extend({
            defaults: {
                template: 'SendCloud_SendCloud/checkout/shipping/checkout',
            },
            quote: quote,
            initObservable: function () {
                this.renderWidget = ko.computed(function () {
                    mountElement = document.querySelector(`#sendcloud-chekout`);
                    if (!mountElement) {
                        return true;
                    }

                    addEventListenerToPostCodeField();

                    renderWidgetHandler();

                    return true;
                }, this);

                return this;
            }
        });

    function addEventListenerToPostCodeField() {
        let postalCodeInputField = document.querySelector('input[name="postcode"]');

        if (postalCodeInputField) {
            postalCodeInputField.addEventListener('change', (event) => {
                doRenderWidget();
            });
        }
    }

        function renderWidgetHandler() {
            if (isDeliveryMethodChanged()) {
                doRenderWidget();
            }
        }

        function isDeliveryMethodChanged() {
            return quote.getSendcloudDeliveryMethodId() !== selectedDeliveryMethodId;
        }

        function doRenderWidget() {
            initWidget();

            if (shouldShowWidget()) {
                showWidget();
            }

            updateBackendData();
        }

        function initWidget() {
            if (renderedWidgetDestructor) {
                renderedWidgetDestructor.call();
            }

            $(mountElement).on('scShippingOptionChange', onSelectionChange);
            selectedDeliveryMethodId = quote.getSendcloudDeliveryMethodId();
        }

        function shouldShowWidget() {
            return quote.getSendcloudDeliveryMethodConfig() &&
                widgetShowingDeliveryMethodTypes.indexOf(quote.getSendcloudDeliveryMethodType()) !== -1;
        }

        function showWidget() {
            window.renderScShippingOption({
                mountElement: mountElement,
                deliveryMethod: quote.getSendcloudDeliveryMethodConfig(),
                shippingData: getServicePointData(),
                renderDate: new Date(),
                locale: window.storeLocale.replace('_', '-'),
                state: getWidgetState(),
            }).then(function (destructorCallback) {
                renderedWidgetDestructor = destructorCallback;
            });
        }

        function onSelectionChange(event) {
            setWidgetState(event.detail.state);

            quote.setDeliveryMethodData(event.detail.data);

            updateBackendData();
        }

        function updateBackendData() {
            let shippingStepWidget = registry.get('checkout.steps.shipping-step.shippingAddress');

            if (shippingStepWidget && shippingStepWidget.validateShippingInformation()) {
                setShippingInformationAction();
            }
        }

        function getServicePointData() {
            let shippingAddress = quote.shippingAddress();
            let quoteExtensionAttributes = quote.getExtensionAttributes();
            let totals = quote.totals();
            return {
                'service_point_id': '',
                'post_number': '',
                'postal_code': shippingAddress['postcode'],
                'city': shippingAddress['city'],
                'cart_price': totals['grand_total'],
                'cart_price_currency': totals['base_currency_code'],
                'cart_weight': quoteExtensionAttributes['weight_in_grams'],
                'cart_weight_unit': 'g',
                'store_order_id': '',
                'checkout_shipping_method_id': '',
                'checkout_shipping_method_name': ''
            }
        }
    }
);
