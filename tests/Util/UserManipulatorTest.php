<?php

namespace AWurth\Silex\User\Tests\Util;

use AWurth\Silex\User\Event\Events;
use AWurth\Silex\User\Tests\Model\TestUser;
use AWurth\Silex\User\Util\UserManipulator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class UserManipulatorTest extends TestCase
{
    public function testCreate()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();
        $user = new TestUser();

        $username = 'test_username';
        $password = 'test_password';
        $email = 'test@email.org';
        $active = true;
        $superadmin = false;

        $userManagerMock->expects($this->once())
            ->method('createUser')
            ->will($this->returnValue($user));

        $userManagerMock->expects($this->once())
            ->method('updateUser')
            ->will($this->returnValue($user))
            ->with($this->isInstanceOf(TestUser::class));

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_CREATED, true);

        $requestStackMock = $this->getRequestStackMock(true);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->create($username, $password, $email, $active, $superadmin);

        $this->assertSame($username, $user->getUsername());
        $this->assertSame($password, $user->getPlainPassword());
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($active, $user->isEnabled());
        $this->assertSame($superadmin, $user->isSuperAdmin());
    }

    public function testActivateWithValidUsername()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();
        $username = 'test_username';

        $user = new TestUser();
        $user->setUsername($username);
        $user->setEnabled(false);

        $userManagerMock->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue($user))
            ->with($this->equalTo($username));

        $userManagerMock->expects($this->once())
            ->method('updateUser')
            ->will($this->returnValue($user))
            ->with($this->isInstanceOf(TestUser::class));

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_ACTIVATED, true);

        $requestStackMock = $this->getRequestStackMock(true);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->activate($username);

        $this->assertSame($username, $user->getUsername());
        $this->assertSame(true, $user->isEnabled());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testActivateWithInvalidUsername()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();
        $invalidusername = 'invalid_username';

        $userManagerMock->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue(null))
            ->with($this->equalTo($invalidusername));

        $userManagerMock->expects($this->never())
            ->method('updateUser');

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_ACTIVATED, false);

        $requestStackMock = $this->getRequestStackMock(false);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->activate($invalidusername);
    }

    public function testDeactivateWithValidUsername()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();
        $username = 'test_username';

        $user = new TestUser();
        $user->setUsername($username);
        $user->setEnabled(true);

        $userManagerMock->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue($user))
            ->with($this->equalTo($username));

        $userManagerMock->expects($this->once())
            ->method('updateUser')
            ->will($this->returnValue($user))
            ->with($this->isInstanceOf(TestUser::class));

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_DEACTIVATED, true);

        $requestStackMock = $this->getRequestStackMock(true);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->deactivate($username);

        $this->assertSame($username, $user->getUsername());
        $this->assertSame(false, $user->isEnabled());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDeactivateWithInvalidUsername()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();
        $invalidusername = 'invalid_username';

        $userManagerMock->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue(null))
            ->with($this->equalTo($invalidusername));

        $userManagerMock->expects($this->never())
            ->method('updateUser');

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_DEACTIVATED, false);

        $requestStackMock = $this->getRequestStackMock(false);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->deactivate($invalidusername);
    }

    public function testPromoteWithValidUsername()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();
        $username = 'test_username';

        $user = new TestUser();
        $user->setUsername($username);
        $user->setSuperAdmin(false);

        $userManagerMock->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue($user))
            ->with($this->equalTo($username));

        $userManagerMock->expects($this->once())
            ->method('updateUser')
            ->will($this->returnValue($user))
            ->with($this->isInstanceOf(TestUser::class));

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_PROMOTED, true);

        $requestStackMock = $this->getRequestStackMock(true);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->promote($username);

        $this->assertSame($username, $user->getUsername());
        $this->assertSame(true, $user->isSuperAdmin());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPromoteWithInvalidUsername()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();
        $invalidusername = 'invalid_username';

        $userManagerMock->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue(null))
            ->with($this->equalTo($invalidusername));

        $userManagerMock->expects($this->never())
            ->method('updateUser');

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_PROMOTED, false);

        $requestStackMock = $this->getRequestStackMock(false);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->promote($invalidusername);
    }

    public function testDemoteWithValidUsername()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();
        $username = 'test_username';

        $user = new TestUser();
        $user->setUsername($username);
        $user->setSuperAdmin(true);

        $userManagerMock->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue($user))
            ->with($this->equalTo($username));

        $userManagerMock->expects($this->once())
            ->method('updateUser')
            ->will($this->returnValue($user))
            ->with($this->isInstanceOf(TestUser::class));

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_DEMOTED, true);

        $requestStackMock = $this->getRequestStackMock(true);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->demote($username);

        $this->assertSame($username, $user->getUsername());
        $this->assertSame(false, $user->isSuperAdmin());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDemoteWithInvalidUsername()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();
        $invalidusername = 'invalid_username';

        $userManagerMock->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue(null))
            ->with($this->equalTo($invalidusername));

        $userManagerMock->expects($this->never())
            ->method('updateUser');

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_DEMOTED, false);

        $requestStackMock = $this->getRequestStackMock(false);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->demote($invalidusername);
    }

    public function testChangePasswordWithValidUsername()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();

        $user = new TestUser();
        $username = 'test_username';
        $password = 'test_password';
        $oldpassword = 'old_password';

        $user->setUsername($username);
        $user->setPlainPassword($oldpassword);

        $userManagerMock->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue($user))
            ->with($this->equalTo($username));

        $userManagerMock->expects($this->once())
            ->method('updateUser')
            ->will($this->returnValue($user))
            ->with($this->isInstanceOf(TestUser::class));

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_PASSWORD_CHANGED, true);

        $requestStackMock = $this->getRequestStackMock(true);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->changePassword($username, $password);

        $this->assertSame($username, $user->getUsername());
        $this->assertSame($password, $user->getPlainPassword());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testChangePasswordWithInvalidUsername()
    {
        $userManagerMock = $this->getMockBuilder('AWurth\Silex\User\Model\UserManagerInterface')->getMock();

        $invalidusername = 'invalid_username';
        $password = 'test_password';

        $userManagerMock->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue(null))
            ->with($this->equalTo($invalidusername));

        $userManagerMock->expects($this->never())
            ->method('updateUser');

        $eventDispatcherMock = $this->getEventDispatcherMock(Events::USER_PASSWORD_CHANGED, false);

        $requestStackMock = $this->getRequestStackMock(false);

        $manipulator = new UserManipulator($userManagerMock, $eventDispatcherMock, $requestStackMock);
        $manipulator->changePassword($invalidusername, $password);
    }

    /**
     * @param string $event
     * @param bool   $once
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEventDispatcherMock($event, $once = true)
    {
        $eventDispatcherMock = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();

        $eventDispatcherMock->expects($once ? $this->once() : $this->never())
            ->method('dispatch')
            ->with($event);

        return $eventDispatcherMock;
    }

    /**
     * @param bool $once
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRequestStackMock($once = true)
    {
        $requestStackMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')->getMock();

        $requestStackMock->expects($once ? $this->once() : $this->never())
            ->method('getCurrentRequest')
            ->willReturn(null);

        return $requestStackMock;
    }
}
