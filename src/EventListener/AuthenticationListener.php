<?php

namespace AWurth\SilexUser\EventListener;

use AWurth\SilexUser\Event\Events;
use AWurth\SilexUser\Event\FilterUserResponseEvent;
use AWurth\SilexUser\Security\LoginManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var LoginManager
     */
    protected $loginManager;

    /**
     * @var string
     */
    protected $firewallName;

    /**
     * Constructor.
     *
     * @param LoginManager $loginManager
     * @param string       $firewallName
     */
    public function __construct(LoginManager $loginManager, $firewallName)
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
     * @param FilterUserResponseEvent $event
     */
    public function authenticate(FilterUserResponseEvent $event)
    {
        try {
            $this->loginManager->logInUser($this->firewallName, $event->getUser(), $event->getResponse());
        } catch (AccountStatusException $e) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}
