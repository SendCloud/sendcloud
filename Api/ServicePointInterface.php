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
    public function activate();

    public function deactivate();
}