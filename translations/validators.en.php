<?php

return [
    'silex_user' => [
        'email' => [
            'already_used' => 'This email is already used.',
            'blank' =>        'Please enter an email.',
            'long' =>         'The email must not exceed {{ limit }} characters.',
            'invalid' =>      'The email is not valid.'
        ],
        'username' => [
            'already_used' => 'This username is already used.',
            'blank' =>        'Please enter a username.',
            'short' =>        'The username must be at least {{ limit }} characters long.',
            'long' =>         'The username must not exceed {{ limit }} characters.',
            'invalid' =>      'The username can contain only letters and digits and ".", "_", "-"'
        ],
        'password' => [
            'blank' =>    'Please enter a password.',
            'short' =>    'The password must be at least {{ limit }} characters long.',
            'long' =>     'The password must not exceed {{ limit }} characters.',
            'mismatch' => 'The entered passwords don\'t match.'
        ]
    ]
];
