<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 28-3-18
 * Time: 17:04
 */

namespace CreativeICT\SendCloud\Test\Unit\Block;

use CreativeICT\SendCloud\Test\Unit\Generic;

class TestButton extends Generic
{
    private $button;
    private $urlInterface;

    const WEBSHOP_URL = 'https://www.webshop.nl/admin/creativeict_autoconnect/autoconnect/index';

    protected function setUp()
    {
        parent::setUp();
        $this->urlInterface = $this->getMockBuilder('Magento\Framework\UrlInterface')
            ->disableOriginalConstructor()
            ->enableOriginalClone()
            ->getMock();

        $this->contextMock = $this->getMockBuilder('Magento\Backend\Block\Template\Context')
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->method('getUrlBuilder')
            ->willReturn($this->urlInterface);

        $this->urlInterface->expects($this->once())
            ->method('getUrl')
            ->willReturn(self::WEBSHOP_URL);

        $this->button = $this->objectManager->getObject('CreativeICT\SendCloud\Block\System\Config\Form\Button', [
            'context' => $this->contextMock,
        ]);
    }

    public function testGetAjaxCheckUrl()
    {
        $this->assertEquals(self::WEBSHOP_URL, $this->button->getAjaxCheckUrl());
    }
}