<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 3-4-18
 * Time: 12:50
 */

namespace CreativeICT\SendCloud\Test\Unit\Model;


use CreativeICT\SendCloud\Test\Unit\Generic;

class ServicePointTest extends Generic
{
    private $servicePoint;

    const ACTIVATE = 'activate';
    const DEACTIVATE = 'deactivate';
    const SHIPACTIVATE = 'activate';
    const SHIPDEACTIVATE = 'deactivate';

    protected function setUp()
    {
        parent::setUp();

        $this->servicePoint = $this->objectManager->getObject('CreativeICT\SendCloud\Model\ServicePoint', []);
    }

    public function testActivate()
    {
        $this->assertEquals(self::ACTIVATE, $this->servicePoint->activate());
    }

    public function testDeactivate()
    {
        $this->assertEquals(self::DEACTIVATE, $this->servicePoint->deactivate());
    }

    public function testShippingEmailActivate()
    {
        $this->assertEquals(self::SHIPACTIVATE, $this->servicePoint->shippingEmailActivate());
    }

    public function testShippingEmailDeactivate()
    {
        $this->assertEquals(self::SHIPDEACTIVATE, $this->servicePoint->shippingEmailDeactivate());
    }
}