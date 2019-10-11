<?php

Route::get('home', 'PagesController@home');
Route::get('info', 'PagesController@info');
Route::get('run-script', 'PagesController@runScript');

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

# Comments
Route::get("comments/{comment}/delete", "CommentsController@delete")->middleware('can:delete,comment');
Route::delete("comments/{comment}", "CommentsController@destroy")->middleware('can:delete,comment');
Route::get("comments/{comment}/edit", "CommentsController@edit")->middleware('can:update,comment');
Route::patch("comments/{comment}", "CommentsController@update")->middleware('can:update,comment');
Route::get("comments/new", "CommentsController@create")->middleware('can:create,App\Comment');
Route::post("comments", "CommentsController@store")->middleware('can:create,App\Comment');

# Other
Route::get("accept-privacy", "PagesController@showAcceptPrivacyStatement")->name("show-accept-privacy");
Route::post("accept-privacy", "PagesController@storePrivacyStatement")->name("store-accept-privacy");

Route::get('logout', 'Auth\LoginController@logout')->name('logout');
