<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Updater\Http\Controllers\UpdateController;

// Pages
Route::get('privacy', 'PagesController@showPrivacyStatement');

// Review things
Route::get('enquete/{event}', 'ReviewsController@review');
Route::post('enquete/{event}', 'ReviewsController@reviewPost');

// icalendar routes
Route::get('events/icalendar', 'EventsController@iCalendar');

Route::get('updater/update', [UpdateController::class, 'update']);
Route::get('updater/version', [UpdateController::class, 'version']);

// iDeal integration
Route::post('iDeal-webhook', [iDealController::class, 'webhook']);
Route::get('iDeal-response/{participant}/{event}', [iDealController::class, 'eventPaymentResponse']);
Route::get('iDeal-response', [iDealController::class, 'genericResponse']);
Route::get('donate', [DonateController::class, 'donate']);
Route::get('donate/done', [DonateController::class, 'response']);
