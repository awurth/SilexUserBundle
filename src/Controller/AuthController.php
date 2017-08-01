<?php

namespace AWurth\SilexUser\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Silex User Authentication Controller.
 *
 * @author Alexis Wurth <alexis.wurth57@gmail.com>
 */
class AuthController extends Controller
{
    public function loginAction(Request $request)
    {
        return $this->render('silex_user/auth/login.twig', [
            'error'         => $this->application['security.last_error']($request),
            'last_username' => $this->application['session']->get('_security.last_username')
        ]);
    }
}
