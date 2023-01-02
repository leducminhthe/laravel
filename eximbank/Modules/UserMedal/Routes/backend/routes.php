<?php

Route::group(['prefix' => '/admin-cp/usermedal', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\UserMedalController@index')->name('module.usermedal.list')->middleware('permission:category-usermedal');

    Route::get('/getdata', 'Backend\UserMedalController@getData')->name('module.usermedal.getdata')->middleware('permission:category-usermedal');

    Route::get('/create', 'Backend\UserMedalController@form')->name('module.usermedal.create')->middleware('permission:category-usermedal-create');

    Route::get('/edit/{id}', 'Backend\UserMedalController@form')->name('module.usermedal.edit')->where('id', '[0-9]+')->middleware('permission:category-usermedal-edit');

    Route::post('/save', 'Backend\UserMedalController@save')->name('module.usermedal.save')->middleware('permission:category-usermedal');

    Route::post('/remove', 'Backend\UserMedalController@remove')->name('module.usermedal.remove')->middleware('permission:category-usermedal-delete');

    Route::get('/getdata-child/{id}', 'Backend\UserMedalController@getDataChild')->name('module.usermedal.getdata_child')->where('id', '[0-9]+');

    Route::post('/edit-child', 'Backend\UserMedalController@editPromotionChild')->name('module.usermedal.edit_promotion_child');
});

Route::group(['prefix' => '/admin-cp/usermedal-setting', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\UserMedalSettingsController@index')->name('module.usermedal-setting.list')->middleware('permission:usermedal-setting');

    Route::get('/getdata', 'Backend\UserMedalSettingsController@getData')->name('module.usermedal-setting.getdata')->middleware('permission:usermedal-setting');

    Route::get('/create', 'Backend\UserMedalSettingsController@form')->name('module.usermedal-setting.create')->middleware('permission:usermedal-setting-create');

    Route::get('/edit/{id}', 'Backend\UserMedalSettingsController@form')->name('module.usermedal-setting.edit')->where('id', '[0-9]+')->middleware('permission:usermedal-setting-edit');

    Route::post('/save', 'Backend\UserMedalSettingsController@save')->name('module.usermedal-setting.save')->middleware('permission:usermedal-setting-edit');

    Route::post('/{type}/save-item/{form}', 'Backend\UserMedalSettingsController@saveItems')->name('module.usermedal-setting.save-item');

    Route::post('/remove-item', 'Backend\UserMedalSettingsController@removeItem')->name('module.usermedal-setting.remove')->middleware('permission:usermedal-setting-delete');

    Route::post('/edit-item', 'Backend\UserMedalSettingsController@editItem')->name('module.usermedal-setting.edit-item')->middleware('permission:usermedal-setting-edit');

    Route::post('/load-courses', 'Backend\UserMedalSettingsController@loadCourses')->name('module.usermedal-setting.load-courses');

    Route::get('/load-quiz', 'Backend\UserMedalSettingsController@loadQuiz')->name('module.usermedal-setting.load-quiz');

    Route::post('/edit/{id}/save-object', 'Backend\UserMedalSettingsController@saveObject')->name('module.usermedal-setting.save_object')->where('id', '[0-9]+');
    Route::get('/edit/{id}/get-object', 'Backend\UserMedalSettingsController@getObject')->name('module.usermedal-setting.get_object')->where('id', '[0-9]+');
    Route::get('/edit/{id}/get-user-object', 'Backend\UserMedalSettingsController@getUserObject')->name('module.usermedal-setting.get_user_object')->where('id', '[0-9]+');
    Route::post('/edit/{id}/remove-object', 'Backend\UserMedalSettingsController@removeObject')->name('module.usermedal-setting.remove_object')->where('id', '[0-9]+');
    Route::post('/edit/{id}/import-object', 'Backend\UserMedalSettingsController@importObject')->name('module.usermedal-setting.import_object')->where('id', '[0-9]+');
    Route::post('/edit/{id}/check-unit-child', 'Backend\UserMedalSettingsController@getChild')->name('module.usermedal-setting.get_child')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-tree-child', 'Backend\UserMedalSettingsController@getTreeChild')
        ->name('module.usermedal-setting.get_tree_child')
        ->where('id', '[0-9]+');

    Route::post('/remove', 'Backend\UserMedalSettingsController@remove')->name('module.usermedal-setting.remove2');

});
?>
