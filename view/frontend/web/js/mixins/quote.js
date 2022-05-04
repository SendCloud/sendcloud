define([
    'ko',
], function (ko) {
    'use strict';

    return function (Component) {
        var sendcloudDeliveryMethodConfig = ko.computed(function (){
            let method = Component.shippingMethod();

            if(!method || !method.extension_attributes || !method.extension_attributes.method_configuration) {
                return null;
            }

            return JSON.parse(method.extension_attributes.method_configuration);
        }, this);

        var sendcloudCheckoutData = ko.observable(JSON.parse(window.checkoutConfig.quoteData.extension_attributes.sendcloud_checkout_data));
        
        //for legacy service point
        var extensionAttributes = ko.observable(window.checkoutConfig.quoteData.extension_attributes);

        Component.getCheckoutExtensionAttributes = function () {
            return {...sendcloudDeliveryMethodConfig(), delivery_method_data:{...Component.getDeliveryMethodData()}, state: {...Component.getState()}};
        }

        Component.getSendcloudDeliveryMethodConfig = function () {
            return sendcloudDeliveryMethodConfig() ? sendcloudDeliveryMethodConfig() : null;
        }

        Component.getDeliveryMethodData = function () {
            if(Component.getSendcloudDeliveryMethodType() === 'standard_delivery') {
                return null;
            }

            if(Component.getSendcloudDeliveryMethodType() === 'same_day_delivery') {
                return getSameDayData();
            }

            return sendcloudCheckoutData() ? sendcloudCheckoutData().delivery_method_data : null;
        }

        Component.setDeliveryMethodData = function (delivery_method_data) {
            return sendcloudCheckoutData({...sendcloudCheckoutData(), ...delivery_method_data});
        }

        Component.hasDeliveryMethodData = function () {
            let chekoutData = Component.getCheckoutExtensionAttributes();

            if(!chekoutData['delivery_method_data'] || Object.keys(chekoutData['delivery_method_data']).length === 0) {
                return false;
            }
            if(Component.getSendcloudDeliveryMethodType() === 'service_point_delivery' && !chekoutData['delivery_method_data']['service_point']) {
                return false;
            }
            if(Component.getSendcloudDeliveryMethodType() === 'nominated_day_delivery' && !chekoutData['delivery_method_data']['delivery_date']) {
                return false;
            }
            
            return true;
        }

        Component.getState = function () {
            if(!sendcloudCheckoutData()) {
                return {};
            }

            return sendcloudCheckoutData().state;
        }

        Component.setState = function (widgetState) {
            return sendcloudCheckoutData({...sendcloudCheckoutData(), ...widgetState});
        }

        Component.getSendcloudDeliveryMethodId = function () {
            return sendcloudDeliveryMethodConfig() ? sendcloudDeliveryMethodConfig().id : null;
        }

        Component.getSendcloudDeliveryMethodType = function () {
            return sendcloudDeliveryMethodConfig() ? sendcloudDeliveryMethodConfig().delivery_method_type :
                Component.shippingMethod() && Component.shippingMethod().method_code === 'sendcloud' ?
                    'service_point_legacy' : null;
        }

        //for legacy service point
        Component.setExtensionAttributes = function (extension_attributes) {
            extensionAttributes({...extensionAttributes(), ...extension_attributes});
        }

        //for legacy service point
        Component.getExtensionAttributes = function () {
            return extensionAttributes();
        }

        return Component;
    }
    
    function getSameDayData() {
        return {
            delivery_date: new Date().toISOString(),
            formatted_delivery_date: getFormatedCurrentDate(),
            parcel_handover_date: new Date().toISOString()
        };
    }
    
    function getFormatedCurrentDate() {
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        const d = new Date();
        return months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
    }
});
