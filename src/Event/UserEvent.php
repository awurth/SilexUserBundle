<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Silex\User\Event;

use AWurth\Silex\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class UserEvent extends Event
{
    /**
     * @var Request|null
     */
    private $request;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * Constructor.
     *
     * @param UserInterface $user
     * @param Request|null  $request
     */
    public function __construct(UserInterface $user, Request $request = null)
    {
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Request|null
     */
    public function getRequest()
    {
        return $this->request;
    }
}
