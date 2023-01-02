<?php

Route::group(['prefix'=>'/admin-cp/splitsubject','middleware' => 'auth'], function() {
    Route::get('/', 'SplitSubjectController@index')->name('module.splitsubject.index')
    ->middleware('permission:splitsubject');

    Route::get('/getData', 'SplitSubjectController@getData')->name('module.splitsubject.getData')
    ->middleware('permission:splitsubject');

    Route::get('/create', 'SplitSubjectController@create')->name('module.splitsubject.create')
    ->middleware('permission:splitsubject-create');

    Route::get('/edit/{id}', 'SplitSubjectController@edit')->name('module.splitsubject.edit')->where('id', '[0-9]+')
    ->middleware('permission:splitsubject-edit');

    Route::post('/update/{id}', 'SplitSubjectController@update')->name('module.splitsubject.update')->where('id', '[0-9]+')
    ->middleware('permission:splitsubject-edit');

    Route::post('/store', 'SplitSubjectController@store')->name('module.splitsubject.store')
    ->middleware('permission:splitsubject');

    Route::post('/remove', 'SplitSubjectController@destroy')->name('module.splitsubject.remove')
    ->middleware('permission:splitsubject-delete');

    Route::post('/approve', 'SplitSubjectController@approve')->name('module.splitsubject.approve')
    ->middleware('permission:splitsubject-approved');

    Route::post('/import', 'SplitSubjectController@import')->name('module.splitsubject.import')
    ->middleware('permission:splitsubject');

    Route::get('/logs', 'SplitSubjectController@showLogs')->name('module.splitsubject.logs')
    ->middleware('permission:splitsubject');

    Route::get('/getlogs', 'SplitSubjectController@getLogs')->name('module.splitsubject.logs.getData')
    ->middleware('permission:splitsubject');
});
