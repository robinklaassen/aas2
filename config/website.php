<?php

declare(strict_types=1);

return [
    'github' => [
        'base_uri' => 'https://api.github.com/repos/',
        'repository' => env('GITHUB_REPOSITORY'),
        'token' => env('GITHUB_TOKEN'),
    ],
];
