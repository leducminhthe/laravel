<?php

#include_once (__DIR__ . '/frontend/routes.php');

include_once (__DIR__ . '/backend/routes.php');

Route::group(['prefix' => '/usermedal', 'middleware' => 'auth'], function() {

    Route::get('/', 'UserMedalController@index')->name('module.frontend.usermedal.list')->middleware('pagespeed');
    Route::get('/detail/{id}', 'UserMedalController@detail')->name('module.frontend.usermedal.detail')->where('id', '[0-9]+');
    Route::get('/dataresult/{id}', 'UserMedalController@getDataResult')->name('module.frontend.usermedal.dataresult')->where('id', '[0-9]+');

});
Route::group(['prefix' => '/usermedal-history', 'middleware' => 'auth'], function() {
    Route::get('/', 'UserMedalController@history')->name('module.frontend.usermedal.history')->middleware('pagespeed');
    Route::get('/datahistory', 'UserMedalController@getDataHistory')->name('module.frontend.usermedal.datahistory');
});
