<?php

namespace AWurth\SilexUser\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Silex User Authentication Controller.
 *
 * @author Alexis Wurth <alexis.wurth57@gmail.com>
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
