<?php
/**
 * Created by PhpStorm.
 * User: wessel
 * Date: 27-02-18
 * Time: 21:43
 */

namespace CreativeICT\SendCloud\Api;


interface ServicePointInterface
{
    /**
     * @api
     * @param string $script_url
     * @return array|mixed
     */
    public function activate($script_url);

    /**
     * @return mixed
     */
    public function deactivate();

    /**
     * @return mixed
     */
    public function shippingEmailActivate();

    /**
     * @return mixed
     */
    public function shippingEmailDeactivate();
}