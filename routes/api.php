<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// JSON API's for website
Route::get('cal/{type}', 'ApiController@cal');
Route::get('camp-info/{camp}', 'ApiController@campInfo');
Route::get('camps-report', 'ApiController@campsReport');

// Contact form on website
Route::post('api/contact-form', 'ContactFormController@send');

// iDeal integration
Route::post('iDeal-webhook', 'iDealController@webhook');
Route::get('iDeal-response/{participant}/{event}', 'iDealController@response');
