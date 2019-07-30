<?php

namespace SendCloud\SendCloud\Test\Unit\Model\Carrier;

use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Shipping\Model\Simplexml\ElementFactory;
use Magento\Shipping\Model\Tracking\Result\StatusFactory;
use SendCloud\SendCloud\Test\Unit\Generic;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrierInterface;

class SendCloudTest extends Generic
{
    const METHOD_CODE = 'sendcloud';
    const METHOD_TITLE = 'Sendcloud';

    /** @var \SendCloud\SendCloud\Model\Carrier\SendCloud */
    private $sendCloud;

    /** @var ErrorFactory */
    private $mockErrorFactory;

    /** @var ResultFactory */
    private $mockResultFactory;

    /** @var MethodFactory */
    private $mockMethodFactory;

    /** @var AbstractCarrier */
    private $mockAbstractCarrier;

    /** @var RateRequest */
    private $mockRateRequest;

    /** @var ElementFactory */
    private $elementFactory;

    /** @var \Magento\Shipping\Model\Tracking\ResultFactory */
    private $mockTrackFactory;

    /** @var \Magento\Shipping\Model\Tracking\Result\ErrorFactory */
    private $mockTrackErrorFactory;

    /** @var StatusFactory */
    private $mockStatusFactory;

    /** @var CountryFactory */
    private $mockCountryFactory;

    /** @var CurrencyFactory */
    private $mockCurrencyFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->mockErrorFactory = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->elementFactory = $this->getMockBuilder('Magento\Shipping\Model\Simplexml\ElementFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockResultFactory = $this->getMockBuilder('Magento\Shipping\Model\Rate\ResultFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create', 'append'])
            ->getMock();

        $this->mockMethodFactory = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateResult\MethodFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setCarrier', 'setMethodTitle'])
            ->getMock();

        $this->mockAbstractCarrier = $this->getMockBuilder('Magento\Shipping\Model\Carrier\AbstractCarrier', [])
            ->disableOriginalConstructor()
            ->setMethods(['getConfigData', 'collectRates'])
            ->getMock();

        $this->mockTrackFactory = $this->getMockBuilder('Magento\Shipping\Model\Tracking\ResultFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockTrackErrorFactory = $this->getMockBuilder('Magento\Shipping\Model\Tracking\Result\ErrorFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockRateRequest = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockStatusFactory = $this->getMockBuilder('Magento\Shipping\Model\Tracking\Result\StatusFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockCountryFactory = $this->getMockBuilder('Magento\Directory\Model\CountryFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockCurrencyFactory = $this->getMockBuilder('Magento\Directory\Model\CurrencyFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->sendCloud = $this->objectManager->getObject('SendCloud\SendCloud\Model\Carrier\SendCloud', []);
    }

    public function testCollectRates()
    {
        $this->mockResultFactory->method('create')
            ->willReturn($this->mockResultFactory);

        $this->mockMethodFactory->method('create')
            ->willReturn($this->mockMethodFactory);

        $this->mockMethodFactory->method('setCarrier')
            ->with(self::METHOD_CODE)
            ->willReturn($this->mockMethodFactory);
        $this->mockMethodFactory->method('setMethodTitle')
            ->with(self::METHOD_TITLE)
            ->willReturn($this->mockMethodFactory);

        $this->mockResultFactory->method('append')
            ->with($this->mockMethodFactory)
            ->willReturn($this->mockResultFactory);

        $this->assertEquals(false, $this->sendCloud->collectRates($this->mockRateRequest));
    }
}
