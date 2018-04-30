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
    const SCRIPT_URL = 'https://embed.sendcloud.sc/spp/1.0.0/api.min.js';
    const SHIPPING_FLAG = 1;

    protected function setUp()
    {
        parent::setUp();

        $this->servicePoint = $this->objectManager->getObject('CreativeICT\SendCloud\Model\ServicePoint', []);
    }

    public function testActivate()
    {
        $result = array('message' => array('success' => 'Shipping method is activated an script url is set'));

        $this->assertEquals($result, $this->servicePoint->activate(self::SCRIPT_URL));
    }

    public function testDeactivate()
    {
        $result = array('message' => array('success' => 'Shipping method is deactivated'));

        $this->assertEquals($result, $this->servicePoint->deactivate());
    }

    public function testShippingEmail()
    {
        $result = array('message' => array('success' => 'Shipment email is activated'));

        $this->assertEquals($result, $this->servicePoint->shippingEmail(self::SHIPPING_FLAG));
    }

}