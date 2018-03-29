<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 29-3-18
 * Time: 13:55
 */

namespace CreativeICT\SendCloud\Test\Unit\Model;


use CreativeICT\SendCloud\Test\Unit\Generic;

class TestSettings extends Generic
{
    private $settings;

    const MODULE_INFORMATION = "Module information";
    const SCRIPT_URL = "https://sendcloud.nl/scripturltest";

    protected function setUp()
    {
        parent::setUp();

        $this->settings = $this->objectManager->getObject('CreativeICT\SendCloud\Model\Settings', []);
    }

    public function testGetModuleInformation()
    {
        $this->assertEquals(self::MODULE_INFORMATION, $this->settings->getModuleInformation());
    }

    public function testSetScriptUrl()
    {
        $this->assertEquals(self::SCRIPT_URL, $this->settings->setScriptUrl(self::SCRIPT_URL));
    }
}