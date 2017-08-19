<?php

namespace AWurth\SilexUser\EventListener;

use AWurth\SilexUser\Event\Events;
use InvalidArgumentException;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class FlashListener implements EventSubscriberInterface
{
    /**
     * @var array
     */
    protected static $successMessages = [
        Events::REGISTRATION_COMPLETED => 'registration.flash.user_created'
    ];

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Constructor.
     *
     * @param Session             $session
     * @param TranslatorInterface $translator
     */
    public function __construct(Session $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::REGISTRATION_COMPLETED => 'addSuccessFlash'
        ];
    }

    /**
     * Adds a success flash message.
     *
     * @param Event  $event
     * @param string $eventName
     */
    public function addSuccessFlash(Event $event, $eventName)
    {
        if (!isset(self::$successMessages[$eventName])) {
            throw new InvalidArgumentException('This event does not correspond to a known flash message');
        }

        $this->session->getFlashBag()->add('success', $this->translator->trans(self::$successMessages[$eventName]));
    }
}
