<?php

/**
 * Created by PhpStorm.
 * User: wessel
 * Date: 24-02-18
 * Time: 14:35
 */

namespace CreativeICT\SendCloud\Test\Unit\Controller;

use CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect\AutoGenerateApiUser;
use Magento\Test\Event\MagentoTest;
use PHPunit\Framework\TestCase;

class AutoGenerateApiUserTest extends TestCase
{
    public function testGetOrCreateApiUser()
    {
        $testclass = new MagentoTest('test');
        $this->assertEquals('sendcloud', 'test');
    }
}
