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

Route::group(['prefix' => '/admin-cp/botconfig', 'middleware' => 'auth'], function() {
    Route::get('/', 'BotConfigController@index')->name('module.botconfig') ;
    Route::get('/getdata', 'BotConfigController@index')->name('module.botconfig.getdata') ;
    Route::get('/{id}', 'BotConfigController@edit')->name('module.botconfig.edit')->where('id','[0-9]+');;
    Route::post('/', 'BotConfigController@store')->name('module.botconfig.post') ;
    Route::delete('/', 'BotConfigController@destroy')->name('module.botconfig.delete') ;
});
Route::group(['prefix' => '/admin-cp/botconfig/suggest', 'middleware' => 'auth'], function() {
    Route::get('/', 'BotConfigSuggestController@index')->name('module.botconfig.suggest') ;
    Route::get('/getdata', 'BotConfigSuggestController@index')->name('module.botconfig.suggest.getdata') ;
    Route::get('/{id}', 'BotConfigSuggestController@edit')->name('module.botconfig.suggest.edit')->where('id','[0-9]+');;
    Route::post('/', 'BotConfigSuggestController@store')->name('module.botconfig.suggest.post') ;
    Route::delete('/', 'BotConfigSuggestController@destroy')->name('module.botconfig.suggest.delete') ;
});
