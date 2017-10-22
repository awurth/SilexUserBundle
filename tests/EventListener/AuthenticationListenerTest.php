<?php

namespace AWurth\Silex\User\Tests\EventListener;

use AWurth\Silex\User\Event\Events;
use AWurth\Silex\User\Event\FilterUserResponseEvent;
use AWurth\Silex\User\EventListener\AuthenticationListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthenticationListenerTest extends TestCase
{
    const FIREWALL_NAME = 'foo';

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var FilterUserResponseEvent
     */
    protected $event;

    /**
     * @var AuthenticationListener
     */
    protected $listener;

    public function setUp()
    {
        $user = $this->getMockBuilder('AWurth\Silex\User\Model\UserInterface')->getMock();

        $response = $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $this->event = new FilterUserResponseEvent($user, $request, $response);

        $this->eventDispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')->getMock();
        $this->eventDispatcher->expects($this->once())->method('dispatch');

        $loginManager = $this->getMockBuilder('AWurth\Silex\User\Security\LoginManagerInterface')->getMock();

        $this->listener = new AuthenticationListener($loginManager, self::FIREWALL_NAME);
    }

    public function testAuthenticate()
    {
        $this->listener->authenticate($this->event, Events::REGISTRATION_COMPLETED, $this->eventDispatcher);
    }
}
