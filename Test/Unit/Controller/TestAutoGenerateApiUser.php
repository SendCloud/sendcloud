<?php

/**
 * Created by PhpStorm.
 * User: wessel
 * Date: 24-02-18
 * Time: 14:35
 */

namespace CreativeICT\SendCloud\Test\Unit\Controller;

use CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect\AutoGenerateApiUser;

class AutoGenerateApiUserTest extends \PHPUnit\Framework\TestCase
{
    const PASSWORD = 'test1234';

    /** @var \CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect\Index */
    private $connect;

    /** @var \Magento\User\Model\UserFactory*/
    private $mockUserFactory;

    /** @var \Magento\Authorization\Model\RoleFactory */
    private $mockRoleFactory;

    /** @var \Magento\Authorization\Model\RulesFactory */
    private $mockRulesFactory;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->mockUserFactory = $this->getMockBuilder(
            '\Magento\User\Model\UserFactory'
        )
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setData', 'setRoleId', 'save', 'getUserName', 'getId', 'getData'])
            ->getMock();

        $this->mockRoleFactory = $this->getMockBuilder(
            '\Magento\Authorization\Model\RoleFactory'
        )
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setData', 'save', 'getId', 'setResources'])
            ->getMock();

        $this->mockRulesFactory = $this->getMockBuilder(
            '\Magento\Authorization\Model\RulesFactory'
        )
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setData', 'save', 'setRoleId', 'getId', 'setResources', 'saveRel'])
            ->getMock();

        $this->connect = $this->getMockClass(
            '\CreativeICT\SendCloud\Controller\Adminhtml\AutoConnect\Index',
            ['generatePassword'],
            [],
            '',
            false
        );

        //$this->connect->method('generatePassword')->willReturn(self::PASSWORD);
        $this->autoGenerateApiUser = $this->objectManager->getObject(AutoGenerateApiUser::class,
            [
                'userFactory' => $this->mockUserFactory,
                'roleFactory' => $this->mockRoleFactory,
                'rulesFactory' => $this->mockRulesFactory
            ]
        );
        //$this->autoGenerateApiUser = new AutoGenerateApiUser($mockUserFactory, $mockRoleFactory, $mockRulesFactory);

    }

    public function testCreateApiUser()
    {
        $apiUserInfo = [
            'username'  => 'sendcloud',
            'firstname' => 'rob',
            'lastname'    => 'api',
            'email'     => 'sendcloud@api.com',
            'password'  => self::PASSWORD,
            'interface_locale' => 'en_US',
            'is_active' => 1
        ];

        $this->mockUserFactory->method('create')->willReturn($this->mockUserFactory);
        $this->mockUserFactory->expects($this->once())->method('setData')->with($apiUserInfo);
        var_dump($this->mockUserFactory->getUserName());

        $this->mockUserFactory->method('setData')->willReturn($this->mockUserFactory);
        $this->mockRoleFactory->method('create')->willReturn($this->mockRoleFactory);
        $this->mockRulesFactory->method('create')->willReturn($this->mockRulesFactory);
        $this->mockRulesFactory->method('setRoleId')->willReturn($this->mockRulesFactory);
        $this->mockRoleFactory->method('getId')->willReturn(1);
        $this->mockRulesFactory->method('setData')->willReturn($this->mockRulesFactory);

        var_dump($this->autoGenerateApiUser->createApiUser(self::PASSWORD));
        $this->autoGenerateApiUser->createApiUser(self::PASSWORD);
        //$this->assertEquals("Test", $this->autoGenerateApiUser->getApiUser());
    }
}
