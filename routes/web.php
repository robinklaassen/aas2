<?php

declare(strict_types=1);


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
*/

// Everybody
Route::middleware([])->group(base_path('routes/web.public.php'));

// Only for non-authenticated users
Route::middleware(['guest'])->group(base_path('routes/web.guest.php'));

// user routes
Route::middleware(['auth'])->group(base_path('routes/web.auth.php'));
