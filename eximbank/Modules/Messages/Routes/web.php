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

Route::group(['prefix'=>'/messages/bot', 'middleware' => 'auth'], function() {
    Route::post('/', 'MessagesController@botProcess');
    Route::get('/suggest', 'MessagesController@getSuggest');
//    Route::post('/bot', 'MessagesController@botProcess');

});

Route::group(['prefix'=>'/messages/suggest', 'middleware' => 'auth'], function() {
    Route::get('/', 'MessagesController@getSuggest');
    Route::get('/{id}', 'MessagesController@getSuggest')->where('id','[0-9]+');
    Route::post('/{id}', 'MessagesController@saveSuggest')->where('id','[0-9]+');

});
Route::group(['prefix' => '/messages/user','middleware'=>'auth'], function () {
    Route::get('/', 'MessagesController@getMessageUser');
    Route::post('/', 'MessagesController@saveMessageUser');
    Route::get('/online-recent', 'MessagesController@getMessageUnread');
});
