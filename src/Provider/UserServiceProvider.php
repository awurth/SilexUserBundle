<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Silex\User\Provider;

use AWurth\Silex\User\Controller\AuthController;
use AWurth\Silex\User\Controller\RegistrationController;
use AWurth\Silex\User\EventListener\LastLoginListener;
use AWurth\Silex\User\Model\UserManager;
use AWurth\Silex\User\EventListener\AuthenticationListener;
use AWurth\Silex\User\EventListener\EmailConfirmationListener;
use AWurth\Silex\User\EventListener\FlashListener;
use AWurth\Silex\User\Mailer\TwigSwiftMailer;
use AWurth\Silex\User\Security\LoginManager;
use AWurth\Silex\User\Util\UserManipulator;
use LogicException;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ServiceControllerResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;

/**
 * User Service Provider.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 */
class UserServiceProvider implements ServiceProviderInterface, BootableProviderInterface, ControllerProviderInterface, EventListenerProviderInterface
{
    /**
     * @var array
     */
    protected static $defaultOptions = [
        'use_routes' => true,
        'use_templates' => true,
        'use_translations' => true,
        'use_flash_notifications' => true,
        'use_last_login_listener' => true,
        'use_authentication_listener' => false,
        'registration.confirmation.enabled' => false,
        'registration.confirmation.from_email' => ''
    ];

    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $this->checkDependencies($app);

        $app['silex_user.options'] = [];

        // Services
        $app['silex_user.user_manager'] = function ($app) {
            $this->checkOptions($app);

            return new UserManager(
                $app[$app['silex_user.options']['object_manager']],
                $app['security.encoder_factory'],
                $app['silex_user.options']['user_class']
            );
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
            if (isset($app['mailer']) && 'Swift_Mailer' === get_class($app['mailer'])) {
                $parameters = [
                    'from_email' => [
                        'confirmation' => $this->getOption($app, 'registration.confirmation.from_email')
                    ]
                ];

                return new TwigSwiftMailer($app['mailer'], $app['twig'], $app['url_generator'], $parameters);
            }

            return null;
        };

        $app['silex_user.util.user_manipulator'] = function ($app) {
            return new UserManipulator($app['silex_user.user_manager'], $app['dispatcher'], $app['request_stack']);
        };

        // Controllers
        $app['silex_user.auth.controller'] = function ($app) {
            return new AuthController($app);
        };

        $app['silex_user.registration.controller'] = function ($app) {
            return new RegistrationController($app);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        if (true === $this->getOption($app, 'use_templates')) {
            $app['twig.loader.filesystem']->addPath(__DIR__.'/../../templates');
        }

        if (true === $this->getOption($app, 'use_translations')) {
            /** @var Translator $translator */
            $translator = $app['translator'];
            $translationsDir = __DIR__.'/../../translations';

            $translator->addLoader('php', new PhpFileLoader());

            $translator->addResource('php', $translationsDir.'/silex_user.en.php', 'en', 'silex_user');
            $translator->addResource('php', $translationsDir.'/silex_user.fr.php', 'fr', 'silex_user');
            $translator->addResource('php', $translationsDir.'/validators.en.php', 'en', 'validators');
            $translator->addResource('php', $translationsDir.'/validators.fr.php', 'fr', 'validators');
        }

        if (true === $this->getOption($app, 'use_routes')) {
            $app->mount('/', $this->connect($app));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/login', 'silex_user.auth.controller:loginAction')
            ->bind('silex_user.login');

        $controllers->method('GET|POST')
            ->match('/login_check')
            ->bind('silex_user.login_check');

        $controllers->method('GET|POST')
            ->match('/logout')
            ->bind('silex_user.logout');

        $controllers->method('GET|POST')
            ->match('/register', 'silex_user.registration.controller:registerAction')
            ->bind('silex_user.register');

        $controllers->get('/register/confirmed', 'silex_user.registration.controller:confirmedAction')
            ->bind('silex_user.registration_confirmed');

        if (true === $this->getOption($app, 'registration.confirmation.enabled')) {
            $controllers->get('/register/check-email', 'silex_user.registration.controller:checkEmailAction')
                ->bind('silex_user.registration_check_email');

            $controllers->get('/register/confirm/{token}', 'silex_user.registration.controller:confirmAction')
                ->bind('silex_user.registration_confirm');
        }

        return $controllers;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $app['silex_user.options'] = array_replace(self::$defaultOptions, $app['silex_user.options']);

        $this->checkOptions($app);

        if (true === $this->getOption($app, 'use_last_login_listener')) {
            $dispatcher->addSubscriber(new LastLoginListener($app['silex_user.user_manager']));
        }

        if (true === $this->getOption($app, 'use_authentication_listener')) {
            $dispatcher->addSubscriber(new AuthenticationListener($app['silex_user.login_manager'], $app['silex_user.options']['firewall_name']));
        }

        if (true === $this->getOption($app, 'use_flash_notifications')) {
            $dispatcher->addSubscriber(new FlashListener($app['session'], $app['translator']));
        }

        if (true === $this->getOption($app, 'registration.confirmation.enabled')) {
            if (null === $app['silex_user.mailer']) {
                throw new LogicException('You must configure a mailer to enable email notifications');
            }

            $dispatcher->addSubscriber(new EmailConfirmationListener($app['silex_user.mailer'], $app['url_generator'], $app['session']));
        }
    }

    /**
     * Checks if all required Service Providers are enabled.
     *
     * @param Container $app
     */
    protected function checkDependencies(Container $app)
    {
        $dependencies = [
            'twig' => 'TwigServiceProvider',
            'session' => 'SessionServiceProvider',
            'translator' => 'TranslationServiceProvider',
            'validator' => 'ValidatorServiceProvider',
            'form.factory' => 'FormServiceProvider',
            'security.token_storage' => 'SecurityServiceProvider'
        ];

        if (!$app['resolver'] instanceof ServiceControllerResolver) {
            throw new LogicException('You must register the ServiceControllerServiceProvider to use the UserServiceProvider');
        }

        foreach ($dependencies as $key => $provider) {
            if (!isset($app[$key])) {
                throw new LogicException(sprintf('You must register the %s to use the UserServiceProvider', $provider));
            }
        }
    }

    /**
     * Checks if options are set correctly.
     *
     * @param Container $app
     */
    protected function checkOptions(Container $app)
    {
        $requiredOptions = ['object_manager', 'user_class', 'firewall_name'];

        foreach ($requiredOptions as $option) {
            if (empty($app['silex_user.options'][$option])) {
                throw new LogicException(sprintf('The "%s" option must be set', $option));
            }
        }

        if (true === $this->getOption($app, 'registration.confirmation.enabled') && empty($this->getOption($app, 'registration.confirmation.from_email'))) {
            throw new LogicException('The "registration.confirmation.from_email" option must be set');
        }
    }

    /**
     * Gets an option or its default value if it is not set.
     *
     * @param Container $app
     * @param string $name
     *
     * @return mixed
     */
    protected function getOption(Container $app, $name)
    {
        if (isset($app['silex_user.options'][$name])) {
            return $app['silex_user.options'][$name];
        } else {
            return self::$defaultOptions[$name];
        }
    }
}
