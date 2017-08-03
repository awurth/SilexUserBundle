# SilexUser - User Provider for Silex

Inspired by [Silex User Pack](https://github.com/quazardous/silex-user-pack), [Silex Simple User](https://github.com/jasongrimes/silex-simpleuser) and [FOS User Bundle](https://github.com/FriendsOfSymfony/FOSUserBundle).

See [awurth/silex](https://github.com/awurth/silex) for an example implementation.

## Dependencies
SilexUser depends on the following libraries
- [Doctrine ORM](http://www.doctrine-project.org/projects/orm.html) (see [Doctrine ORM Service Provider](https://github.com/dflydev/dflydev-doctrine-orm-service-provider))
- [Symfony Security Component](http://symfony.com/doc/current/components/security.html) (see [Silex Security Service Provider](https://silex.symfony.com/doc/2.0/providers/security.html))
- [Symfony Form Component](https://symfony.com/doc/current/components/form.html) (see [Silex Form Service Provider](https://silex.symfony.com/doc/2.0/providers/form.html))
- [Symfony Validator Component](https://symfony.com/doc/current/components/validator.html) (see [Silex Validator Service Provider](https://silex.symfony.com/doc/2.0/providers/validator.html))
- [Twig Templating Engine](https://twig.symfony.com) (see [Twig Service Provider](https://silex.symfony.com/doc/2.0/providers/twig.html))

## Installation
#### Install SilexUser with Composer
``` bash
$ composer require awurth/silex-user
```

``` php
$silexUser = new AWurth\SilexUser\Provider\SilexUserServiceProvider();

$app->register($silexUser);

$app->mount('/', $silexUser);
```

#### Register Service Providers
``` php
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\FormServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider());
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

#### Create User class

``` php
<?php
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

##### Tell SilexUser which class to use
``` php
$app['silex_user.user_class'] = YourUserClass::class;
```

You can use this class like any other Doctrine entity and add properties and validation constraints.

#### Register Doctrine mappings
``` php
$app['orm.em.options'] = [
    'mappings' => [
        [
            'type' => 'annotation',
            'namespace' => 'Namespace\Of\User\Class',
            'path' => 'path/of/User/class',
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
        'secured' => [
            'pattern' => '^/',
            'form' => [
                'login_path' => '/login',
                'check_path' => '/login_check'
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

## Overwriting default templates
You might want to overwrite the default templates. To do so, you just have
to copy the structure of the templates in `silex_user/src/views`:
create a folder named `silex_user` and put your templates in it.
Templates for the authentication area go in the `auth` folder and
templates for emails go in the `email` folder.

You can now set the `silex_user.use_templates` option to `false`.
This just prevents SilexUser to add the default templates directory to Twig paths.

# TODO
- Commands for creating/removing/updating users
- Custom routes
- Change password
- Groups
- Roles
- Email activation
- Tests
- Configuration
    - Security
    - Salt generator
    - Email activation
    - Log in after registration
