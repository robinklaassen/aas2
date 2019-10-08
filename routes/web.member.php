<?php

# Declaration things
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

# Event things
Route::get('events/{event}/reviews', 'EventsController@reviews');
Route::resource('events', 'EventsController')->only(['show']);

# Member index
Route::resource('members', 'MembersController', ["only" => "index"]);
