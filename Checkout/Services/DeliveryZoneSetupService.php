<?php

namespace SendCloud\SendCloud\Checkout\Services;

use SendCloud\SendCloud\CheckoutCore\Contracts\Services\DeliveryZoneSetupService as DeliveryZoneSetupServiceInterface;

class DeliveryZoneSetupService implements DeliveryZoneSetupServiceInterface
{

    public function deleteSpecific(array $zones)
    {
        // Intentionally left out because there are no shipping zones in the Magento system
    }

    public function deleteAll()
    {
        // Intentionally left out because there are no shipping zones in the Magento system
    }

    public function update(array $zones)
    {
        // Intentionally left out because there are no shipping zones in the Magento system
    }

    public function create(array $zones)
    {
        // Intentionally left out because there are no shipping zones in the Magento system
    }
}
