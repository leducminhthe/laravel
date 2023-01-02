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

Route::group(['prefix'=>'/admin-cp/mergesubject','middleware' => ['auth','permission:mergesubject']], function() {
    Route::get('/', 'MergeSubjectController@index')->name('module.mergesubject.index')
    ->middleware('permission:mergesubject');

    Route::get('/getData', 'MergeSubjectController@getData')->name('module.mergesubject.getData')
    ->middleware('permission:mergesubject');

    Route::get('/create', 'MergeSubjectController@create')->name('module.mergesubject.create')
    ->middleware('permission:mergesubject-create');

    Route::get('/edit/{id}', 'MergeSubjectController@edit')->name('module.mergesubject.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:mergesubject-edit');

    Route::post('/update/{id}', 'MergeSubjectController@update')->name('module.mergesubject.update')
    ->where('id', '[0-9]+')
    ->middleware('permission:mergesubject');

    Route::post('/store', 'MergeSubjectController@store')->name('module.mergesubject.store')
    ->middleware('permission:mergesubject');

    Route::post('/remove', 'MergeSubjectController@destroy')->name('module.mergesubject.remove')
    ->middleware('permission:mergesubject-delete');

    Route::post('/approve', 'MergeSubjectController@approve')->name('module.mergesubject.approve')
    ->middleware('permission:mergesubject');

    Route::post('/import', 'MergeSubjectController@import')->name('module.mergesubject.import')
    ->middleware('permission:mergesubject');

    Route::get('/logs', 'MergeSubjectController@showLogs')->name('module.mergesubject.logs')
    ->middleware('permission:mergesubject');

    Route::get('/getlogs', 'MergeSubjectController@getLogs')->name('module.mergesubject.logs.getData')
    ->middleware('permission:mergesubject');
});
