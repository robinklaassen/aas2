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
Route::get('iDeal-response/{participant}/{event}', 'iDealController@response');

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
Route::get(
    'profile/remove-course/{course}',
    'ProfileController@removeCourseConfirm'
);
Route::get('profile/on-camp', 'ProfileController@onCamp');
Route::put('profile/on-camp', 'ProfileController@onCampSave');
Route::get('profile/edit-camp/{event}', 'ProfileController@editCamp');
Route::put('profile/edit-camp/{event}', 'ProfileController@editCampSave');
Route::delete('profile/remove-course/{course}', 'ProfileController@removeCourse');
Route::get('profile/reviews/{event}', 'ProfileController@reviews');

# Participant things
Route::get(
    'participants/{participant}/on-event',
    'ParticipantsController@onEvent'
);
Route::put(
    'participants/{participant}/on-event',
    'ParticipantsController@onEventSave'
);
Route::get(
    'participants/{participant}/edit-event/{event}',
    'ParticipantsController@editEvent'
);
Route::put(
    'participants/{participant}/edit-event/{event}',
    'ParticipantsController@editEventSave'
);
Route::get('participants/{participant}/delete', 'ParticipantsController@delete');
Route::get('participants/export', 'ParticipantsController@export');
Route::get('participants/map', 'ParticipantsController@map');
Route::resource('participants', 'ParticipantsController');

# Member things
Route::get('members/search', 'MembersController@search');
Route::get('members/{member}/on-event', 'MembersController@onEvent');
Route::put('members/{member}/on-event', 'MembersController@onEventSave');
Route::get('members/{member}/add-course', 'MembersController@addCourse');
Route::put('members/{member}/add-course', 'MembersController@addCourseSave');
Route::get(
    'members/{member}/edit-course/{course}',
    'MembersController@editCourse'
);
Route::put(
    'members/{member}/edit-course/{course}',
    'MembersController@editCourseSave'
);
Route::get(
    'members/{member}/remove-course/{course}',
    'MembersController@removeCourseConfirm'
);
Route::delete(
    'members/{member}/remove-course/{course}',
    'MembersController@removeCourse'
);
Route::get('members/{member}/delete', 'MembersController@delete');
Route::get('members/export', 'MembersController@export');
Route::get('members/map', 'MembersController@map');
Route::resource('members', 'MembersController');

# Event things
Route::get('events/{event}/delete', 'EventsController@delete');
Route::get(
    'events/{event}/remove-member/{member}',
    'EventsController@removeMemberConfirm'
);
Route::delete(
    'events/{event}/remove-member/{member}',
    'EventsController@removeMember'
);
Route::get('events/{event}/edit-member/{member}', 'EventsController@editMember');
Route::put(
    'events/{event}/edit-member/{member}',
    'EventsController@editMemberSave'
);
Route::get(
    'events/{event}/edit-participant/{participant}',
    'EventsController@editParticipant'
);
Route::put(
    'events/{event}/edit-participant/{participant}',
    'EventsController@editParticipantSave'
);
Route::get(
    'events/{event}/remove-participant/{participant}',
    'EventsController@removeParticipantConfirm'
);
Route::delete(
    'events/{event}/remove-participant/{participant}',
    'EventsController@removeParticipant'
);
Route::get('events/{event}/export', 'EventsController@export');
Route::get('events/{event}/check/{type}', 'EventsController@check');
Route::get('events/{event}/budget', 'EventsController@budget');
Route::get('events/{event}/email', 'EventsController@email');
Route::get('events/{event}/send', 'EventsController@sendConfirm');
Route::put('events/{event}/send', 'EventsController@send');
Route::get('events/{event}/payments', 'EventsController@payments');
Route::get('events/{event}/night-register', 'EventsController@nightRegister');
Route::get('events/icalendar', 'EventsController@iCalendar');
Route::get('events/{event}/join-members', 'EventsController@joinMembers');
Route::put('events/{event}/join-members', 'EventsController@joinMembersSave');
Route::get('events/{event}/reviews', 'EventsController@reviews');
Route::get('events/{event}/move-participant/{participant}', 'EventsController@moveParticipant');
Route::put('events/{event}/move-participant/{participant}', 'EventsController@moveParticipantSave');
Route::resource('events', 'EventsController');

# Location things
Route::get('locations/{location}/delete', 'LocationsController@delete');
Route::get('locations/{location}/reviews/{events}', 'LocationsController@reviews');
Route::resource('locations', 'LocationsController');

# Course things
Route::get('courses/{course}/delete', 'CoursesController@delete');
Route::resource('courses', 'CoursesController');

# Action things
Route::get('actions/{action}/delete', 'ActionsController@delete');
Route::resource('actions', 'ActionsController');

# User things
Route::get('users/{user}/admin', 'UsersController@admin');
Route::put('users/{user}/admin', 'UsersController@adminSave');
Route::get('users/{user}/password', 'UsersController@password');
Route::put('users/{user}/password', 'UsersController@passwordSave');
Route::get('users/{user}/delete', 'UsersController@delete');
Route::get('users/create-for-member', 'UsersController@createForMember');
Route::post('users/create-for-member', 'UsersController@storeForMember');
Route::get(
    'users/create-for-participant',
    'UsersController@createForParticipant'
);
Route::post(
    'users/create-for-participant',
    'UsersController@storeForParticipant'
);
Route::resource('users', 'UsersController');

# Declaration things
Route::get(
    'declarations/process/{member}',
    'DeclarationsController@confirmProcess'
);
Route::post('declarations/process/{member}', 'DeclarationsController@process');
Route::get('declarations/admin', 'DeclarationsController@admin');
Route::get(
    'declarations/files/{file}/delete',
    'DeclarationsController@fileDelete'
);
Route::delete('declarations/files/{file}', 'DeclarationsController@fileDestroy');
Route::get('declarations/files', 'DeclarationsController@showFiles');
Route::get('declarations/{declaration}/delete', 'DeclarationsController@delete');
Route::delete('declarations/{declaration}', 'DeclarationsController@destroy');
Route::get('declarations/{declaration}/edit', 'DeclarationsController@edit');
Route::patch('declarations/{declaration}', 'DeclarationsController@update');
Route::get('declarations/upload', 'DeclarationsController@upload');
Route::post('declarations/create', 'DeclarationsController@create');
Route::post('declarations', 'DeclarationsController@store');
Route::get('declarations', 'DeclarationsController@index');

# Review things
Route::get('enquete/{event}', 'ReviewsController@review');
Route::post('enquete/{event}', 'ReviewsController@reviewPost');

# Laravel standard registration and login things (customised)
Route::get('password/forgot', 'Auth\PasswordController@forgot');
Route::post('password/reset', 'Auth\PasswordController@reset');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get("privacy-statement", "Users@privacy");

// Registration Routes...
// Note: these are the routes for the skeleton Laravel registration forms. We use register-member and register-participant
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');
