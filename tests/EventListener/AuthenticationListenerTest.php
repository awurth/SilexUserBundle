<?php

namespace EventListener;

use AWurth\SilexUser\Event\Events;
use AWurth\SilexUser\Event\FilterUserResponseEvent;
use AWurth\SilexUser\EventListener\AuthenticationListener;
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
        $user = $this->getMockBuilder('AWurth\SilexUser\Model\UserInterface')->getMock();

        $response = $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $this->event = new FilterUserResponseEvent($user, $request, $response);

        $this->eventDispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')->getMock();
        $this->eventDispatcher->expects($this->once())->method('dispatch');

        $loginManager = $this->getMockBuilder('AWurth\SilexUser\Security\LoginManagerInterface')->getMock();

        $this->listener = new AuthenticationListener($loginManager, self::FIREWALL_NAME);
    }

    public function testAuthenticate()
    {
        $this->listener->authenticate($this->event, Events::REGISTRATION_COMPLETED, $this->eventDispatcher);
    }
}
