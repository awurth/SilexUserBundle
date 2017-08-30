<?php

namespace AWurth\SilexUser\Tests;

use AWurth\SilexUser\Provider\SilexUserServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use LogicException;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\WebTestCase;

class SilexUserServiceProviderDependenciesTest extends WebTestCase
{
    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the ServiceControllerServiceProvider to use the SilexUserServiceProvider
     */
    public function testRegisterWithoutServiceController()
    {
        $this->app->register(new SilexUserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the TwigServiceProvider to use the SilexUserServiceProvider
     */
    public function testRegisterWithoutTwig()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new SilexUserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the SessionServiceProvider to use the SilexUserServiceProvider
     */
    public function testRegisterWithoutSession()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new SilexUserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the TranslationServiceProvider to use the SilexUserServiceProvider
     */
    public function testRegisterWitoutTranslation()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new SessionServiceProvider());
        $this->app->register(new SilexUserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the ValidatorServiceProvider to use the SilexUserServiceProvider
     */
    public function testRegisterWitoutValidator()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new SessionServiceProvider());
        $this->app->register(new LocaleServiceProvider());
        $this->app->register(new TranslationServiceProvider());
        $this->app->register(new SilexUserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the FormServiceProvider to use the SilexUserServiceProvider
     */
    public function testRegisterWitoutForm()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new SessionServiceProvider());
        $this->app->register(new LocaleServiceProvider());
        $this->app->register(new TranslationServiceProvider());
        $this->app->register(new ValidatorServiceProvider());
        $this->app->register(new SilexUserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the DoctrineOrmServiceProvider to use the SilexUserServiceProvider
     */
    public function testRegisterWitoutDoctrineOrm()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new SessionServiceProvider());
        $this->app->register(new LocaleServiceProvider());
        $this->app->register(new TranslationServiceProvider());
        $this->app->register(new ValidatorServiceProvider());
        $this->app->register(new FormServiceProvider());
        $this->app->register(new SilexUserServiceProvider());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must register the SecurityServiceProvider to use the SilexUserServiceProvider
     */
    public function tetRegisterWitoutSecurity()
    {
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider());
        $this->app->register(new SessionServiceProvider());
        $this->app->register(new LocaleServiceProvider());
        $this->app->register(new TranslationServiceProvider());
        $this->app->register(new ValidatorServiceProvider());
        $this->app->register(new FormServiceProvider());
        $this->app->register(new DoctrineServiceProvider());
        $this->app->register(new DoctrineOrmServiceProvider());

        $this->app->register(new SilexUserServiceProvider());
    }

    public function createApplication()
    {
        $app = new Application([
            'debug' => true
        ]);

        return $app;
    }
}
