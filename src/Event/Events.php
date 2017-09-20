<?php

namespace AWurth\SilexUser\Event;

/**
 * Contains all events thrown by SilexUser.
 *
 * @author Alexis Wurth <alexis.wurth57@gmail.com>
 */
class Events
{
    const REGISTRATION_INITIALIZE = 'silex_user.registration.initialize';

    const REGISTRATION_SUCCESS = 'silex_user.registration.success';

    const REGISTRATION_FAILURE = 'silex_user.registration.failure';

    const REGISTRATION_COMPLETED = 'silex_user.registration.completed';

    const REGISTRATION_CONFIRM = 'silex_user.registration.confirm';

    const REGISTRATION_CONFIRMED = 'silex_user.registration.confirmed';

    const SECURITY_IMPLICIT_LOGIN = 'silex_user.security.implicit_login';
}
