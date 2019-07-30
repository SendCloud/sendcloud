<?php

namespace SendCloud\SendCloud\Test\Unit\Model;

use SendCloud\SendCloud\Test\Unit\Generic;

class SettingsTest extends Generic
{
    private $settings;

    public static $MODULE_INFORMATION = [["Version" => null]];
    const SCRIPT_URL = "https://sendcloud.nl/scripturltest";

    protected function setUp()
    {
        parent::setUp();

        $this->settings = $this->objectManager->getObject('SendCloud\SendCloud\Model\Settings', []);
    }

    public function testGetModuleInformation()
    {
        $this->assertEquals(self::$MODULE_INFORMATION, $this->settings->getModuleInformation());
    }
}
