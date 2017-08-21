<?php

namespace AWurth\SilexUser\Provider;

use AWurth\SilexUser\Controller\AuthController;
use AWurth\SilexUser\Controller\RegistrationController;
use AWurth\SilexUser\Entity\UserManager;
use AWurth\SilexUser\EventListener\AuthenticationListener;
use AWurth\SilexUser\EventListener\EmailConfirmationListener;
use AWurth\SilexUser\EventListener\FlashListener;
use AWurth\SilexUser\Mailer\TwigSwiftMailer;
use AWurth\SilexUser\Security\LoginManager;
use LogicException;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;

/**
 * Silex User Service Provider.
 *
 * @author Alexis Wurth <alexis.wurth57@gmail.com>
 */
class SilexUserServiceProvider implements ServiceProviderInterface, BootableProviderInterface, ControllerProviderInterface, EventListenerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        if (!isset($app['twig'])) {
            throw new LogicException('You must register the TwigServiceProvider to use the SilexUserServiceProvider');
        }

        $app['silex_user.options'] = [];

        // Services
        $app['silex_user.user_manager'] = function ($app) {
            return new UserManager($app);
        };

        $app['silex_user.login_manager'] = function ($app) {
            return new LoginManager(
                $app['security.token_storage'],
                $app['security.user_checker'],
                $app['security.session_strategy'],
                $app['request_stack']
            );
        };

        $app['silex_user.user_provider.username'] = function ($app) {
            return new UserProvider($app['silex_user.user_manager']);
        };

        $app['silex_user.user_provider.username_email'] = function ($app) {
            return new EmailUserProvider($app['silex_user.user_manager']);
        };

        $app['silex_user.mailer'] = function ($app) {
            if (isset($app['mailer']) && get_class($app['mailer']) === 'Swift_Mailer') {
                $parameters = [
                    'from_email' => [
                        'confirmation' => $app['silex_user.options']['registration']['confirmation']['from_email']
                    ]
                ];

                return new TwigSwiftMailer($app['mailer'], $app['twig'], $app['url_generator'], $parameters);
            }

            return null;
        };

        // Controllers
        $app['auth.controller'] = function ($app) {
            return new AuthController($app);
        };

        $app['registration.controller'] = function ($app) {
            return new RegistrationController($app);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        $app['silex_user.options'] = array_replace_recursive([
            'use_templates' => true,
            'use_translations' => true,
            'use_flash_notifications' => true,
            'use_authentication_listener' => false,
            'registration' => [
                'confirmation' => [
                    'enabled' => false,
                    'from_email' => null
                ]
            ]
        ], $app['silex_user.options']);

        if (!isset($app['silex_user.options']['user_class'])) {
            throw new LogicException('The "user_class" option must be set');
        }

        if (!isset($app['silex_user.options']['firewall_name'])) {
            throw new LogicException('The "firewall_name" option must be set');
        }

        if (true === $app['silex_user.options']['registration']['confirmation']['enabled']) {
            if (null === $app['silex_user.mailer']) {
                throw new LogicException('You must configure a mailer to enable email notifications');
            }

            if (empty($app['silex_user.options']['registration']['confirmation']['from_email'])) {
                throw new LogicException('The "registration.confirmation.from_email" option must be set');
            }
        }

        if (true === $app['silex_user.options']['use_templates']) {
            $app['twig.loader.filesystem']->addPath(__DIR__ . '/../Resources/views/');
        }

        if (true === $app['silex_user.options']['use_translations']) {
            /** @var Translator $translator */
            $translator = $app['translator'];

            $translator->addLoader('php', new PhpFileLoader());

            $translator->addResource('php', __DIR__ . '/../Resources/translations/en.php', 'en');
            $translator->addResource('php', __DIR__ . '/../Resources/translations/fr.php', 'fr');
            $translator->addResource('php', __DIR__ . '/../Resources/translations/validators.en.php', 'en', 'validators');
            $translator->addResource('php', __DIR__ . '/../Resources/translations/validators.fr.php', 'fr', 'validators');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/login', 'auth.controller:loginAction')
            ->bind('silex_user.login');

        $controllers->method('GET|POST')
            ->match('/login_check')
            ->bind('silex_user.login_check');

        $controllers->method('GET|POST')
            ->match('/logout')
            ->bind('silex_user.logout');

        $controllers->method('GET|POST')
            ->match('/register', 'registration.controller:registerAction')
            ->bind('silex_user.register');

        $controllers->get('/register/confirmed', 'registration.controller:confirmedAction')
            ->bind('silex_user.registration_confirmed');

        if (true === $app['silex_user.options']['registration']['confirmation']['enabled']) {
            $controllers->get('/register/check-email', 'registration.controller:checkEmailAction')
                ->bind('silex_user.registration_check_email');

            $controllers->get('/register/confirm/{token}', 'registration.controller:confirmAction')
                ->bind('silex_user.registration_confirm');
        }

        return $controllers;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        if (true === $app['silex_user.options']['use_authentication_listener']) {
            $dispatcher->addSubscriber(new AuthenticationListener($app['silex_user.login_manager'], $app['silex_user.firewall_name']));
        }

        if (true === $app['silex_user.options']['use_flash_notifications']) {
            $dispatcher->addSubscriber(new FlashListener($app['session'], $app['translator']));
        }

        if (true === $app['silex_user.options']['registration']['confirmation']['enabled']) {
            $dispatcher->addSubscriber(new EmailConfirmationListener($app['silex_user.mailer'], $app['url_generator'], $app['session']));
        }
    }
}
