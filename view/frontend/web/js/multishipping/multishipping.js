define([
    'jquery',
    'https://cdn.jsdelivr.net/npm/@sendcloud/checkout-plugin-ui@^2.0.0/dist/checkout-plugin-ui-loader.js'
], function ($) {
    'use strict';
    var self = this;
    var configuration = [];
    var checkoutMethods = {};
    var renderedWidgetDestructors = {};
    var widgetsStates = {};
    var addresses = null;
    return {
        initialize: function (data) {

            $.get('/sendcloud/multishipping/index',
                {
                    "methods[]": Object.values(data.methods)
                },
                function (data) {
                    if (data) {
                        for (const property in data) {
                            configuration['sendcloudcheckout_' + property] = JSON.parse(data[property]);
                        }
                    }
                });
            addresses = data.addresses;
            document.querySelectorAll('.continue').forEach(item => {
                item.addEventListener('click', event => {
                    this.removeWidgets();
                });
            });
            document.querySelectorAll('.radio').forEach(item => {
                item.addEventListener('click', event => {
                    this.handleMethodChange(event);
                });
            });
            document.querySelectorAll('.item-content').forEach(item => {
                let shippingMethods = item.querySelectorAll('[class="radio"][value^="sendcloudcheckout"]');

                if (shippingMethods.length > 0) {
                    let input = document.createElement("input");
                    input.type = "text";
                    input.hidden = true;
                    input.name = shippingMethods[0].name.replace('shipping_method', 'sc_delivery_method_data');
                    input.id = shippingMethods[0].name.replace('shipping_method', 'sc_delivery_method_data');
                    item.parentElement.appendChild(input);

                    let div = document.createElement("div");
                    div.id = shippingMethods[0].name.replace('shipping_method', 'sendcloud_chekout_widget');
                    item.parentElement.appendChild(div)
                }
            });
        },
        handleMethodChange: function (event) {
            let addressId = event.target.name.substring(event.target.name.indexOf("[") + 1, event.target.name.lastIndexOf("]"))
            if (renderedWidgetDestructors[addressId]) {
                renderedWidgetDestructors[addressId].call();
            }
            if (event.target.value in configuration) {
                if (configuration[event.target.value].delivery_method_type === 'nominated_day_delivery' || configuration[event.target.value].delivery_method_type === 'service_point_delivery') {
                    this.showWidget(event.target);
                } else if(configuration[event.target.value].delivery_method_type === 'same_day_delivery') {
                    document.getElementById('sc_delivery_method_data[' + addressId + ']').value =
                        JSON.stringify({
                            ...configuration[event.target.defaultValue],
                            delivery_method_data:{
                                delivery_date: new Date().toISOString(),
                                formatted_delivery_date: getFormatedCurrentDate()
                            }
                        });
                } else {
                    document.getElementById('sc_delivery_method_data[' + addressId + ']').value = JSON.stringify(configuration[event.target.defaultValue]);
                }
            }

        },
        showWidget: function (targetMethod) {
            let SPdata = {};
            let methodExtensionAttributes = configuration[targetMethod.value];
            let addressId = targetMethod.name.substring(targetMethod.name.indexOf("[") + 1, targetMethod.name.lastIndexOf("]"))
            if (methodExtensionAttributes.delivery_method_type == 'service_point_delivery') {
                let address = addresses[addressId];
                SPdata = {
                    'service_point_id': '',
                    'post_number': '',
                    'postal_code': address['postcode'],
                    'city': address['city'],
                    'cart_price': address['grand_total'],
                    'cart_price_currency': address['base_currency_code'],
                    'cart_weight': address['weight_in_grams'],
                    'cart_weight_unit': 'g',
                    'store_order_id': '',
                    'checkout_shipping_method_id': '',
                    'checkout_shipping_method_name': ''
                }
            }

            if ((methodExtensionAttributes.delivery_method_type === 'nominated_day_delivery' || methodExtensionAttributes.delivery_method_type === 'service_point_delivery')) {
                let mountElement = document.getElementById('sendcloud_chekout_widget[' + addressId + ']');
                let state = {};
                if (widgetsStates[addressId] && widgetsStates[addressId][methodExtensionAttributes.delivery_method_type]) {
                    state = widgetsStates[addressId][methodExtensionAttributes.delivery_method_type]
                }

                if (!mountElement) {
                    return;
                }

                $(mountElement).on('scShippingOptionChange', this.onSelectionChange);

                window.renderScShippingOption({
                    mountElement: mountElement,
                    deliveryMethod: methodExtensionAttributes,
                    shippingData: SPdata,
                    renderDate: new Date(),
                    locale: 'en-US',
                    state: state,
                }).then(function (destructorCallback) {
                    renderedWidgetDestructors[addressId] = destructorCallback
                });

                checkoutMethods[addressId] = targetMethod;
            }
        },
        onSelectionChange: function (event) {
            let addressId = event.target.id.substring(event.target.id.lastIndexOf("[") + 1, event.target.id.lastIndexOf("]"));
            let targetMethod = checkoutMethods[addressId];
            var data = JSON.stringify({
                ...configuration[targetMethod.defaultValue],
                ...event.detail.data
            });

            document.getElementById('sc_delivery_method_data[' + addressId + ']').value = data;
            if (!widgetsStates[addressId]) {
                widgetsStates[addressId] = {}
            }

            widgetsStates[addressId][configuration[targetMethod.defaultValue].delivery_method_type] = event.detail.state;
        },
        removeWidgets: function () {
            Object.keys(renderedWidgetDestructors).forEach(key => {
                renderedWidgetDestructors[key].call();
            });
        },
    }
    function getFormatedCurrentDate() {
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        const d = new Date();
        return months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
    }
});
