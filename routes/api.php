<?php

declare(strict_types=1);

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\CorsController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\iDealController;
use Illuminate\Support\Facades\Route;

// JSON API's for website
Route::controller(ApiController::class)->group(function () {
    Route::get('cal/{type}', 'cal');
    Route::get('camp-info/{camp}', 'campInfo');
    Route::get('camps-report', 'campsReport');
});

// Contact form on website
Route::post('api/contact-form', [ContactFormController::class, 'send']);

// iDeal webhook
Route::post('iDeal-webhook', [iDealController::class, 'webhook']);

// Donate route
Route::post('donate', [DonateController::class, 'donate']);
// add OPTIONS route to fire cors middleware for preflight
Route::options('donate', CorsController::class);
