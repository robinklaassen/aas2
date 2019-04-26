<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
*/

# Root page refers to login
Route::get('/', 'Auth\LoginController@showLoginForm');

# iDeal integration
Route::post('iDeal-webhook', 'iDealController@webhook');
Route::get('iDeal-response/{participants}/{events}', 'iDealController@response');

# Custom password reset
Route::get('forgot-password', 'PasswordController@forgot');
Route::post('forgot-password', 'PasswordController@reset');

# New registration things
Route::get('register-member', 'RegistrationController@registerMember');
Route::post('register-member', 'RegistrationController@storeMember');
Route::get('register-participant', 'RegistrationController@registerParticipant');
Route::post('register-participant', 'RegistrationController@storeParticipant');

# Static pages (or things not related to CRUD)
Route::get('home', 'PagesController@home');
Route::get('info', 'PagesController@info');
Route::get('lists', 'PagesController@lists');
Route::get('graphs', 'PagesController@graphs');
Route::get('temp-test', 'PagesController@tempTest');
Route::get('run-script', 'PagesController@runScript');
Route::get('cal/{type}', 'PagesController@cal');
Route::get('camp-info/{camp}', 'PagesController@campInfo');
Route::get('camps-report', 'PagesController@campsReport');
Route::get('referrer', 'PagesController@referrer');
Route::get('inschrijven', 'PagesController@referrer');

# Profile things
Route::get('profile', 'ProfileController@show');
Route::post('profile', 'ProfileController@upload');
Route::get('profile/edit', 'ProfileController@edit');
Route::patch('profile', 'ProfileController@update');
Route::get('profile/password', 'ProfileController@password');
Route::put('profile/password', 'ProfileController@passwordSave');
Route::get('profile/declare', 'ProfileController@declareForm');
Route::put('profile/declare', 'ProfileController@declareSubmit');
Route::get('profile/add-course', 'ProfileController@addCourse');
Route::put('profile/add-course', 'ProfileController@addCourseSave');
Route::get('profile/edit-course/{course}', 'ProfileController@editCourse');
Route::put('profile/edit-course/{course}', 'ProfileController@editCourseSave');
Route::get('profile/remove-course/{course}',
    'ProfileController@removeCourseConfirm');
Route::get('profile/on-camp', 'ProfileController@onCamp');
Route::put('profile/on-camp', 'ProfileController@onCampSave');
Route::get('profile/edit-camp/{event}', 'ProfileController@editCamp');
Route::put('profile/edit-camp/{event}', 'ProfileController@editCampSave');
Route::delete('profile/remove-course/{course}', 'ProfileController@removeCourse');
Route::get('profile/reviews/{event}', 'ProfileController@reviews');

# Participant things
Route::get('participants/{participants}/on-event',
    'ParticipantsController@onEvent');
Route::put('participants/{participants}/on-event',
    'ParticipantsController@onEventSave');
Route::get('participants/{participants}/edit-event/{event}',
    'ParticipantsController@editEvent');
Route::put('participants/{participants}/edit-event/{event}',
    'ParticipantsController@editEventSave');
Route::get('participants/{participants}/delete', 'ParticipantsController@delete');
Route::get('participants/export', 'ParticipantsController@export');
Route::get('participants/map', 'ParticipantsController@map');
Route::resource('participants', 'ParticipantsController');

# Member things
Route::get('members/search', 'MembersController@search');
Route::get('members/{members}/on-event', 'MembersController@onEvent');
Route::put('members/{members}/on-event', 'MembersController@onEventSave');
Route::get('members/{members}/add-course', 'MembersController@addCourse');
Route::put('members/{members}/add-course', 'MembersController@addCourseSave');
Route::get('members/{members}/edit-course/{course}',
    'MembersController@editCourse');
Route::put('members/{members}/edit-course/{course}',
    'MembersController@editCourseSave');
Route::get('members/{members}/remove-course/{course}',
    'MembersController@removeCourseConfirm');
Route::delete('members/{members}/remove-course/{course}',
    'MembersController@removeCourse');
Route::get('members/{members}/delete', 'MembersController@delete');
Route::get('members/export', 'MembersController@export');
Route::get('members/map', 'MembersController@map');
Route::resource('members', 'MembersController');

# Event things
Route::get('events/{events}/delete', 'EventsController@delete');
Route::get('events/{events}/remove-member/{member}',
    'EventsController@removeMemberConfirm');
