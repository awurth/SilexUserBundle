<?php

namespace AWurth\SilexUser\Tests;

use AWurth\SilexUser\Provider\SilexUserServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use LogicException;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\WebTestCase;

class SilexUserServiceProviderConfigurationTest extends WebTestCase
{
    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The "user_class" option must be set
     */
    public function testWithoutUserClass()
    {
        $this->app->boot();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The "user_class" option must be set
     */
    public function testWithEmptyUserClass()
    {
        $this->app['silex_user.options'] = [
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
            'user_class' => 'User',
            'firewall_name' => ''
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
    public function testEmailConfirmationWithoutFromEmail()
    {
        $this->app->register(new SwiftmailerServiceProvider());

        $this->app['silex_user.options'] = [
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
        $this->app->register(new SwiftmailerServiceProvider());

        $this->app['silex_user.options'] = [
            'user_class' => 'User',
            'firewall_name' => 'main',
            'registration.confirmation.enabled' => true,
            'registration.confirmation.from_email' => ''
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
        $app->register(new LocaleServiceProvider());
        $app->register(new TranslationServiceProvider());
        $app->register(new ValidatorServiceProvider());
        $app->register(new FormServiceProvider());
        $app->register(new DoctrineServiceProvider());
        $app->register(new DoctrineOrmServiceProvider());
        $app->register(new SecurityServiceProvider());
        $app->register(new SilexUserServiceProvider());

        $app['security.firewalls'] = [
            'main' => [
                'form' => []
            ]
        ];

        return $app;
    }
}
