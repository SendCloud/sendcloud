<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 3-4-18
 * Time: 14:01
 */

namespace CreativeICT\SendCloud\Test\Unit\Model\Carrier;


use CreativeICT\SendCloud\Test\Unit\Generic;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrierInterface;

class SendCloudTest extends Generic
{
    /** @var \CreativeICT\SendCloud\Model\Carrier\SendCloud */
    private $sendCloud;

    private $mockErrorFactory;

    private $mockResultFactory;

    private $mockMethodFactory;

    private $mockAbstractCarrier;

    private $mockRateRequest;

    protected function setUp()
    {
        parent::setUp();

        $this->mockErrorFactory = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockResultFactory = $this->getMockBuilder('Magento\Shipping\Model\Rate\ResultFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockMethodFactory = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateResult\MethodFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockAbstractCarrier = $this->getMockBuilder('Magento\Shipping\Model\Carrier\AbstractCarrier', [])
            ->disableOriginalConstructor()
            ->setMethods(['getConfigData', 'collectRates'])
            ->getMock();

        $this->mockRateRequest = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateRequest', []);

        $this->sendCloud = $this->objectManager->getObject('CreativeICT\SendCloud\Model\Carrier\SendCloud', []);
    }

    public function testGetAllowedMethods()
    {
        $code = array('sendcloud' => NULL);
        $this->assertEquals($code, $this->sendCloud->getAllowedMethods());
    }

    public function testCollectRates()
    {
        //$this->assertEquals(array('test'), $this->sendCloud->collectRates(RateRequest::class));
        $this->assertEquals(array('test'), $this->sendCloud->collectRates(\Magento\Quote\Model\Quote\Address\RateRequest));
    }
}