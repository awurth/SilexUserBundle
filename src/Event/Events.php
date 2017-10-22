<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Silex\User\Event;

/**
 * Contains all events thrown by the Silex User Provider.
 */
class Events
{
    /**
     * The REGISTRATION_INITIALIZE event occurs when the registration process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     */
    const REGISTRATION_INITIALIZE = 'silex_user.registration.initialize';

    /**
     * The REGISTRATION_SUCCESS event occurs when the registration form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     */
    const REGISTRATION_SUCCESS = 'silex_user.registration.success';

    /**
     * The REGISTRATION_FAILURE event occurs when the registration form is not valid.
     *
     * This event allows you to set the response instead of using the default one.
     * The event listener method receives a AWurth\Silex\User\Event\FormEvent instance.
     */
    const REGISTRATION_FAILURE = 'silex_user.registration.failure';

    /**
     * The REGISTRATION_COMPLETED event occurs after saving the user in the registration process.
     *
     * This event allows you to access the response which will be sent.
     */
    const REGISTRATION_COMPLETED = 'silex_user.registration.completed';

    /**
     * The REGISTRATION_CONFIRM event occurs just before confirming the account.
     *
     * This event allows you to access the user which will be confirmed.
     */
    const REGISTRATION_CONFIRM = 'silex_user.registration.confirm';

    /**
     * The REGISTRATION_CONFIRMED event occurs after confirming the account.
     *
     * This event allows you to access the response which will be sent.
     */
    const REGISTRATION_CONFIRMED = 'silex_user.registration.confirmed';

    /**
     * The SECURITY_IMPLICIT_LOGIN event occurs when the user is logged in programmatically.
     *
     * This event allows you to access the response which will be sent.
     */
    const SECURITY_IMPLICIT_LOGIN = 'silex_user.security.implicit_login';

    /**
     * The USER_CREATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the created user and to add some behaviour after the creation.
     */
    const USER_CREATED = 'silex_user.user.created';

    /**
     * The USER_ACTIVATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the activated user and to add some behaviour after the activation.
     */
    const USER_ACTIVATED = 'silex_user.user.activated';

    /**
     * The USER_DEACTIVATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the deactivated user and to add some behaviour after the deactivation.
     */
    const USER_DEACTIVATED = 'silex_user.user.deactivated';

    /**
     * The USER_PASSWORD_CHANGED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the created user and to add some behaviour after the password change.
     */
    const USER_PASSWORD_CHANGED = 'silex_user.user.password_changed';

    /**
     * The USER_PROMOTED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the promoted user and to add some behaviour after the promotion.
     */
    const USER_PROMOTED = 'silex_user.user.promoted';
    
    /**
     * The USER_DEMOTED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the demoted user and to add some behaviour after the demotion.
     */
    const USER_DEMOTED = 'silex_user.user.demoted';
}
