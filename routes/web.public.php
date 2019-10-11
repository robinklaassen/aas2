<?php

# iDeal integration
Route::post('iDeal-webhook', 'iDealController@webhook');
Route::get('iDeal-response/{participant}/{event}', 'iDealController@response');

# Pages
Route::get("privacy", "PagesController@showPrivacyStatement");
Route::get('cal/{type}', 'PagesController@cal')->middleware('cors');
Route::get('camp-info/{camp}', 'PagesController@campInfo');
Route::get('camps-report', 'PagesController@campsReport');

# Review things
Route::get('enquete/{event}', 'ReviewsController@review');
Route::post('enquete/{event}', 'ReviewsController@reviewPost');
