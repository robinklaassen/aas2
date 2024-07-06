<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Root page refers to login
Route::get('/', 'Auth\LoginController@showLoginForm');

// New registration things
// Route::get('register-member', 'RegistrationController@registerMember');
// Route::post('register-member', 'RegistrationController@storeMember');
// Route::get('register-participant', 'RegistrationController@registerParticipant');
// Route::post('register-participant', 'RegistrationController@storeParticipant');

// Custom password reset
Route::get('forgot-password', 'PasswordController@forgot');
Route::post('forgot-password', 'PasswordController@reset');

// Static pages (or things not related to CRUD)
Route::view('referrer', 'pages.referrer');
Route::view('inschrijven', 'pages.referrer');

// Laravel standard registration and login things (customised)
Route::get('password/forgot', 'Auth\PasswordController@forgot');
Route::post('password/reset', 'Auth\PasswordController@reset');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
