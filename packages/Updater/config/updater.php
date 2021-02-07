<?php

return [
    'git' => [
        'branch' => env('UPDATER_GIT_BRANCH', 'master'),
        'remote' => env('UPDATER_GIT_REMOTE', 'origin')
    ]
];
