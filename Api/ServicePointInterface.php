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
     * @return mixed
     */
    public function activate();

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