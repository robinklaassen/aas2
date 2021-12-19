<?php

declare(strict_types=1);

return [
    'allowed-origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '')),
];
