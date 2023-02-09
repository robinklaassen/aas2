<?php

declare(strict_types=1);

return [
    'directadmin' => [
        'uri' => env('DA_URI'),
        'username' => env('DA_USERNAME'),
        'accesstoken' => env('DA_ACCESSTOKEN'),
        'name' => env('DA_SUBSCRIBER_LIST_NAME'),
        'domain' => env('DA_SUBSCRIBER_LIST_DOMAIN'),
    ],
    'lists' => [
        'all' => [
            'memberTypes' => ['normaal', 'info', 'aspirant'],
        ],
    ],
];
