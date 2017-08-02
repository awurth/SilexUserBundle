<?php

namespace AWurth\SilexUser\Provider;

use AWurth\SilexUser\Controller\AuthController;
use AWurth\SilexUser\Controller\RegistrationController;
use AWurth\SilexUser\Entity\User;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;

/**
 * Silex User Service Provider.
 *
 * @author Alexis Wurth <alexis.wurth57@gmail.com>
 */
class SilexUserServiceProvider implements ServiceProviderInterface, BootableProviderInterface, ControllerProviderInterface
{
    /**
     * Register SilexUser service.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     */
    public function register(Container $app)
    {
        $app['silex_user.user_class'] = User::class;
        $app['silex_user.use_templates'] = true;
        $app['silex_user.use_translations'] = true;

        $app['auth.controller'] = function ($app) {
            return new AuthController($app);
        };

        $app['registration.controller'] = function ($app) {
            return new RegistrationController($app);
        };
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        if (true === $app['silex_user.use_templates']) {
            $app['twig.loader.filesystem']->addPath(__DIR__ . '/../Resources/views/');
        }

        if (true === $app['silex_user.use_translations']) {
            /** @var Translator $translator */
            $translator = $app['translator'];

            $translator->addLoader('php', new PhpFileLoader());

            $translator->addResource('php', __DIR__ . '/../Resources/translations/en.php', 'en');
            $translator->addResource('php', __DIR__ . '/../Resources/translations/fr.php', 'fr');
        }
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/login', 'auth.controller:loginAction')
            ->bind('silex_user.login');

        $controllers->method('GET|POST')
            ->match('/register', 'registration.controller:registerAction')
            ->bind('silex_user.register');

        $controllers->get('/register/confirmed', 'registration.controller:confirmedAction')
            ->bind('silex_user.registration_confirmed');

        return $controllers;
    }
}
