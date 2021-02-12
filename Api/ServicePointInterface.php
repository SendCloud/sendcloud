<?php

namespace SendCloud\SendCloud\Api;

/**
 * Interface ServicePointInterface
 */
interface ServicePointInterface
{
    /**
     * @api
     * @param string $script_url
     * @return mixed[]
     */
    public function activate($script_url);

    /**
     * @return mixed
     */
    public function deactivate();

    /**
     * @param boolean $activate
     * @return mixed
     */
    public function shippingEmail($activate);
}
