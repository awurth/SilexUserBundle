# SilexUser - User Provider for Silex

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1ec0cb08-4d4f-4dcf-86ff-6512afaf24d5/mini.png)](https://insight.sensiolabs.com/projects/1ec0cb08-4d4f-4dcf-86ff-6512afaf24d5) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/awurth/silex-user/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/awurth/silex-user/?branch=master) [![Total Downloads](https://poser.pugx.org/awurth/silex-user/downloads)](https://packagist.org/packages/awurth/silex-user) [![License](https://poser.pugx.org/awurth/silex-user/license)](https://packagist.org/packages/awurth/silex-user)

Inspired by [FOS User Bundle](https://github.com/FriendsOfSymfony/FOSUserBundle) and [Silex Simple User](https://github.com/jasongrimes/silex-simpleuser).

See [awurth/silex](https://github.com/awurth/silex) for an example implementation.

See the [project's website](http://awurth.fr/doc/silex-user) for complete and up to date documentation.

## Dependencies
SilexUser depends on the following libraries
- [Doctrine ORM](http://www.doctrine-project.org/projects/orm.html) (see [Doctrine ORM Service Provider](https://github.com/dflydev/dflydev-doctrine-orm-service-provider))
- [Symfony Security Component](http://symfony.com/doc/current/components/security.html) (see [Silex Security Service Provider](https://silex.symfony.com/doc/2.0/providers/security.html))
- [Symfony Form Component](https://symfony.com/doc/current/components/form.html) (see [Silex Form Service Provider](https://silex.symfony.com/doc/2.0/providers/form.html))
- [Symfony Validator Component](https://symfony.com/doc/current/components/validator.html) (see [Silex Validator Service Provider](https://silex.symfony.com/doc/2.0/providers/validator.html))
- [Twig Templating Engine](https://twig.symfony.com) (see [Twig Service Provider](https://silex.symfony.com/doc/2.0/providers/twig.html))

## Installation
#### Download SilexUser with Composer
``` bash
$ composer require awurth/silex-user
```

#### Register Service Providers
``` php
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider());
```

#### Install and configure Doctrine ORM
SilexUser uses the Doctrine ORM and the `orm.em` service to get and save data to the database.
I recommend you use the [Doctrine ORM Service Provider](https://github.com/dflydev/dflydev-doctrine-orm-service-provider).

``` bash
$ composer require dflydev/doctrine-orm-service-provider
```

##### Configure database connexion
``` php
$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'my_database',
        'user'      => 'my_username',
        'password'  => 'my_password',
        'charset'   => 'utf8mb4'
    ]
]);
```

##### Register Doctrine ORM Service Provider
``` php
$app->register(new Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider());
```

##### Register Doctrine ORM Manager Registry Provider
The base User entity uses the `UniqueEntity` validation constraint,
so you will need to install the [Doctrine ORM Manager Registry Provider](https://github.com/saxulum/saxulum-doctrine-orm-manager-registry-provider)
or any other library that enables that feature.

``` bash
$ composer require saxulum/saxulum-doctrine-orm-manager-registry-provider
```

``` php
$app->register(new Saxulum\DoctrineOrmManagerRegistry\Provider\DoctrineOrmManagerRegistryProvider());
```

##### Register the SilexUserServiceProvider
``` php
$app->register(new AWurth\SilexUser\Provider\SilexUserServiceProvider());
```

#### Create User class
``` php
<?php

namespace Security\Entity;

use AWurth\SilexUser\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{
}
```

You can use this class like any other Doctrine entity and add properties and validation constraints.

#### Register Doctrine mappings
``` php
$app['orm.em.options'] = [
    'mappings' => [
        [
            'type' => 'annotation',
            'namespace' => 'Namespace\Of\User\Class',
            'path' => 'path/to/User/class/directory',
            'use_simple_annotation_reader' => false
        ]
    ]
];
```

The base User entity uses Doctrine advanced annotations so you need to set
the `use_simple_annotation_reader` option to `false`.

#### Configure your application's security
``` php
use AWurth\SilexUser\Provider\UserProvider;
use Silex\Provider\SecurityServiceProvider;

$app->register(new SecurityServiceProvider(), [
    'security.firewalls' => [
        'your_firewall_name' => [
            'pattern' => '^/',
            'form' => [
                'login_path' => '/login',
                'check_path' => '/login_check',
                'with_csrf' => true
            ],
            'logout' => [
                'logout_path' => '/logout',
                'invalidate_session' => true
            ],
            'anonymous' => true,
            'users' => function () use ($app) {
                return new UserProvider($app);
            }
        ]
    ]
]);
```

#### Configure SilexUser
``` php
$app['silex_user.options'] = [
    'user_class' => YourUserClass::class,
    'firewall_name' => 'your_firewall_name'
];
```

# TODO
- Commands for creating/removing/updating users
- Custom routes
- Change password
- Groups
- Roles
- Tests
- Configuration
    - Security
