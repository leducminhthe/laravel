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

Route::group(['prefix'=>'/admin-cp/model-history','middleware' => 'auth'], function() {
    Route::get('/', 'ModelHistoryController@index')->name('module.modelhistory.index')->middleware('permission:model-history');
    Route::get('/getdata', 'ModelHistoryController@getdata')->name('module.modelhistory.getdata')->middleware('permission:model-history');
});
