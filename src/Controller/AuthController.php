<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Silex\User\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * User Authentication Controller.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 */
class AuthController extends Controller
{
    public function loginAction(Request $request)
    {
        $csrfToken = null;
        if (isset($this->application['csrf.token_manager']) && $this->application['csrf.token_manager'] instanceof CsrfTokenManagerInterface) {
            $csrfToken = $this->application['csrf.token_manager']->getToken('authenticate');
        }

        return $this->render('silex_user/auth/login.twig', [
            'error'         => $this->application['security.last_error']($request),
            'last_username' => $this->application['session']->get(Security::LAST_USERNAME),
            'csrf_token' => $csrfToken
        ]);
    }
}
