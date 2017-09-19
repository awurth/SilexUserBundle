<?php

namespace AWurth\SilexUser\Controller;

use AWurth\SilexUser\Model\UserInterface;
use AWurth\SilexUser\Model\UserManagerInterface;
use Doctrine\ORM\EntityManager;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class Controller
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * Constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->application = $app;
    }

    /**
     * Creates a new form.
     *
     * @param string $type
     * @param mixed $data
     * @param array $options
     *
     * @return FormInterface
     */
    public function createForm($type, $data = null, array $options = [])
    {
        return $this->application['form.factory']->create($type, $data, $options);
    }

    /**
     * Gets the event dispatcher.
     *
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->application['dispatcher'];
    }

    /**
     * Gets Doctrine Entity Manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->application['orm.em'];
    }

    /**
     * Gets the session.
     *
     * @return SessionInterface
     */
    public function getSession()
    {
        return $this->application['session'];
    }

    /**
     * Gets the user manager.
     *
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->application['silex_user.user_manager'];
    }

    /**
     * Gets a user from the Security Token Storage.
     *
     * @return UserInterface|null
     */
    public function getUser()
    {
        /** @var TokenInterface $token */
        $token = $this->application['security.token_storage']->getToken();
        if (null === $token) {
            return null;
        }

        $user = $token->getUser();
        if (!is_object($user)) {
            return null;
        }

        return $user;
    }

    /**
     * Generates a path from the given parameters.
     *
     * @param string      $route      The name of the route
     * @param mixed       $parameters An array of parameters
     *
     * @return string The generated path
     */
    public function path($route, $parameters = [])
    {
        return $this->application['url_generator']->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * Redirects the user to another route.
     *
     * @param string $route The route to redirect to
     * @param array $parameters An array of parameters
     * @param int $status The status code (302 by default)
     *
     * @return RedirectResponse
     */
    public function redirect($route, $parameters = [], $status = 302)
    {
        return $this->application->redirect($this->path($route, $parameters), $status);
    }

    /**
     * Renders a template.
     *
     * @param string $name The template name
     * @param array $context An array of parameters to pass to the template
     *
     * @return string
     */
    public function render($name, array $context = [])
    {
        return $this->application['twig']->render($name, $context);
    }
}
