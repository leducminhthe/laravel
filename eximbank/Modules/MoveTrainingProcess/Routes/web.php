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

Route::group(['prefix'=>'/admin-cp/movetrainingprocess','middleware' => 'auth'], function() {
    Route::get('/', 'MoveTrainingProcessController@index')->name('module.movetrainingprocess.index')
    ->middleware('permission:movetrainingprocess');

    Route::get('/getData', 'MoveTrainingProcessController@getData')->name('module.movetrainingprocess.getData')
    ->middleware('permission:movetrainingprocess');

    Route::get('/edit/{user_id}/getData', 'MoveTrainingProcessController@getTrainingProcessUser')->name('module.movetrainingprocess.user.getData')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:movetrainingprocess');

    Route::get('/create', 'MoveTrainingProcessController@create')->name('module.movetrainingprocess.create')
    ->middleware('permission:movetrainingprocess-create');

    Route::get('/edit/{user_id}', 'MoveTrainingProcessController@edit')->name('module.movetrainingprocess.edit')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:movetrainingprocess-edit');

    Route::post('/update/{id}', 'MoveTrainingProcessController@update')->name('module.movetrainingprocess.update')
    ->where('id', '[0-9]+')
    ->middleware('permission:movetrainingprocess-edit');

    Route::post('/store', 'MoveTrainingProcessController@store')->name('module.movetrainingprocess.store')
    ->middleware('permission:movetrainingprocess');

    Route::post('/remove', 'MoveTrainingProcessController@destroy')->name('module.movetrainingprocess.remove')
    ->middleware('permission:movetrainingprocess-delete');

    Route::post('/submit', 'MoveTrainingProcessController@submitMoveTrainingProcess')->name('module.movetrainingprocess.submit')
    ->middleware('permission:movetrainingprocess');

    Route::post('/modal', 'MoveTrainingProcessController@showModalMoveTrainingProcess')->name('module.movetrainingprocess.modal')
    ->middleware('permission:movetrainingprocess');

    Route::get('/training-process-old/getdata/{user_id}', 'MoveTrainingProcessController@getTrainingProcessOld')->name('module.movetrainingprocess.training_process_old.getData')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:movetrainingprocess');

    Route::post('/approved', 'MoveTrainingProcessController@approved')->name('module.movetrainingprocess.approved')
    ->middleware('permission:movetrainingprocess');

    Route::get('/logs', 'MoveTrainingProcessController@showLogs')->name('module.movetrainingprocess.logs')
    ->middleware('permission:movetrainingprocess-watch-log');

    Route::get('/getlogs', 'MoveTrainingProcessController@getLogs')->name('module.movetrainingprocess.logs.getData')
    ->middleware('permission:movetrainingprocess-watch-log');
});
