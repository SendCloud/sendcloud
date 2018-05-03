<?php

namespace CreativeICT\SendCloud\Test\Unit\Controller;


use CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect\Connector;
use CreativeICT\SendCloud\Test\Unit\Generic;
use Magento\Framework\Controller\ResultFactory;

class ConnectorTest extends Generic
{
    const PASSWORD = 'test1234';
    const URL = 'https://panel.sendcloud.sc/shops/magento_v2/connect/?shop_url=http%3A%2F%2F5bb261fb.ngrok.io%2F&username=sendcloud&password=test1234';

    private $connector;

    private $contextMock;

    private $resultPageMock;

    private $autoGenerateApiMock;

    private $mathRandomMock;

    private $loggerMock;

    private $resultFactoryMock;

    private $urlInterfaceMock;

    protected function setUp()
    {
        parent::setUp();

        $this->contextMock = $this->getMockBuilder('Magento\Backend\App\Action\Context')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultPageMock = $this->getMockBuilder('Magento\Framework\View\Result\PageFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->autoGenerateApiMock = $this->getMockBuilder('CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect\AutoGenerateApiUser')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mathRandomMock = $this->getMockBuilder('Magento\Framework\Math\Random')
            ->disableOriginalConstructor()
            ->getMock();

        $this->loggerMock = $this->getMockBuilder('CreativeICT\SendCloud\Logger\SendCloudLogger')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultFactoryMock = $this->getMockBuilder('Magento\Framework\Controller\ResultFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setData'])
            ->getMock();

        $this->urlInterfaceMock = $this->getMockBuilder('Magento\Backend\Model\UrlInterface')
            ->disableOriginalConstructor()
            ->setMethods(['getBaseUrl'])
            ->getMock();

        /** @var Connector connector */
        $this->connector = new Connector(
            $this->contextMock,
            $this->resultPageMock,
            $this->autoGenerateApiMock,
            $this->mathRandomMock,
            $this->loggerMock,
            $this->resultFactoryMock
        );
    }

    public function testExecute()
    {
        $baseUrl = $this->urlInterfaceMock->method('getBaseUrl')
            ->willReturn(self::url);

        $responseData = array(
            "url" => $baseUrl
        );

        $this->resultFactoryMock->method('create')
            ->with(ResultFactory::TYPE_JSON)
            ->willReturn($this->resultFactoryMock);

        $this->resultFactoryMock->method('setData')
            ->with($responseData)
            ->willReturn($this->resultFactoryMock);

        $this->assertEquals('test', $this->connector->execute());
    }
}