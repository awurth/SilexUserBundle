<?php

namespace AWurth\SilexUser\Controller;

use Doctrine\ORM\EntityManager;
use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Controller
{
    /**
     * @var Application
     */
    protected $application;

    public function __construct(Application $app)
    {
        $this->application = $app;
    }

    /**
     * Get Doctrine Entity Manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->application['orm.em'];
    }

    /**
     * Get current authenticated user.
     *
     * @return UserInterface|null
     */
    public function getUser()
    {
        $token = $this->application['security.token_storage']->getToken();

        return null !== $token ? $token->getUser() : null;
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
     * Redirect the user to another route.
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
     * Render a template.
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
