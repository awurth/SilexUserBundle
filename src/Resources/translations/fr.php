<?php

return [
    'silex_user' => [
        'login' => [
            'title' => 'Login'
        ],
        'registration' => [
            'title' => 'Inscription',
            'check_email' => 'Un e-mail a été envoyé à l\'adresse %email%. Il contient un lien d\'activation sur lequel il vous faudra cliquer afin d\'activer votre compte.',
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
                'message' => 'Félicitations %username%, votre compte est maintenant activé.',
                'title' => 'Inscription confirmée'
            ],
            'flash' => [
                'user_created' => 'L\'utilisateur a été créé avec succès.'
            ]
        ],
        'form' => [
            'username' => 'Nom d\'utilisateur',
            'password' => 'Mot de passe',
            'password_confirmation' => 'Répéter le mot de passe'
        ],
        'global' => [
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'register' => 'Inscription',
            'logged_in_as' => 'Connecté en tant que %username%'
        ]
    ]
];
