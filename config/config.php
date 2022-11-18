<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'models' => [
        'user' => App\Models\User::class
    ],
    'services' => [
      'unsilo' => App\Services\UnsiloService\UnsiloService::class
    ],

    'route' => [
        'prefix' => 'packages/v4',
        'middleware' => [
            'auth:api'
        ],
    ],
];