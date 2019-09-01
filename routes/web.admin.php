<?php

Route::get('lists', 'PagesController@lists');
Route::get('graphs', 'PagesController@graphs');

# Action things
Route::get('actions/{action}/delete', 'ActionsController@delete');
Route::resource('actions', 'ActionsController');

# Course things
Route::get('courses/{course}/delete', 'CoursesController@delete');
Route::resource('courses', 'CoursesController');

# Declartion things
Route::get(
    'declarations/process/{member}',
    'DeclarationsController@confirmProcess'
);
Route::post('declarations/process/{member}', 'DeclarationsController@process');
Route::get('declarations/admin', 'DeclarationsController@admin');

# Location things
Route::get('locations/{location}/delete', 'LocationsController@delete');
Route::get('locations/{location}/reviews/{events}', 'LocationsController@reviews');
Route::resource('locations', 'LocationsController');

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
Route::get('events/{event}/join-members', 'EventsController@joinMembers');
Route::put('events/{event}/join-members', 'EventsController@joinMembersSave');
Route::get('events/{event}/move-participant/{participant}', 'EventsController@moveParticipant');
Route::put('events/{event}/move-participant/{participant}', 'EventsController@moveParticipantSave');
Route::resource('events', 'EventsController', ["except" => "show"]);


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
Route::resource('members', 'MembersController', ["except" => "index"]);
