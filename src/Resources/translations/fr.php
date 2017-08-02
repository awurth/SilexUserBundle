<?php

return [
    'silex_user' => [
        'form' => [
            'username' => 'Nom d\'utilisateur',
            'password' => 'Mot de passe',
            'password_confirmation' => 'Répéter le mot de passe'
        ],
        'login' => [
            'title' => 'Login'
        ],
        'global' => [
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'register' => 'Inscription'
        ],
        'registration' => [
            'confirmed' => [
                'message' => 'Votre compte a été créé avec succès.',
                'title' => 'Inscription terminée'
            ],
            'title' => 'Inscription'
        ]
    ]
];
