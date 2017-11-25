<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Silex\User\EventListener;

use AWurth\Silex\User\Event\Events;
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

        $this->session->getFlashBag()->add('success', $this->translator->trans(self::$successMessages[$eventName], [], 'silex_user'));
    }
}
