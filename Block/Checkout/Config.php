<?php

namespace SendCloud\SendCloud\Block\Checkout;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use SendCloud\SendCloud\Logger\SendCloudLogger;

class Config extends Template
{
    private SendCloudLogger $sendCloudLogger;

    /**
     * Config Constructor.
     *
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(SendCloudLogger $sendCloudLogger, Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->sendCloudLogger = $sendCloudLogger;
    }

    /**
     * @return mixed
     */
    public function getScriptUrl()
    {
        $scriptUrl = $this->_scopeConfig->getValue('sendcloud/sendcloud/script_url', ScopeInterface::SCOPE_STORE);
        $this->sendCloudLogger->info("Script url: " . $scriptUrl);

        return $scriptUrl;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        $locale = $this->_scopeConfig->getValue('general/locale/code', ScopeInterface::SCOPE_STORE);
        $this->sendCloudLogger->info("Locale: " . $locale);

        return $locale;
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        return [
            ' is required.' => __(' is required.'),
            'a Full Service Digital Agency.' => __('a Full Service Digital Agency.'),
            'Billing Address' => __('Billing Address'),
            'Billing Info' => __('Billing Info'),
            'Branding' => __('Branding'),
            'Calculate Handling Fee' => __('Calculate Handling Fee'),
            'Cancel' => __('Cancel'),
            'Change service point' => __('Change service point'),
            'Clear search' => __('Clear search'),
            'Closed' => __('Closed'),
            'Closed tomorrow' => __('Closed tomorrow'),
            'Connect to Sendcloud' => __('Connect to Sendcloud'),
            'Could not find any service points for this location.' => __('Could not find any service points for this location.'),
            'Day' => __('Day'),
            'Delivery day must be selected before order purchase. Please return to shipping and select delivery date.' => __('Delivery day must be selected before order purchase. Please return to shipping and select delivery date.'),
            'Displayed Error Message' => __('Displayed Error Message'),
            'edit' => __('edit'),
            'Enable Free Shipping Threshold' => __('Enable Free Shipping Threshold'),
            'Enabled' => __('Enabled'),
            'Field' => __('Field'),
            'for support and more information.' => __('for support and more information.'),
            'Free shipping above: ' => __('Free shipping above: '),
            'Free Shipping Amount Threshold' => __('Free Shipping Amount Threshold'),
            'Free shipping' => __('Free shipping'),
            'Fri' => __('Fri'),
            'Friday' => __('Friday'),
            'General Configuration' => __('General Configuration'),
            'Handling Fee' => __('Handling Fee'),
            'Heads Up! The opening times of this service point have changed. If the opening times are still OK to you, click on select again.' => __('Heads Up! The opening times of this service point have changed. If the opening times are still OK to you, click on select again.'),
            'Incl. Tax' => __('Incl. Tax'),
            'Items Shipped' => __('Items Shipped'),
            'Method Name' => __('Method Name'),
            'Mon' => __('Mon'),
            'Monday' => __('Monday'),
            'No shipping information available' => __('No shipping information available'),
            'Once your package ships we will send you a tracking number.' => __('Once your package ships we will send you a tracking number.'),
            'One of the items in your cart is too large to be shipped to a service point.' => __('One of the items in your cart is too large to be shipped to a service point.'),
            'Open tomorrow' => __('Open tomorrow'),
            'Opening times' => __('Opening times'),
            'Order Information' => __('Order Information'),
            'Order Total' => __('Order Total'),
            'Payment and Shipping Method' => __('Payment and Shipping Method'),
            'Payment Information' => __('Payment Information'),
            'Payment Method' => __('Payment Method'),
            'Please select a service point' => __('Please select a service point'),
            'Please specify a shipping method.' => __('Please specify a shipping method.'),
            'PO box number' => __('PO box number'),
            'PO box number is required.' => __('PO box number is required.'),
            'Post office box' => __('Post office box'),
            'Price' => __('Price'),
            'Sat' => __('Sat'),
            'Saturday' => __('Saturday'),
            'Save Address' => __('Save Address'),
            'Search' => __('Search'),
            'Search for service point locations' => __('Search for service point locations'),
            'Select' => __('Select'),
            'Select location' => __('Select location'),
            'Select service point' => __('Select service point'),
            'Selected' => __('Selected'),
            'Sendcloud checkout data missing' => __('Sendcloud checkout data missing'),
            'Sendcloud service point' => __('Sendcloud service point'),
            'Service Point' => __('Service Point'),
            'Service Point must be selected before order purchase. Please return to shipping and select service point.' => __('Service Point must be selected before order purchase. Please return to shipping and select service point.'),
            'Ship to Applicable Countries' => __('Ship to Applicable Countries'),
            'Ship to Specific Countries' => __('Ship to Specific Countries'),
            'Ship To' => __('Ship To'),
            'Shipment History' => __('Shipment History'),
            'Shipping' => __('Shipping'),
            'Shipping Address' => __('Shipping Address'),
            'Shipping and Handling Information' => __('Shipping and Handling Information'),
            'Shipping and Tracking Information' => __('Shipping and Tracking Information'),
            'Shipping Info' => __('Shipping Info'),
            'Shipping Method' => __('Shipping Method'),
            'Show Method if Not Applicable' => __('Show Method if Not Applicable'),
            'Sorry, this service point is no longer available.' => __('Sorry, this service point is no longer available.'),
            'Sorry, we were unable to record your selected service point. Please try again.' => __('Sorry, we were unable to record your selected service point. Please try again.'),
            'Sort Order' => __('Sort Order'),
            'Sun' => __('Sun'),
            'Sunday' => __('Sunday'),
            'Temporarily unavailable' => __('Temporarily unavailable'),
            'Thank you for your order from %store_name. ' => __('Thank you for your order from %store_name. '),
            'The order was placed using %1.' => __('The order was placed using %1.'),
            'Thu' => __('Thu'),
            'Thursday' => __('Thursday'),
            'Title' => __('Title'),
            'Toggle list' => __('Toggle list'),
            'Toggle map' => __('Toggle map'),
            'Total Shipping Charges' => __('Total Shipping Charges'),
            'Track Order' => __('Track Order'),
            'Track this shipment' => __('Track this shipment'),
            'Tue' => __('Tue'),
            'Tuesday' => __('Tuesday'),
            'View opening times' => __('View opening times'),
            'We couldn’t display this delivery option. Choose another option to continue.' => __('We couldn’t display this delivery option. Choose another option to continue.'),
            'Wed' => __('Wed'),
            'Wednesday' => __('Wednesday'),
            'Your %store_name order confirmation' => __('Your %store_name order confirmation'),
            'Your %store_name order has shipped ' => __('Your %store_name order has shipped '),
            'Your Shipment #%shipment_id for Order #%order_id' => __('Your Shipment #%shipment_id for Order #%order_id'),
            'Your shipping confirmation is below. Thank you again for your business.' => __('Your shipping confirmation is below. Thank you again for your business.'),
        ];
    }
}
