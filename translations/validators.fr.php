<?php

return [
    'silex_user' => [
        'email' => [
            'already_used' => 'L\'adresse email est déjà utilisée.',
            'blank' =>        'Veuillez entrer une adresse email.',
            'long' =>         'L\'adresse email ne doit pas dépasser {{ limit }} caractères.',
            'invalid' =>      'L\'adresse email est invalide.'
        ],
        'username' => [
            'already_used' => 'Le nom d\'utilisateur est déjà utilisé.',
            'blank' =>        'Veuillez entrer un nom d\'utilisateur.',
            'short' =>        'Le nom d\'utilisateur doit être composé d\'au moins {{ limit }} caractères.',
            'long' =>         'Le nom d\'utilisateur ne doit pas dépasser {{ limit }} caractères.',
            'invalid' =>      'Le nom d\'utilisateur ne peut contenir que des lettres et des chiffres et ".", "_", "-"'
        ],
        'password' => [
            'blank' =>    'Veuillez entrer un mot de passe',
            'short' =>    'Le nom d\'utilisateur doit être composé d\'au moins {{ limit }} caractères.',
            'long' =>     'Le nom d\'utilisateur ne doit pas dépasser {{ limit }} caractères.',
            'mismatch' => 'Les deux mots de passe ne sont pas identiques.'
        ]
    ]
];
