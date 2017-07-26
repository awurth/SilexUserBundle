<?php

namespace AWurth\SilexUser\Controller;

use AWurth\SilexUser\Entity\User;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Silex User Authentication Controller.
 *
 * @author Alexis Wurth <alexis.wurth57@gmail.com>
 */
class AuthController
{
    public function loginAction(Application $app, Request $request)
    {
        return $app['twig']->render('@silex_user/auth/login.twig', [
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username')
        ]);
    }

    public function registerAction(Application $app, Request $request)
    {
        $user = new User();

        /** @var FormInterface $form */
        $form = $app['form.factory']->createBuilder(FormType::class, $user)
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_name' => 'password',
                'second_name' => 'confirm_password',
                'invalid_message' => 'The password fields must match.'
            ])
            ->getForm();

        if ($form->handleRequest($request)->isValid()) {
            /** @var PasswordEncoderInterface $encoder */
            $encoder = $app['security.encoder_factory']->getEncoder($user);

            $user->setSalt('');
            $user->setPassword($encoder->encodePassword($user->getPassword(), $user->getSalt()));

            $em = $app['orm.em'];

            $em->persist($user);
            $em->flush();

            $app['session']->getFlashBag()->set('success', 'Account created.');

            return $app->redirect($this->path($app, 'login'));
        }

        return $app['twig']->render('@silex_user/auth/register.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Generates a path from the given parameters.
     *
     * @param Application $app        The name of the route
     * @param string      $route      The name of the route
     * @param mixed       $parameters An array of parameters
     *
     * @return string The generated path
     */
    public function path(Application $app, $route, $parameters = [])
    {
        return $app['url_generator']->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}