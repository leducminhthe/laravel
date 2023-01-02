<?php

Route::group(['prefix' => '/offline/scorm', 'middleware' => ['quiz.secondary','auth']], function () {
    Route::get('/lesson/{id}/{activity_id}/{activity_type}/{lesson}', 'Frontend\ScormController@index')->name('module.offline.scorm')
    ->where('id', '[0-9]+')
    ->where('activity_id', '[0-9]+');

    Route::post('/{id}/{activity_id}/{activity_type}/play', 'Frontend\ScormController@play')->name('module.offline.scorm.play')->where('id', '[0-9]+')->where('activity_id', '[0-9]+')->where('activity_type','[0-9]+');

    Route::get('/{id}/{activity_id}/{attempt_id}/player', 'Frontend\ScormController@player')->name('module.offline.scorm.player')->where('id', '[0-9]+')->where('activity_id', '[0-9]+')->where('attempt_id', '[0-9]+');

    Route::get('/attempts/{activity_id}', 'Frontend\ScormController@getDataAttempt')->name('module.offline.attempts')->where('activity_id', '[0-9]+');

    Route::get('/redirect', 'Frontend\ScormController@redirect')->name('module.offline.scorm.player.redirect');

    Route::match(['get', 'post'], '/check-net', 'Frontend\ScormController@checkNet')->name('module.offline.scorm.player.checknet');

    Route::post('/save', 'Frontend\ScormController@save')->name('module.offline.scorm.player.save');

});
Route::group(['prefix' => '/offline/xapi', 'middleware' => 'quiz.secondary'], function () {
    Route::get('/{id}/{activity_id}/{attempt_id}/player', 'Frontend\XapiController@player')->name('module.offline.xapi.player')->where('id', '[0-9]+')->where('activity_id', '[0-9]+')->where('attempt_id', '[0-9]+');
    Route::get('/redirect', 'Frontend\XapiController@redirect')->name('module.offline.xapi.player.redirect');
});
