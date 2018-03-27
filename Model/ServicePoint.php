<?php
/**
 * Created by PhpStorm.
 * User: wessel
 * Date: 27-02-18
 * Time: 21:46
 */

namespace CreativeICT\SendCloud\Model;


use CreativeICT\SendCloud\Api\ServicePointInterface;

class ServicePoint implements ServicePointInterface
{
    /**
     * @return string
     */
    public function activate()
    {
        return "activate";
    }

    /**
     * @return string
     */
    public function deactivate()
    {
        return "deactivate";
    }

    /**
     * @return string
     */
    public function shippingEmailActivate()
    {
        return "activate";
    }

    /**
     * @return string
     */
    public function shippingEmailDeactivate()
    {
        return "deactivate";
    }
}