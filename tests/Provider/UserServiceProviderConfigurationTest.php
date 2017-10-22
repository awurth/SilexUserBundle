<?php

namespace AWurth\Silex\User\Tests\Provider;

use AWurth\Silex\User\Provider\UserServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use LogicException;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\WebTestCase;

class UserServiceProviderConfigurationTest extends WebTestCase
{
    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The "object_manager" option must be set
     */
    public function testWithoutObjectManager()
    {
        $this->app->boot();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The "object_manager" option must be set
     */
    public function testWithEmptyObjectManager()
    {
        $this->app['silex_user.options'] = [
            'object_manager' => ''
        ];

        $this->app->boot();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The "user_class" option must be set
     */
    public function testWithoutUserClass()
    {
        $this->app['silex_user.options'] = [
            'object_manager' => 'orm.em'
        ];

        $this->app->boot();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The "user_class" option must be set
     */
    public function testWithEmptyUserClass()
    {
        $this->app['silex_user.options'] = [
            'object_manager' => 'orm.em',
            'user_class' => ''
        ];

        $this->app->boot();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The "firewall_name" option must be set
     */
    public function testWithoutFirewallName()
    {
        $this->app['silex_user.options'] = [
            'object_manager' => 'orm.em',
            'user_class' => 'User'
        ];

        $this->app->boot();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The "firewall_name" option must be set
     */
    public function testWithEmptyFirewallName()
    {
        $this->app['silex_user.options'] = [
            'object_manager' => 'orm.em',
            'user_class' => 'User',
            'firewall_name' => ''
        ];

        $this->app->boot();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The "registration.confirmation.from_email" option must be set
     */
    public function testEmailConfirmationWithoutFromEmail()
    {
        $this->app['silex_user.options'] = [
            'object_manager' => 'orm.em',
            'user_class' => 'User',
            'firewall_name' => 'main',
            'registration.confirmation.enabled' => true
        ];

        $this->app->boot();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The "registration.confirmation.from_email" option must be set
     */
    public function testEmailConfirmationWithEmptyFromEmail()
    {
        $this->app['silex_user.options'] = [
            'object_manager' => 'orm.em',
            'user_class' => 'User',
            'firewall_name' => 'main',
            'registration.confirmation.enabled' => true,
            'registration.confirmation.from_email' => ''
        ];

        $this->app->boot();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must configure a mailer to enable email notifications
     */
    public function testEmailConfirmationWithoutMailer()
    {
        $this->app['silex_user.options'] = [
            'object_manager' => 'orm.em',
            'user_class' => 'User',
            'firewall_name' => 'main',
            'registration.confirmation.enabled' => true,
            'registration.confirmation.from_email' => 'awurth.dev@gmail.com'
        ];

        $this->app->boot();
    }

    public function createApplication()
    {
        $app = new Application([
            'debug' => true
        ]);

        $app->register(new ServiceControllerServiceProvider());
        $app->register(new TwigServiceProvider());
        $app->register(new SessionServiceProvider());
        $app->register(new DoctrineServiceProvider());
        $app->register(new DoctrineOrmServiceProvider());
        $app->register(new LocaleServiceProvider());
        $app->register(new TranslationServiceProvider());
        $app->register(new ValidatorServiceProvider());
        $app->register(new FormServiceProvider());
        $app->register(new SecurityServiceProvider());
        $app->register(new UserServiceProvider());

        $app['security.firewalls'] = [
            'main' => [
                'form' => []
            ]
        ];

        return $app;
    }
}
