<?php

return [
    'silex_user' => [
        'login' => [
            'title' => 'Login'
        ],
        'registration' => [
            'title' => 'Register',
            'check_email' => 'An email has been sent to %email%. It contains an activation link you must click to activate your account.',
            'email' => [
                'subject' => 'Welcome %username%!',
                'message' => '
                    Hello %username%!
                    
                    To finish activating your account - please visit %confirmationUrl%
                    
                    This link can only be used once to validate your account.
                    
                    Regards,
                    the Team.'
            ],
            'confirmed' => [
                'message' => 'Your account has been created successfully.',
                'title' => 'Registration confirmed'
            ],
            'flash' => [
                'user_created' => 'The user has been created successfully.'
            ]
        ],
        'form' => [
            'username' => 'Username',
            'password' => 'Password',
            'password_confirmation' => 'Repeat password'
        ],
        'global' => [
            'login' => 'Log in',
            'logout' => 'Log out',
            'register' => 'Register'
        ]
    ]
];
