<?php

Route::group(['prefix'=>'/admin-cp/subjectcomplete','middleware' => 'auth'], function() {
    Route::get('/', 'SubjectCompleteController@index')->name('module.subjectcomplete.index')
    ->middleware('permission:subjectcomplete');

    Route::get('/getData', 'SubjectCompleteController@getData')->name('module.subjectcomplete.getData')
    ->middleware('permission:subjectcomplete');

    Route::get('/edit/{user_id}/getData', 'SubjectCompleteController@getTrainingProcessUser')->name('module.subjectcomplete.user.getData')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:subjectcomplete');

    Route::get('/create', 'SubjectCompleteController@create')->name('module.subjectcomplete.create')
    ->middleware('permission:subjectcomplete-create');

    Route::get('/edit/{user_id}', 'SubjectCompleteController@edit')->name('module.subjectcomplete.edit')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:subjectcomplete-edit');

    Route::post('/update/{id}', 'SubjectCompleteController@update')->name('module.subjectcomplete.update')
    ->where('id', '[0-9]+')
    ->middleware('permission:subjectcomplete-edit');

    Route::post('/store', 'SubjectCompleteController@store')->name('module.subjectcomplete.store')
    ->middleware('permission:subjectcomplete');

    Route::get('/logs', 'SubjectCompleteController@showLogs')->name('module.subjectcomplete.logs')
    ->middleware('permission:subjectcomplete');

    Route::get('/getlogs', 'SubjectCompleteController@getLogs')->name('module.subjectcomplete.logs.getData')
    ->middleware('permission:subjectcomplete');
    
    Route::get('/approve', 'SubjectCompleteController@approve')->name('module.subjectcomplete.approve')
    ->middleware('permission:subjectcomplete-approved');

    Route::post('/approved', 'SubjectCompleteController@approved')->name('module.subjectcomplete.approved')
    ->middleware('permission:subjectcomplete-approved');

    Route::get('/approve/getdata', 'SubjectCompleteController@getApprove')->name('module.subjectcomplete.approve.getData')
    ->middleware('permission:subjectcomplete');

    Route::post('/edit/{user_id}/save', 'SubjectCompleteController@store')->name('module.subjectcomplete.user.save')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:subjectcomplete-edit');

    Route::post('/edit/{user_id}/get-modal', 'SubjectCompleteController@showModal')->name('module.subjectcomplete.user.get_modal')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:subjectcomplete');

    Route::post('/import', 'SubjectCompleteController@import')->name('module.subjectcomplete.import')
    ->middleware('permission:subjectcomplete');
});
