<?php

namespace CreativeICT\SendCloud\Test\Unit\Controller;


use CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect\Connector;
use CreativeICT\SendCloud\Test\Unit\Generic;

class TestConnector extends Generic
{
    private $controller;

    protected function setUp()
    {
        parent::setUp();
        $this->controller = $this->objectManager->getObject(Index::class, []);
    }

    public function testExecute()
    {
        //$this->controller->execute();
    }
}