<?php

declare(strict_types=1);

use App\Http\Controllers\DonateController;
use App\Http\Controllers\iDealController;
use Illuminate\Support\Facades\Route;

// JSON API's for website
Route::controller('ApiController')->group(function () {
    Route::get('cal/{type}', 'cal');
    Route::get('camp-info/{camp}', 'campInfo');
    Route::get('camps-report', 'campsReport');
});

// Contact form on website
Route::post('api/contact-form', 'ContactFormController@send');

// iDeal integration
Route::post('iDeal-webhook', [iDealController::class, 'webhook']);
Route::get('iDeal-response/{participant}/{event}', [iDealController::class, 'eventPaymentResponse']);
Route::get('iDeal-response', [iDealController::class, 'genericResponse']);
Route::get('donate', [DonateController::class, 'donate']);
Route::get('donate/done', [DonateController::class, 'response']);
