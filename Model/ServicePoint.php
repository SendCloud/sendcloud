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

    public function activate()
    {
        return "activate";
    }

    public function deactivate()
    {
        return "deactivate";
    }
}