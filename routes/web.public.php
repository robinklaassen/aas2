<?php

declare(strict_types=1);

use App\Http\Controllers\iDealController;
use Illuminate\Support\Facades\Route;
use Updater\Http\Controllers\UpdateController;

// iDeal integration
Route::post('iDeal-webhook', [iDealController::class, 'webhook']);
Route::get('iDeal-response/{participant}/{event}', [iDealController::class, 'eventPaymentResponse']);
Route::get('iDeal-response', [iDealController::class, 'genericResponse']);
Route::get('doneer', 'DonateController');

// Pages
Route::get('privacy', 'PagesController@showPrivacyStatement');
Route::get('cal/{type}', 'PagesController@cal')->middleware('cors');
Route::get('camp-info/{camp}', 'PagesController@campInfo');
Route::get('camps-report', 'PagesController@campsReport');

// Review things
Route::get('enquete/{event}', 'ReviewsController@review');
Route::post('enquete/{event}', 'ReviewsController@reviewPost');

// icalendar routes
Route::get('events/icalendar', 'EventsController@iCalendar');

Route::get('updater/update', [UpdateController::class, 'update']);
Route::get('updater/version', [UpdateController::class, 'version']);

Route::post('api/contact-form', 'ContactFormController@send')->middleware('cors');
