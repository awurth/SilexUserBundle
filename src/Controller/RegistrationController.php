<?php

namespace AWurth\SilexUser\Controller;

use AWurth\SilexUser\Entity\User;
use AWurth\SilexUser\Entity\UserManager;
use AWurth\SilexUser\Event\FilterUserResponseEvent;
use AWurth\SilexUser\Event\FormEvent;
use AWurth\SilexUser\Event\GetResponseUserEvent;
use AWurth\SilexUser\Event\Events;
use AWurth\SilexUser\Form\RegistrationFormType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Silex User Registration Controller.
 *
 * @author Alexis Wurth <alexis.wurth57@gmail.com>
 */
class RegistrationController extends Controller
{
    public function registerAction(Request $request)
    {
        /** @var UserManager $userManager */
        $userManager = $this->application['silex_user.user_manager'];

        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->application['dispatcher'];

        /** @var User $user */
        $user = new $this->application['silex_user.user_class']();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(Events::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var FormInterface $form */
        $form = $this->application['form.factory']->create(RegistrationFormType::class, $user);

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

    public function confirmedAction()
    {
        return $this->render('silex_user/registration/confirmed.twig');
    }
}
