<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\SilexUser\EventListener;

use AWurth\SilexUser\Event\Events;
use AWurth\SilexUser\Event\FilterUserResponseEvent;
use AWurth\SilexUser\Event\UserEvent;
use AWurth\SilexUser\Security\LoginManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var LoginManagerInterface
     */
    protected $loginManager;

    /**
     * @var string
     */
    protected $firewallName;

    /**
     * Constructor.
     *
     * @param LoginManagerInterface $loginManager
     * @param string                $firewallName
     */
    public function __construct(LoginManagerInterface $loginManager, $firewallName)
    {
        $this->loginManager = $loginManager;
        $this->firewallName = $firewallName;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::REGISTRATION_COMPLETED => 'authenticate',
            Events::REGISTRATION_CONFIRMED => 'authenticate'
        ];
    }

    /**
     * Authenticates the user.
     *
     * @param FilterUserResponseEvent  $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function authenticate(FilterUserResponseEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        try {
            $this->loginManager->logInUser($this->firewallName, $event->getUser(), $event->getResponse());

            $dispatcher->dispatch(Events::SECURITY_IMPLICIT_LOGIN, new UserEvent($event->getUser(), $event->getRequest()));
        } catch (AccountStatusException $e) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}
