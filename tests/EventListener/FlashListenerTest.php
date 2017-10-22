<?php

namespace AWurth\Silex\User\Tests\EventListener;

use AWurth\Silex\User\Event\Events;
use AWurth\Silex\User\EventListener\FlashListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

class FlashListenerTest extends TestCase
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var FlashListener
     */
    protected $listener;

    public function setUp()
    {
        $this->event = new Event();

        $flashBag = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Flash\FlashBag')->getMock();

        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')->getMock();
        $session->expects($this->once())->method('getFlashBag')->willReturn($flashBag);

        $translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')->getMock();

        $this->listener = new FlashListener($session, $translator);
    }

    public function testAddSuccessFlash()
    {
        $this->listener->addSuccessFlash($this->event, Events::REGISTRATION_COMPLETED);
    }
}
