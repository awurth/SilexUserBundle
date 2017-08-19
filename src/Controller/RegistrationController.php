<?php

namespace AWurth\SilexUser\Controller;

use AWurth\SilexUser\Entity\UserInterface;
use AWurth\SilexUser\Event\FilterUserResponseEvent;
use AWurth\SilexUser\Event\FormEvent;
use AWurth\SilexUser\Event\GetResponseUserEvent;
use AWurth\SilexUser\Event\Events;
use AWurth\SilexUser\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Silex User Registration Controller.
 *
 * @author Alexis Wurth <alexis.wurth57@gmail.com>
 */
class RegistrationController extends Controller
{
    public function registerAction(Request $request)
    {
        $userManager = $this->getUserManager();
        $dispatcher = $this->getDispatcher();

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(Events::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(Events::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                $response = $event->getResponse();
                if (null === $response) {
                    $response = $this->redirect('silex_user.registration_confirmed');
                }

                $dispatcher->dispatch(Events::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(Events::REGISTRATION_FAILURE, $event);

            $response = $event->getResponse();
            if (null !== $response) {
                return $response;
            }
        }

        return $this->render('silex_user/registration/register.twig', [
            'form' => $form->createView()
        ]);
    }

    public function checkEmailAction()
    {
        $session = $this->getSession();
        $email = $session->get('silex_user_confirmation_email');

        if (empty($email)) {
            return $this->redirect('silex_user.register');
        }

        $session->remove('silex_user_confirmation_email');
        $user = $this->getUserManager()->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }
        
        return $this->render('silex_user/registration/check_email.twig', [
            'user' => $user
        ]);
    }

    public function confirmAction(Request $request, $token)
    {
        $userManager = $this->getUserManager();

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $dispatcher = $this->getDispatcher();

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(Events::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        $response = $event->getResponse();
        if (null === $response) {
            $response = $this->redirect('silex_user.registration_confirmed');
        }
        
        $dispatcher->dispatch(Events::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    public function confirmedAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('silex_user/registration/confirmed.twig');
    }
}
