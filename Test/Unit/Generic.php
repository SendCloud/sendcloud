<?php

namespace SendCloud\SendCloud\Test\Unit;

use Magento\TestFramework\ObjectManager;

class Generic extends \PHPUnit\Framework\TestCase
{
    /** @var ObjectManager */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
    }
}