Route::delete('events/{events}/remove-member/{member}',
    'EventsController@removeMember');
Route::get('events/{events}/edit-member/{member}', 'EventsController@editMember');
Route::put('events/{events}/edit-member/{member}',
    'EventsController@editMemberSave');
Route::get('events/{events}/edit-participant/{participant}',
    'EventsController@editParticipant');
Route::put('events/{events}/edit-participant/{participant}',
    'EventsController@editParticipantSave');
Route::get('events/{events}/remove-participant/{participant}',
    'EventsController@removeParticipantConfirm');
Route::delete('events/{events}/remove-participant/{participant}',
    'EventsController@removeParticipant');
Route::get('events/{events}/export', 'EventsController@export');
Route::get('events/{events}/check/{type}', 'EventsController@check');
Route::get('events/{events}/budget', 'EventsController@budget');
Route::get('events/{events}/email', 'EventsController@email');
Route::get('events/{events}/send', 'EventsController@sendConfirm');
Route::put('events/{events}/send', 'EventsController@send');
Route::get('events/{events}/payments', 'EventsController@payments');
Route::get('events/{events}/night-register', 'EventsController@nightRegister');
Route::get('events/icalendar', 'EventsController@iCalendar');
Route::get('events/{events}/join-members', 'EventsController@joinMembers');
Route::put('events/{events}/join-members', 'EventsController@joinMembersSave');
Route::get('events/{events}/reviews', 'EventsController@reviews');
Route::get('events/{events}/move-participant/{participant}', 'EventsController@moveParticipant');
Route::put('events/{events}/move-participant/{participant}', 'EventsController@moveParticipantSave');
Route::resource('events', 'EventsController');

# Location things
Route::get('locations/{locations}/delete', 'LocationsController@delete');
Route::get('locations/{locations}/reviews/{events}', 'LocationsController@reviews');
Route::resource('locations', 'LocationsController');

# Course things
Route::get('courses/{courses}/delete', 'CoursesController@delete');
Route::resource('courses', 'CoursesController');

# Action things
Route::get('actions/{actions}/delete', 'ActionsController@delete');
Route::resource('actions', 'ActionsController');

# User things
Route::get('users/{users}/admin', 'UsersController@admin');
Route::put('users/{users}/admin', 'UsersController@adminSave');
Route::get('users/{users}/password', 'UsersController@password');
Route::put('users/{users}/password', 'UsersController@passwordSave');
Route::get('users/{users}/delete', 'UsersController@delete');
Route::get('users/create-for-member', 'UsersController@createForMember');
Route::post('users/create-for-member', 'UsersController@storeForMember');
Route::get('users/create-for-participant',
    'UsersController@createForParticipant');
Route::post('users/create-for-participant',
    'UsersController@storeForParticipant');
Route::resource('users', 'UsersController');

# Declaration things
Route::get('declarations/process/{members}',
    'DeclarationsController@confirmProcess');
Route::post('declarations/process/{members}', 'DeclarationsController@process');
Route::get('declarations/admin', 'DeclarationsController@admin');
Route::get('declarations/files/{files}/delete',
    'DeclarationsController@fileDelete');
Route::delete('declarations/files/{files}', 'DeclarationsController@fileDestroy');
Route::get('declarations/files', 'DeclarationsController@showFiles');
Route::get('declarations/{declarations}/delete', 'DeclarationsController@delete');
Route::delete('declarations/{declarations}', 'DeclarationsController@destroy');
Route::get('declarations/{declarations}/edit', 'DeclarationsController@edit');
Route::patch('declarations/{declarations}', 'DeclarationsController@update');
Route::get('declarations/upload', 'DeclarationsController@upload');
Route::post('declarations/create', 'DeclarationsController@create');
Route::post('declarations', 'DeclarationsController@store');
Route::get('declarations', 'DeclarationsController@index');
//Route::resource('declarations', 'DeclarationsController');

# Review things
Route::get('enquete/{events}', 'ReviewsController@review');
Route::post('enquete/{events}', 'ReviewsController@reviewPost');

# Laravel standard registration and login things (customised)
Route::get('password/fogot', 'Auth\PasswordController@forgot');
Route::post('password/reset', 'Auth\PasswordController@reset');

Auth::routes();