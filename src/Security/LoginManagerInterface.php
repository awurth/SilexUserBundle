<?php

namespace AWurth\SilexUser\Security;

use AWurth\SilexUser\Model\UserInterface;
use Symfony\Component\HttpFoundation\Response;

interface LoginManagerInterface
{
    /**
     * @param string        $firewallName
     * @param UserInterface $user
     * @param Response|null $response
     */
    public function logInUser($firewallName, UserInterface $user, Response $response = null);
}
