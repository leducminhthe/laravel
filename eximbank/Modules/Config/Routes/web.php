<?php

Route::group(['prefix' => '/admin-cp', 'middleware' => 'auth'], function() {
    Route::get('/config-email-list', 'ConfigController@index')->name('backend.config.email.index')
    ->middleware('permission:config-email');

    Route::get('/config-email-getdata', 'ConfigController@index')->name('backend.config.email.getdata')
    ->middleware('permission:config-email');

    Route::post('/config-email-remove', 'ConfigController@remove')->name('backend.config.email.remove')
    ->middleware('permission:config-email');

    Route::post('/config-refer/save', 'ConfigController@saveRefer')->name('backend.config.refer.save')
    ->middleware('permission:config-point-refer-save');

    Route::get('/config-email-create', 'ConfigController@formEmail')->name('backend.config.email.create')
    ->middleware('permission:config-email');

    Route::get('/config-email-edit/{id}', 'ConfigController@formEmail')->name('backend.config.email.edit')
    ->where('id','[0-9]+')
    ->middleware('permission:config-email');

    Route::post('/config-email', 'ConfigController@saveEmail')->name('backend.config.email.save')
    ->middleware('permission:config-email-save');

    Route::post('/config-email/send-test', 'ConfigController@testSendMail')->name('backend.config.email.test')
    ->middleware('permission:config-email-save');
});
