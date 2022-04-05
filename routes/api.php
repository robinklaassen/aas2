<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// JSON API's for website
Route::controller('ApiController')->group(function () {
    Route::get('cal/{type}', 'cal');
    Route::get('camp-info/{camp}', 'campInfo');
    Route::get('camps-report', 'campsReport');
});

// Contact form on website
Route::post('api/contact-form', 'ContactFormController@send');
