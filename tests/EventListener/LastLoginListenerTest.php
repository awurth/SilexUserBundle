<?php

namespace EventListener;

use AWurth\SilexUser\Event\UserEvent;
use AWurth\SilexUser\EventListener\LastLoginListener;
use AWurth\SilexUser\Model\UserManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LastLoginListenerTest extends TestCase
{
    /**
     * @var LastLoginListener
     */
    protected $listener;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    public function setUp()
    {
        $this->userManager = $this->getMockBuilder('AWurth\SilexUser\Model\UserManagerInterface')->getMock();

        $this->listener = new LastLoginListener($this->userManager);
    }

    public function testOnImplicitLogin()
    {
        $user = $this->getMockBuilder('AWurth\SilexUser\Model\UserInterface')->getMock();
        $event = new UserEvent($user);

        $user->expects($this->once())->method('setLastLogin');
        $this->userManager->expects($this->once())->method('updateUser');

        $this->listener->onImplicitLogin($event);
    }

    public function testOnSecurityInteractiveLogin()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $user = $this->getMockBuilder('AWurth\SilexUser\Model\UserInterface')->getMock();

        $token = new UsernamePasswordToken($user, null, 'foo');

        $event = new InteractiveLoginEvent($request, $token);

        $user->expects($this->once())->method('setLastLogin');
        $this->userManager->expects($this->once())->method('updateUser');

        $this->listener->onSecurityInteractiveLogin($event);
    }
}
