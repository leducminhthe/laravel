<?php

Route::group(['prefix' => '/coaching-teacher', 'middleware' => 'auth'], function() {
    Route::get('/', 'CoachingFrontendController@index')->name('module.coaching.frontend')->middleware('pagespeed');
    
    //Đăng ký thành GV
    Route::post('/register-teacher', 'CoachingFrontendController@registerTeacher')->name('module.coaching.frontend.register_teacher');

    //Đăng ký/Tạo nội dung
    Route::get('/create-content-skill', 'CoachingFrontendController@form')->name('module.coaching.frontend.create_content_skill');

    //Chỉnh sửa nội dung đã tạo/đăng ký
    Route::get('/edit-content-skill/{id}', 'CoachingFrontendController@form')->name('module.coaching.frontend.edit_content_skill');

    //Lưu nội dung/kỹ năng
    Route::post('/save-content-skill', 'CoachingFrontendController@saveContentSkill')->name('module.coaching.frontend.save_content_skill');

    //Lịch sử kèm cặp
    Route::get('/history', 'CoachingFrontendController@history')->name('module.coaching.frontend.history')->middleware('pagespeed');
});

Route::group(['prefix' => '/admin-cp/coaching-teacher', 'middleware' => 'auth'], function() {
    Route::get('/', 'CoachingBackendController@index')->name('module.coaching.backend')
        ->middleware('permission:coaching-teacher');

    Route::get('/getdata', 'CoachingBackendController@getData')->name('module.coaching.backend.getdata')
        ->middleware('permission:coaching-teacher');

    Route::post('/update_status', 'CoachingBackendController@updateStatus')->name('module.coaching.backend.update_status')
        ->middleware('permission:coaching-teacher');
});

Route::group(['prefix' => '/admin-cp/coaching-group', 'middleware' => 'auth'], function() {
    Route::get('/', 'CoachingGroupController@index')->name('module.coaching_group')
        ->middleware('permission:coaching-group');

    Route::get('/getdata', 'CoachingGroupController@getData')->name('module.coaching_group.getdata')
        ->middleware('permission:coaching-group');

    Route::post('/edit', 'CoachingGroupController@form')->name('module.coaching_group.edit')
        ->where('id', '[0-9]+')
        ->middleware('permission:coaching-group-edit');

    Route::post('/save', 'CoachingGroupController@save')->name('module.coaching_group.save')
        ->middleware('permission:coaching-group-create|coaching-group-edit');

    Route::post('/remove', 'CoachingGroupController@remove')->name('module.coaching_group.remove')
        ->middleware('permission:coaching-group-remove');

    Route::post('/update_status', 'CoachingGroupController@updateStatus')->name('module.coaching_group.update_status')
        ->middleware('permission:coaching-group');
});

Route::group(['prefix' => '/admin-cp/coaching-mentor-method', 'middleware' => 'auth'], function() {
    Route::get('/', 'CoachingMentorMethodController@index')->name('module.coaching_mentor_method')
        ->middleware('permission:coaching-mentor-method');

    Route::get('/getdata', 'CoachingMentorMethodController@getData')->name('module.coaching_mentor_method.getdata')
        ->middleware('permission:coaching-mentor-method');

    Route::post('/edit', 'CoachingMentorMethodController@form')->name('module.coaching_mentor_method.edit')
        ->where('id', '[0-9]+')
        ->middleware('permission:coaching-mentor-method-edit');

    Route::post('/save', 'CoachingMentorMethodController@save')->name('module.coaching_mentor_method.save')
        ->middleware('permission:coaching-mentor-method-create|coaching-mentor-method-edit');

    Route::post('/remove', 'CoachingMentorMethodController@remove')->name('module.coaching_mentor_method.remove')
        ->middleware('permission:coaching-mentor-method-remove');

    Route::post('/update_status', 'CoachingMentorMethodController@updateStatus')->name('module.coaching_mentor_method.update_status')
        ->middleware('permission:coaching-mentor-method');
});