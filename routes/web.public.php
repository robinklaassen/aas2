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
