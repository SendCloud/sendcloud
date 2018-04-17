<?php

namespace CreativeICT\SendCloud\Test\Unit\Controller;

use CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect\AutoGenerateApiUser;
use CreativeICT\SendCloud\Test\Unit\Generic;

class AutoGenerateApiUserTest extends Generic
{
    const PASSWORD = 'test1234';
    const USERNAME = 'sendcloud';

    /** @var \Magento\User\Model\UserFactory */
    private $mockUserFactory;

    /** @var \Magento\Authorization\Model\RoleFactory */
    private $mockRoleFactory;

    /** @var \Magento\Authorization\Model\RulesFactory */
    private $mockRulesFactory;

    /** @var AutoGenerateApiUser */
    private $autoGenerateApiUser;

    protected function setUp()
    {
        parent::setUp();

        $this->mockUserFactory = $this->getMockBuilder('Magento\User\Model\UserFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setData', 'save', 'getUserName', 'getId', 'loadByUsername', 'setUsername', 'setPassword'])
            ->getMock();

        $this->mockRoleFactory = $this->getMockBuilder('\Magento\Authorization\Model\RoleFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setData', 'save', 'getId', 'setResources'])
            ->getMock();

        $this->mockRulesFactory = $this->getMockBuilder('\Magento\Authorization\Model\RulesFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setRoleId', 'setData', 'saveRel'])
            ->getMock();

        $this->sendCloudLogger = $this->getMockBuilder('CreativeICT\SendCloud\Logger\SendCloudLogger')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockUserFactory->method('create')
            ->willReturn($this->mockUserFactory);

        $this->mockRoleFactory->method('create')
            ->willReturn($this->mockRoleFactory);

        $this->mockRulesFactory->method('create')
            ->willReturn($this->mockRulesFactory);

        /** @var AutoGenerateApiUser autoGenerateApiUser */
        $this->autoGenerateApiUser = new AutoGenerateApiUser(
            $this->mockUserFactory,
            $this->sendCloudLogger,
            $this->mockRoleFactory,
            $this->mockRulesFactory
        );

    }

    public function testCreateApiUser()
    {
        $this->mockUserFactory->method('setData')
            ->willReturn($this->mockUserFactory);

        $this->mockRulesFactory->method('setRoleId')
            ->willReturn($this->mockRulesFactory);

        $this->mockRoleFactory->method('getId')
            ->willReturn(2);

        $this->mockRulesFactory->method('setData')
            ->willReturn($this->mockRulesFactory);


        $this->autoGenerateApiUser->createApiUser(self::PASSWORD);

        $this->assertEquals(self::USERNAME, $this->autoGenerateApiUser->createApiUser(self::PASSWORD)['username']);
        $this->assertEquals(self::PASSWORD, $this->autoGenerateApiUser->createApiUser(self::PASSWORD)['password']);
    }

    public function testGetApiUser()
    {
        $this->mockUserFactory->method('loadByUsername')
            ->willReturn($this->mockUserFactory);
        $this->mockUserFactory->method('getUsername')
            ->willReturn(self::USERNAME);


        $this->assertEquals(self::USERNAME, $this->autoGenerateApiUser->getApiUser(self::PASSWORD)['username']);
        $this->assertEquals(self::PASSWORD, $this->autoGenerateApiUser->getApiUser(self::PASSWORD)['password']);
    }
}
