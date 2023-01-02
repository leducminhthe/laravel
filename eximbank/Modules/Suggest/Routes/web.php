<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'suggest', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontendController@index')->name('module.suggest.index');
    Route::post('/save', 'FrontendController@save')->name('module.suggest.save');
    Route::get('/getdata', 'FrontendController@getData')->name('module.suggest.get_data');
    Route::get('/modal-comment/{id}', 'FrontendController@modalComment')->name('module.suggest.get_comment')->where('id', '[0-9]+');
    Route::post('/save-comment/{id}', 'FrontendController@saveComment')->name('module.suggest.save_comment')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/suggest', 'middleware' => 'auth'], function() {
    Route::get('/', 'SuggestController@index')->name('module.suggest')
    ->middleware('permission:suggest');

    Route::get('/getdata', 'SuggestController@getData')->name('module.suggest.getdata')
    ->middleware('permission:suggest');

    Route::post('/save-checked-reply', 'SuggestController@saveCheckedReply')->name('module.suggest.save_checked_reply')
    ->middleware('permission:suggest');

    Route::get('/view/{id}', 'SuggestController@form')->name('module.suggest.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:suggest');

});
