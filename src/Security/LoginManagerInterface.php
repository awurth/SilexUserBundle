<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Silex\User\Security;

use AWurth\Silex\User\Model\UserInterface;
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
