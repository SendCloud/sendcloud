<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 29-3-18
 * Time: 13:55
 */

namespace SendCloud\SendCloud\Test\Unit\Model;


use SendCloud\SendCloud\Test\Unit\Generic;

class SettingsTest extends Generic
{
    private $settings;

    const MODULE_INFORMATION = [["Version" => NULL]];
    const SCRIPT_URL = "https://sendcloud.nl/scripturltest";

    protected function setUp()
    {
        parent::setUp();

        $this->settings = $this->objectManager->getObject('SendCloud\SendCloud\Model\Settings', []);
    }

    public function testGetModuleInformation()
    {
        $this->assertEquals(self::MODULE_INFORMATION, $this->settings->getModuleInformation());
    }
}