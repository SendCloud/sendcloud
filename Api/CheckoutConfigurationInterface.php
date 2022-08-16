<?php

namespace SendCloud\SendCloud\Api;

interface CheckoutConfigurationInterface
{
    /**
     * @api
     * @param int $store_view_id
     * @param mixed $checkout_configuration
     * @return mixed[]
     */
    public function update($store_view_id, $checkout_configuration);

    /**
     * @param int $store_view_id
     * @return mixed
     */
    public function delete($store_view_id);

    /**
     * @param int $store_view_id
     * @return mixed
     */
    public function deleteIntegration($store_view_id);
}
