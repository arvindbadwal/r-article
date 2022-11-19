<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'models' => [
        'user' => App\Models\User::class
    ],
    'unsilo' => [
        'old_index' => 'cactus-rdiscovery',
        'new_index' => 'test-rdiscovery',
        'service' => App\Services\UnsiloService\UnsiloService::class
    ]
];