<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Google Analytics / Ads tags
    |--------------------------------------------------------------------------
    |
    | These tags are used to track site usage and register specific conversions.
    |
    */

    'site_tag' => env('GOOGLE_SITE_TAG', null),

    'conversion_new_member' => env('GOOGLE_CONVERSION_NEW_MEMBER', null),

    'conversion_new_participant' => env('GOOGLE_CONVERSION_NEW_PARTICIPANT', null),
];
