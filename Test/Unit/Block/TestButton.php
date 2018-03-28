<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 28-3-18
 * Time: 17:04
 */

namespace CreativeICT\SendCloud\Test\Unit\Block;

use CreativeICT\SendCloud\Block\System\Config\Form\Button as Target;

class TestButton extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->contextMock = $this->getMockBuilder('\Magento\Backend\Block\Template\Context')
            ->disableOriginalConstructor()
            ->setMethods(['getUrl'])
            ->getMock();
        $this->url = $this->getMockBuilder('\Magento\Framework\UrlInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->button = new Target($this->contextMock);
    }

    public function testGetAjaxCheckUrl()
    {
        $this->url->method('getUrl')->will($this->returnValue('test'));
        $this->assertEquals($this->button->getAjaxCheckUrl(), 'test');
    }
}