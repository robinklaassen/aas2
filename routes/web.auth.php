<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('home', 'PagesController@home');
Route::view('info', 'pages.info');

// Profile things
Route::get('profile', 'ProfileController@show');
Route::post('profile', 'ProfileController@upload');
Route::get('profile/edit', 'ProfileController@edit');
Route::patch('profile', 'ProfileController@update');
Route::get('profile/password', 'ProfileController@password');
Route::put('profile/password', 'ProfileController@passwordSave');
Route::get('profile/on-camp', 'ProfileController@onCamp');
Route::put('profile/on-camp', 'ProfileController@onCampSave');

Route::middleware('member')->group(function () {
    Route::get('profile/add-course', 'ProfileController@addCourse');
    Route::put('profile/add-course', 'ProfileController@addCourseSave');
    Route::get('profile/edit-course/{course}', 'ProfileController@editCourse');
    Route::put('profile/edit-course/{course}', 'ProfileController@editCourseSave');
    Route::get(
        'profile/remove-course/{course}',
        'ProfileController@removeCourseConfirm'
    );
    Route::delete('profile/remove-course/{course}', 'ProfileController@removeCourse');
    Route::get('profile/reviews/{event}', 'ProfileController@reviews');
});

Route::middleware('participant')->group(function () {
    Route::get('profile/edit-camp/{event}', 'ProfileController@editCamp');
    Route::put('profile/edit-camp/{event}', 'ProfileController@editCampSave');
    Route::get('pay/{event}', 'ProfileController@setupExistingPayment');
});

// Comments
Route::get('comments/{comment}/delete', 'CommentsController@delete');
Route::delete('comments/{comment}', 'CommentsController@destroy');
Route::get('comments/{comment}/edit', 'CommentsController@edit');
Route::patch('comments/{comment}', 'CommentsController@update');
Route::get('comments/new', 'CommentsController@create');
Route::post('comments', 'CommentsController@store');

// Other
Route::get('accept-privacy', 'PagesController@showAcceptPrivacyStatement')->name('show-accept-privacy');
Route::post('accept-privacy', 'PagesController@storePrivacyStatement')->name('store-accept-privacy');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('roles/explain', 'RolesController@explain')->name('roles.explain');

// Declaration things
Route::get(
    'declarations/files/{file}/delete',
    'DeclarationsController@fileDelete'
);
Route::delete('declarations/files/{file}', 'DeclarationsController@fileDestroy');
Route::get('declarations/files', 'DeclarationsController@showFiles');
Route::get('declarations/{declaration}/delete', 'DeclarationsController@delete');
Route::delete('declarations/{declaration}', 'DeclarationsController@destroy');
Route::get('declarations/{declaration}/edit', 'DeclarationsController@edit');
Route::get('declarations/{declaration}/file', 'DeclarationsController@file');
Route::patch('declarations/{declaration}', 'DeclarationsController@update');
Route::get('declarations/create', 'DeclarationsController@create');
Route::get('declarations/create-bulk', 'DeclarationsController@bulk');
Route::post('declarations/create-bulk', 'DeclarationsController@bulkStore');
Route::post('declarations', 'DeclarationsController@store');
Route::get('declarations', 'DeclarationsController@index');

Route::resource('event-packages', 'EventPackagesController');
Route::get('event-packages/{eventPackage}/delete', 'EventPackagesController@delete');

// Event things
Route::resource('events', 'EventsController');
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
Route::get('events/{event}/join-members', 'EventsController@joinMembers');
Route::put('events/{event}/join-members', 'EventsController@joinMembersSave');
Route::get('events/{event}/move-participant/{participant}', 'EventsController@moveParticipant');
Route::put('events/{event}/move-participant/{participant}', 'EventsController@moveParticipantSave');

Route::get('events/{event}/reviews', 'EventsController@reviews');
Route::get('events/{event}', 'EventsController@show');

// Member things
Route::get('members/search', 'MembersController@search');
Route::get('members/search-skills', 'MembersController@searchSkills');
Route::get('members/export', 'MembersController@export');
Route::get('members/map', 'MembersController@map');
Route::resource('members', 'MembersController');

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

Route::middleware('member')->group(function () {
    Route::get('lists', 'ListsController');
    Route::get('graphs', 'GraphsController');
});

// Action things
Route::get('actions/{action}/delete', 'ActionsController@delete');
Route::resource('actions', 'ActionsController');

// Course things
Route::get('courses/{course}/delete', 'CoursesController@delete');
Route::resource('courses', 'CoursesController');

// Declaration things
Route::get(
    'declarations/process/{member}/{declarationType}',
    'DeclarationsController@confirmProcess'
);
Route::post('declarations/process', 'DeclarationsController@process');
Route::get('declarations/admin', 'DeclarationsController@admin');

// Location things
Route::get('locations/{location}/delete', 'LocationsController@delete');
Route::get('locations/{location}/reviews/{events}', 'LocationsController@reviews');
Route::resource('locations', 'LocationsController');

// Participant things
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
Route::get(
    'participants/anonymize',
    'ParticipantsController@anonymize'
);
Route::get(
    'participants/anonymize/confirm',
    'ParticipantsController@anonymizeConfirm'
);
Route::post(
    'participants/anonymize/confirm',
    'ParticipantsController@anonymizeStore'
);
Route::get('participants/export', 'ParticipantsController@export');
Route::resource('participants', 'ParticipantsController');
