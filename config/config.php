<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'models' => [
        'user' => App\Models\User::class
    ],

    'route' => [
        'prefix' => 'packages/v4',
        'middleware' => [
            'auth:api'
        ],
    ],
];