<?php

return [
    'git' => [
        'branch' => env('UPDATER_GIT_BRANCH', 'master'),
        'remote' => env('UPDATER_GIT_REMOTE', 'origin')
    ],
    'composer_path' => env('UPDATER_COMPOSER_PATH', 'composer'),
    'secret' => env('UPDATER_SECRET')
];
