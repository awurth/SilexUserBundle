<?php

namespace AWurth\Silex\User\Tests\Provider;

use AWurth\Silex\User\Provider\UserServiceProvider;
use LogicException;
use Silex\Application;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\WebTestCase;

class UserServiceProviderDependenciesTest extends WebTestCase
{
    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the ServiceControllerServiceProvider to use the UserServiceProvider
     */
    public function testRegisterWithoutServiceController()
    {
        $this->app->register(new UserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the TwigServiceProvider to use the UserServiceProvider
     */
    public function testRegisterWithoutTwig()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new UserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the SessionServiceProvider to use the UserServiceProvider
     */
    public function testRegisterWithoutSession()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new UserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the TranslationServiceProvider to use the UserServiceProvider
     */
    public function testRegisterWithoutTranslation()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new SessionServiceProvider());
        $this->app->register(new UserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the ValidatorServiceProvider to use the UserServiceProvider
     */
    public function testRegisterWithoutValidator()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new SessionServiceProvider());
        $this->app->register(new LocaleServiceProvider());
        $this->app->register(new TranslationServiceProvider());
        $this->app->register(new UserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the FormServiceProvider to use the UserServiceProvider
     */
    public function testRegisterWithoutForm()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new SessionServiceProvider());
        $this->app->register(new LocaleServiceProvider());
        $this->app->register(new TranslationServiceProvider());
        $this->app->register(new ValidatorServiceProvider());
        $this->app->register(new UserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the SecurityServiceProvider to use the UserServiceProvider
     */
    public function testRegisterWithoutSecurity()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new SessionServiceProvider());
        $this->app->register(new LocaleServiceProvider());
        $this->app->register(new TranslationServiceProvider());
        $this->app->register(new ValidatorServiceProvider());
        $this->app->register(new FormServiceProvider());

        $this->app->register(new UserServiceProvider());
    }

    public function createApplication()
    {
        $app = new Application([
            'debug' => true
        ]);

        return $app;
    }
}
