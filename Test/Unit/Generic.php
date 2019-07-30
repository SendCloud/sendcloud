<?php

namespace SendCloud\SendCloud\Test\Unit;

use Magento\TestFramework\ObjectManager;

class Generic extends TestCaseFinder
{
    /** @var ObjectManager */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
    }
}
