<?php

declare(strict_types=1);

return [
    'github' => [
        'repository' => env('GITHUB_REPOSITORY'),
        'token' => env('GITHUB_TOKEN'),
    ],
    'webhook' => [
        'uri' => env('WEBSITE_UPDATER_URI'),
    ],
];
