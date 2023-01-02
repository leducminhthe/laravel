<?php

Route::group(['prefix' => '/admin-cp/category/userpoint/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\UserPointController@index')->name('module.userpoint.manager')->middleware('permission:category-userpoint-item');

    Route::post('/edit', 'Backend\UserPointController@form')->name('module.userpoint.edit')->where('id', '[0-9]+')->middleware('permission:category-userpoint-item-edit');

    Route::get('/getdata', 'Backend\UserPointController@getData')->name('module.userpoint.getdata')->middleware('permission:category-userpoint-item');

    Route::post('/save', 'Backend\UserPointController@save')->name('module.userpoint.save')->middleware('permission:category-userpoint-item');

    Route::post('/remove', 'Backend\UserPointController@remove')->name('module.userpoint.remove');
});


Route::group(['prefix' => '/userpoint', 'middleware' => 'auth'], function() {
    Route::get('/history', 'UserPointController@history')->name('module.frontend.userpoint.history')->middleware('pagespeed');
    Route::get('/datahistory', 'UserPointController@getDataHistory')->name('module.frontend.userpoint.datahistory');
});

Route::group(['prefix' => '/admin-cp/category/userpoint-reward-login', 'middleware' => 'auth'], function() {
    Route::post('/edit', 'Backend\UserPointRewardLoginController@form')->name('module.userpoint.reward_login.edit');

    Route::get('/getdata', 'Backend\UserPointRewardLoginController@getData')->name('module.userpoint.reward_login.getdata');

    Route::post('/save', 'Backend\UserPointRewardLoginController@save')->name('module.userpoint.reward_login.save');

    Route::post('/remove', 'Backend\UserPointRewardLoginController@remove')->name('module.userpoint.reward_login.remove');
});
