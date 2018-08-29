<?php
/**
 * Created by PhpStorm.
 * User: wessel
 * Date: 27-02-18
 * Time: 21:43
 */

namespace SendCloud\SendCloud\Api;

/**
 * Interface ServicePointInterface
 * @package SendCloud\SendCloud\Api
 */
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
     * @param boolean $activate
     * @return mixed
     */
    public function shippingEmail($activate);
}
