<?php

Route::group(['prefix' => '/admin-cp/online', 'middleware' => 'quiz.secondary'], function() {
    Route::get('/edit/{id}/get-object', 'BackendController@getObject')->name('module.online.get_object')->where('id', '[0-9]+')->middleware('permission:online-course');
});

Route::group(['prefix' => '/admin-cp/online', 'middleware' => ['auth','permission:online-course']], function() {
    Route::get('/', 'BackendController@index')->name('module.online.management')->middleware('permission:online-course');

    Route::get('/getdata', 'BackendController@getData')->name('module.online.backend.getdata')->middleware('permission:online-course');

    Route::get('/edit/{id}', 'BackendController@form')->name('module.online.edit')->where('id', '[0-9]+')->middleware('permission:online-course-edit');

    Route::get('/create', 'BackendController@form')->name('module.online.create')->middleware('permission:online-course-create');

    Route::post('/save', 'BackendController@save')->name('module.online.save')->middleware('permission:online-course-create|online-course-edit');

    Route::post('/save/tutorial', 'BackendController@saveTutorial')->name('module.online.save_tutorial')->middleware('permission:online-course-create|online-course-edit');

    Route::post('/remove', 'BackendController@remove')->name('module.online.remove')->middleware('permission:online-course-delete');

    Route::post('/approve', 'BackendController@approve')->name('module.online.approve')->middleware('permission:online-course-approve');

    Route::post('/send-mail-approve', 'BackendController@sendMailApprove')->name('module.online.send_mail_approve');

    Route::post('/send-mail-change', 'BackendController@sendMailChange')->name('module.online.send_mail_change');

    Route::post('/ajax-get-course-code', 'BackendController@ajaxGetCourseCode')->name('module.online.ajax_get_course_code');

    Route::post('/ajax-get-subject', 'BackendController@ajaxGetSubject')->name('module.online.ajax_get_subject');

    Route::post('/ajax-isopen-publish', 'BackendController@ajaxIsopenPublish')->name('module.online.ajax_isopen_publish');

    Route::post('/import-register-multiple-course', 'BackendController@importRegisterMultipleCourse')->name('module.online.import_register_multiple_course');

    Route::post('/import-result-multiple-course', 'BackendController@importResultMultipleCourse')->name('module.online.import_result_multiple_course');

    Route::post('/edit/{id}/save-object', 'BackendController@saveObject')->name('module.online.save_object')->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'BackendController@removeObject')->name('module.online.remove_object')->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-cost', 'BackendController@saveCost')->name('module.online.save_cost')->where('id', '[0-9]+');

    Route::post('/edit/{id}/modal-add-activity/{type}', 'ActivityController@modalAddActivity')->name('module.online.modal_add_activity')->where('id', '[0-9]+');

    Route::post('/edit/{id}/modal-activity', 'ActivityController@modalActivity')->name('module.online.modal_activity')->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-condition', 'BackendController@saveCondition')->name('module.online.save_condition')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-condition', 'BackendController@getCondition')->name('module.online.get_condition')->where('id', '[0-9]+');

    Route::get('/edit/{id}/go-add-scorm', 'BackendController@goAddScorm')->name('module.online.go_add_scorm')->where('id', '[0-9]+');

    Route::post('/edit/{id}/check-unit-child', 'BackendController@getChild')->name('module.online.get_child')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-tree-child', 'BackendController@getTreeChild')->name('module.online.get_tree_child')->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-setting-score-percent', 'BackendController@saveSettingScorePercent')->name('module.online.save_setting_score_percent')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-setting-percent', 'BackendController@getSettingPercent')->name('module.online.get_setting_percent')->where('id', '[0-9]+');

    //update chạy cron hoàn thành khoá
    Route::post('/{id}/update-result-by-condition', 'BackendController@updateResultByCondition')->name('module.online.update_result_by_condition')->where('id', '[0-9]+');

    //history
    Route::get('/history/{id}/getdata', 'BackendController@getDataHistory')->name('module.online.history.getdata')->where('id', '[0-9]+');

    //quản lý upload file
    Route::post('/uploadfile', 'BackendController@uploadfile')->name('module.online.uploadfile');

    //Tài liệu học tập
    Route::post('/upload-document/{course_id}', 'BackendController@uploadDocument')->name('module.online.upload_document')->where('course_id', '[0-9]+');
    Route::get('/get-data-document/{course_id}', 'BackendController@getDataDocument')->name('module.online.get_data_document')->where('course_id', '[0-9]+');
    Route::post('/remove-document', 'BackendController@removeDocument')->name('module.online.remove_document');

    //ẢNH ĐẠI DIỆN HOẠT ĐỘNG
    Route::post('/image-activity-save/{id}/{type}', 'BackendController@imageActivitySave')->name('module.online.image_activity.save');

    //Thư viện file
    Route::get('/get-data-library-file/{course_id}', 'BackendController@getDataLibraryFile')->name('module.online.get_data_library_file');
    Route::post('/library-file-remove', 'BackendController@removeLibraryFile')->name('module.online.library_file_remove');

    //HỌC VIÊN GHI CHÉP / ĐÁNH GIÁ
    Route::get('/get-user-note-evaluate/{course_id}', 'BackendController@getUserNoteEvaluate')->name('module.online.get_user_note_evaluate');
    Route::get('/get-content-evaluate/{id}/{course_id}', 'BackendController@getContentEvaluate')->name('module.online.get_content_evaluate');
    Route::get('/view-user-note-evaluate/{id}/{course_id}/{type}', 'BackendController@viewUserNoteEvaluate')->name('module.online.view_user_note_evaluate');
    Route::post('/content-evaluate-remove/{id}/{course_id}/', 'BackendController@removeContentEvaluate')->name('module.online.content_evaluate_remove');
    Route::get('/export/{id}/{course_id}/{user_type}', 'BackendController@export')->name('module.online.export');

    // HỌC VIÊN HỎI ĐÁP
    Route::get('/get-user-ask-answer/{course_id}', 'BackendController@getUserAskAnswer')->name('module.online.get_user_ask_answer');
    Route::post('/save-answer', 'BackendController@saveAnswer')
        ->name('module.online.save_answer')
        ->where('id', '[0-9]+');
    Route::post('/ajax-isopen-status', 'BackendController@ajaxIsopenStatus')->name('module.online.ajax_isopen_status');

    // BÀI HỌC
    Route::post('/save-lesson/{course_id}/{type}', 'BackendController@saveLesson')
        ->name('module.online.save_lesson')
        ->where('id', '[0-9]+');
    Route::post('/remove-lesson', 'BackendController@removeLesson')->name('module.online.remove_lesson')->where('id', '[0-9]+');

    Route::post('/lock', 'BackendController@lockCourse')->name('module.online.lock');

    // SAO CHÉP KHÓA HỌC
    Route::post('/copy', 'BackendController@copy')->name('module.online.copy');
//    Route::get('/get-approved-step/{model_id}', 'BackendController@getApprovedStep')->name('module.online.get_approved_step')->where('model_id','[0-9]+');
//    Route::post('/modal-note-approved', 'BackendController@showModalNoteApproved')->name('module.online.modal_note_approved');

    // ĐÁNH GIÁ KHÓA HỌC
    Route::post('/save-ratting-course/{course_id}', 'BackendController@saveRattingCourse')->where('course_id', '[0-9]+')->name('module.online.save_ratting_course');

    Route::post('/modal-info/{course_id}', 'BackendController@modalInfo')->where('course_id', '[0-9]+')->name('module.online.modal_info');

    Route::get('/object/{id}', 'BackendController@form')->name('module.online.edit_object')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/tutorial/{id}', 'BackendController@form')->name('module.online.edit_tutorial')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/cost/{id}', 'BackendController@form')->name('module.online.edit_cost')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/activity-lesson/{id}', 'BackendController@form')->name('module.online.edit_activity_lesson')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/image-activity/{id}', 'BackendController@form')->name('module.online.edit_image_activity')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/condition/{id}', 'BackendController@form')->name('module.online.edit_condition')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/history/{id}', 'BackendController@form')->name('module.online.edit_history')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/libraryFile/{id}', 'BackendController@form')->name('module.online.edit_libraryFile')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/note-evaluate/{id}', 'BackendController@form')->name('module.online.edit_note_evaluate')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/ask-answer/{id}', 'BackendController@form')->name('module.online.edit_ask_answer')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/userpoint/{id}', 'BackendController@form')->name('module.online.edit_userpoint')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/setting_percent/{id}', 'BackendController@form')->name('module.online.edit_setting_percent')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/rating_level/{id}', 'BackendController@form')->name('module.online.edit_rating_level')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/document/{id}', 'BackendController@form')->name('module.online.edit_document')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/activity-lesson/{id}/scorm/{sid}/report', 'BackendController@reportScorm')->name('module.online.scorm.report')->where('id', '[0-9]+')->where('sid', '[0-9]+')->middleware('permission:online-course-edit');
    Route::post('/activity-lesson/{id}/scorm/{sid}/report/delete', 'BackendController@removeAttemptScrom')->name('module.online.scorm.report.remove')->where('id', '[0-9]+')->where('sid', '[0-9]+')->middleware('permission:online-course-edit');

    //BÁO CÁO HOẠT ĐỘNG KHẢO SÁT
    Route::get('/activity-lesson/{id}/survey/{activityId}/report', 'BackendController@reportSurvey')->name('module.online.survey.report')
    ->where('id', '[0-9]+')->where('sid', '[0-9]+')
    ->middleware('permission:online-course-edit');

    Route::get('/export-report-survey/{id}/{activityId}', 'BackendController@exportSurvey')->name('module.online.survey.export_report')
    ->where('id', '[0-9]+')->where('sid', '[0-9]+')
    ->middleware('permission:online-course-edit');

    //Thiết lập tham gia khóa học
    Route::get('/setting_join/{id}', 'BackendController@form')->name('module.online.edit_setting_join')->where('id', '[0-9]+')->middleware('permission:online-course-edit');
    Route::get('/setting_join/{id}/get', 'BackendController@getSettingJoin')->name('module.online.get_setting_join')->where('id', '[0-9]+');
    Route::post('/setting_join/{id}/save', 'BackendController@saveSettingJoin')->name('module.online.save_setting_join')->where('id', '[0-9]+');
    Route::post('/setting_join/{id}/remove', 'BackendController@removeSettingJoin')->name('module.online.remove_setting_join')->where('id', '[0-9]+');
    Route::post('/setting_join/{id}/change-data-register', 'BackendController@changeDateRegister')->name('module.online.setting_join.change_date_register')->where('id', '[0-9]+');
    Route::post('/setting_join/{id}/import', 'BackendController@importSettingJoin')->name('module.online.setting_join.import')->where('id', '[0-9]+');
    Route::post('/run-cron-setting_join/{id}', 'BackendController@runCronSettingJoin')->name('module.online.run_cron_setting_join')->where('id', '[0-9]+');

    // ĐIỀU KIỆN TIÊN QUYẾT
    Route::get('/prerequisite-condition/{id}', 'BackendController@form')->name('module.online.edit.prerequisite_condition')
    ->where('id', '[0-9]+')->middleware('permission:online-course-edit');

    Route::post('/save/prerequisite-condition/{id}', 'BackendController@savePrerequisite')->name('module.online.edit.save_prerequisite_condition')
    ->where('id', '[0-9]+')->middleware('permission:online-course-edit');

    // ĐIỀU KIỆN GHI DANH
    Route::get('/condition-register/{id}', 'BackendController@form')->name('module.online.edit.condition_register')
    ->where('id', '[0-9]+')->middleware('permission:online-course-edit');

    Route::post('/save/condition-register/{id}', 'BackendController@saveConditionRegister')->name('module.online.edit.save_condition_register')
    ->where('id', '[0-9]+')->middleware('permission:online-course-edit');

    // KHÓA HỌC ONLINE DÀNH CHO TẬP TRUNG
    Route::get('/course-for-offline', 'OnlineCourseOfflineController@index')->name('module.online.course_for_offline')
    ->middleware('permission:online-course');

    Route::get('/course-for-offline/getdata', 'OnlineCourseOfflineController@getdata')->name('module.online.course_for_offline.getdata')
    ->middleware('permission:online-course');

    Route::post('/course-for-offline/remove', 'OnlineCourseOfflineController@remove')->name('module.online.course_for_offline.remove')
    ->middleware('permission:online-course-delete');

    Route::get('/course-for-offline/create', 'OnlineCourseOfflineController@form')->name('module.online.course_for_offline.create')
    ->middleware('permission:online-course-create');

    Route::post('/course-for-offline/save', 'OnlineCourseOfflineController@save')->name('module.online.course_for_offline.save')
    ->middleware('permission:online-course-create|online-course-edit');

    Route::post('/course-for-offline/ajax-get-subject', 'OnlineCourseOfflineController@ajaxGetSubject')->name('module.online.course_for_offline.ajax_get_subject');

    Route::post('/course-for-offline/ajax-get-course-code', 'OnlineCourseOfflineController@ajaxGetCourseCode')->name('module.online.course_for_offline.ajax_get_course_code');

    Route::get('/course-for-offline/edit/{id}', 'OnlineCourseOfflineController@form')->name('module.online.course_for_offline.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:online-course-edit');

    Route::get('/course-for-offline/activity-lesson/{id}', 'OnlineCourseOfflineController@form')->name('module.online.course_for_offline.edit_activity_lesson')
    ->where('id', '[0-9]+')
    ->middleware('permission:online-course-edit');

    Route::get('/course-for-offline/image-activity/{id}', 'OnlineCourseOfflineController@form')->name('module.online.course_for_offline.edit_image_activity')
    ->where('id', '[0-9]+')->middleware('permission:online-course-edit');

    Route::get('/course-for-offline/condition/{id}', 'OnlineCourseOfflineController@form')->name('module.online.course_for_offline.edit_condition')
    ->where('id', '[0-9]+')->middleware('permission:online-course-edit');

    Route::get('/course-for-offline/userpoint/{id}', 'OnlineCourseOfflineController@form')->name('module.online.course_for_offline.edit_userpoint')
    ->where('id', '[0-9]+')->middleware('permission:online-course-edit');

    Route::get('/course-for-offline/setting-percent/{id}', 'OnlineCourseOfflineController@form')->name('module.online.course_for_offline.edit_setting_percent')
    ->where('id', '[0-9]+')->middleware('permission:online-course-edit');

    Route::get('/course-for-offline/quiz/{id}', 'OnlineCourseOfflineController@form')->name('module.online.course_for_offline.quiz')
    ->where('id', '[0-9]+')->middleware('permission:online-course-edit');
});

Route::group(['prefix' => '/admin-cp/online/activity/{id}', 'middleware' => ['auth','permission:online-course']], function() {
    Route::get('/load-data/{func}', 'ActivityController@loadData')->name('module.online.activity.loaddata')->where('id', '[0-9]+');

    Route::post('/update-numorder', 'ActivityController@updateNumOrder')->name('module.online.activity.update_numorder')->where('id', '[0-9]+');

    Route::post('/remove', 'ActivityController@remove')->name('module.online.activity.remove')->where('id', '[0-9]+');

    Route::post('/update-status-activity', 'ActivityController@updateStatusActivity')->name('module.online.activity.update_status_activity')->where('id', '[0-9]+');

    Route::post('/save/{activity}/{type}', 'ActivityController@saveActivity')->name('module.online.activity.save')->where('id', '[0-9]+')->where('activity', '[0-9]+');

    Route::post('/edit-name', 'ActivityController@editNameActivity')->name('module.online.activity.edit_name')->where('id', '[0-9]+')->where('activity', '[0-9]+');

    Route::post('/update-lesson', 'ActivityController@updateLesson')->name('module.online.activity.update_lesson')->where('id', '[0-9]+');

    Route::post('/search', 'ActivityController@searchActivity')->name('module.online.activity.search')->where('id', '[0-9]+');

    Route::post('/edit-lesson-name', 'ActivityController@editLessonNameActivity')->name('module.online.activity.edit_lesson_name')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/online/register/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'RegisterController@index')->name('module.online.register')->where('id', '[0-9]+')->middleware('permission:online-course-register');

    Route::get('/getdata', 'RegisterController@getData')->name('module.online.register.getdata')->middleware('permission:online-course-register');

    Route::get('/getDataNotRegister', 'RegisterController@getDataNotRegister')->name('module.online.register.getDataNotRegister')->where('id', '[0-9]+')->middleware('permission:online-course-register');

    Route::get('/create', 'RegisterController@form')->name('module.online.register.create')->where('id', '[0-9]+')->middleware('permission:online-course-register-create');

    Route::post('/save', 'RegisterController@save')->name('module.online.register.save')->where('id', '[0-9]+')->middleware('permission:online-course-register-create|online-course-register-edit');

    Route::post('/remove', 'RegisterController@remove')->name('module.online.register.remove')->where('id', '[0-9]+')->middleware('permission:online-course-register-delete');

    Route::post('/import-register', 'RegisterController@importRegister')->name('module.online.register.import_register')->where('id', '[0-9]+')->middleware('permission:online-course-register');

    Route::post('/approve', 'RegisterController@approve')->name('module.online.register.approve')->where('id', '[0-9]+')->middleware('permission:online-course-register-approve');

    Route::post('/add-to-quiz', 'RegisterController@addToQuiz')->name('module.online.register.add_to_quiz')->where('id', '[0-9]+');

    Route::post('/invite-user-register', 'RegisterController@inviteUserRegister')->name('module.online.register.invite_user')->where('id', '[0-9]+');

    Route::get('/invite-user-register/getdata', 'RegisterController@getDataInviteUserRegister')->name('module.online.register.getdata.invite_user')->where('id', '[0-9]+');

    Route::post('/invite-user-register/remove', 'RegisterController@removeInviteUserRegister')->name('module.online.register.remove.invite_user')->where('id', '[0-9]+');

    Route::post('/send-mail-user-registed', 'RegisterController@sendMailUserRegisted')->name('module.online.register.send_mail_user_registed')->where('id', '[0-9]+');

    Route::get('/export-register', 'RegisterController@exportRegister')->name('module.online.register.export_register')->where('id', '[0-9]+');

    Route::post('/modal-info/{register_id}', 'RegisterController@modalInfo')->where('id', '[0-9]+')->name('module.online.register.modal_info');
});

Route::group(['prefix' => '/admin-cp/online/register-secondary/{id}', 'middleware' => ['auth','permission:online-course']], function() {
    Route::get('/', 'RegisterSecondaryController@index')
        ->name('module.online.register_secondary')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register');

    Route::get('/getdata', 'RegisterSecondaryController@getData')
        ->name('module.online.register_secondary.getdata')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register');

    Route::get('/getDataNotRegister', 'RegisterSecondaryController@getDataNotRegister')
        ->name('module.online.register_secondary.getDataNotRegister')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register');

    Route::get('/create', 'RegisterSecondaryController@form')
        ->name('module.online.register_secondary.create')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register-create');

    Route::post('/save', 'RegisterSecondaryController@save')
        ->name('module.online.register_secondary.save')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register-create|online-course-register-edit');

    Route::post('/remove', 'RegisterSecondaryController@remove')
        ->name('module.online.register_secondary.remove')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register-delete');

    Route::post('/import-register', 'RegisterSecondaryController@importRegister')
        ->name('module.online.register_secondary.import_register')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register');

    Route::post('/add-to-quiz', 'RegisterSecondaryController@addToQuiz')->name('module.online.register_secondary.add_to_quiz')->where('id', '[0-9]+');

    Route::post('/send-mail-user-registed', 'RegisterSecondaryController@sendMailUserRegisted')
        ->name('module.online.register_secondary.send_mail_user_registed')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/online/edit/{id}', 'middleware' => 'auth'], function() {
    Route::get('/result', 'ResultController@index')
        ->name('module.online.result')
        ->where('id', '[0-9]+')->middleware('permission:online-course-result');

    Route::get('/get-result', 'ResultController@getData')
        ->name('module.online.get_result')
        ->where('id', '[0-9]+')->middleware('permission:online-course-result');

    Route::post('/update-activity-complete', 'ResultController@updateActivityComplete')
        ->name('module.online.result.update_activity_complete')
        ->where('id', '[0-9]+')->middleware('permission:online-course-result');

    Route::get('/export-result', 'ResultController@exportResult')->name('module.online.export_result');

    Route::get('/result/{user_id}/view_history_learning', 'ResultController@viewHistoryLearning')
        ->name('module.online.result.view_history_learning')
        ->where('id', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->middleware('permission:online-course-result');
});

Route::group(['prefix' => '/admin-cp/online/{course_id}/quiz', 'middleware' => ['auth','permission:online-course']], function() {
    Route::get('/', 'QuizController@index')->name('module.online.quiz')->where('course_id', '[0-9]+');

    Route::get('/get-quiz', 'QuizController@getData')->name('module.online.get_quiz')->where('course_id', '[0-9]+');

    Route::get('/create', 'QuizController@form')->name('module.online.quiz.create')->where('course_id', '[0-9]+');

    Route::get('/edit/{id}', 'QuizController@form')->name('module.online.quiz.edit')->where('course_id', '[0-9]+')->where('id', '[0-9]+');

    Route::post('/save', 'QuizController@save')->name('module.online.quiz.save')->where('course_id', '[0-9]+');

    Route::post('/remove', 'QuizController@remove')->name('module.online.quiz.remove')->where('course_id', '[0-9]+');

    Route::post('/edit/{id}/save-part', 'QuizController@savePart')->name('module.online.quiz.edit.savepart')->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-part', 'QuizController@getDataPart')->name('module.online.quiz.edit.getpart')->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/add-question', 'QuizController@questionQuiz')
        ->name('module.online.quiz.question')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/report/{id}', 'QuizController@report')->name('module.online.quiz.report')->where('course_id', '[0-9]+')->where('id', '[0-9]+');
    Route::get('/getreport/{id}', 'QuizController@getReport')->name('module.online.quiz.getreport')->where('course_id', '[0-9]+')->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'QuizController@removeAttempt')->name('module.online.quiz.attempt.remove')->where('course_id', '[0-9]+')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/online/rating-level/{course_id}', 'middleware' => ['auth','permission:online-course']], function() {
    Route::get('/get-data', 'RatingLevelController@getData')
        ->name('module.online.rating_level.getData')
        ->where('course_id', '[0-9]+');

    Route::post('/save', 'RatingLevelController@save')
        ->name('module.online.rating_level.save')
        ->where('course_id', '[0-9]+');

    Route::post('/remove', 'RatingLevelController@remove')
        ->name('module.online.rating_level.remove')
        ->where('course_id', '[0-9]+');

    Route::post('/modal-qrcode/{id}', 'RatingLevelController@modalQRCode')
        ->name('module.online.rating_level.modal_qr_code')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/modal-add-object/{id}', 'RatingLevelController@modalAddObject')
        ->name('module.online.rating_level.modal_add_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/get-data-object/{id}', 'RatingLevelController@getDataObject')
        ->name('module.online.rating_level.getDataObject')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/save-object/{id}', 'RatingLevelController@saveObject')
        ->name('module.online.rating_level.save_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/ajax-get-object/{id}', 'RatingLevelController@ajaxGetObject')
        ->name('module.online.rating_level.ajax_get_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/remove-object/{id}', 'RatingLevelController@removeObject')
        ->name('module.online.rating_level.remove_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/list-report', 'RatingLevelController@listReport')
        ->name('module.online.rating_level.list_report')
        ->where('course_id', '[0-9]+');

    Route::get('/list-report/getdata', 'RatingLevelController@getdataListReport')
        ->name('module.online.rating_level.list_report.getdata')
        ->where('course_id', '[0-9]+');

    Route::get('/list-user-rating/{course_rating_level_id}/getdata', 'RatingLevelController@getdataListUserRating')
        ->name('module.online.rating_level.list_user_rating.getdata')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/modal-rating-level', 'RatingLevelController@modalRatingLevel')
        ->name('module.online.rating_level.modal_rating_level')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/modal-edit-rating-level', 'RatingLevelController@modalEditRatingLevel')
        ->name('module.online.rating_level.modal_edit_rating_level')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/save-rating-level-course', 'RatingLevelController@saveRatingCourse')
        ->name('module.online.rating_level.save_rating_course')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/online/userpoint-setting/{course_id}', 'middleware' => ['auth','permission:online-course']], function() {

	Route::post('/add-userpoint-complete/{type}', 'UserPoint@saveSettingComplete')
	->name('module.online.add-userpoint-setting-complete')
	->where('course_id', '[0-9]+');

	Route::post('/userpoint-complete/{type}', 'UserPoint@showModalComplete')
	->name('module.online.userpoint-setting-complete')
	->where('course_id', '[0-9]+');

	Route::get('/save-setting-complete', 'UserPoint@saveSettingComplete')
	->name('module.online.userpoint-save')
	->where('course_id', '[0-9]+');

	Route::post('/setting-module/{type}', 'UserPoint@showModalModule')
	->name('module.online.userpoint-setting-module')
	->where('course_id', '[0-9]+');

	Route::post('/add-userpoint-modules/{type}', 'UserPoint@saveSettingActivities')
	->name('module.online.add-userpoint-setting-activities')
	->where('course_id', '[0-9]+');


	Route::post('/save-setting-others/{type}', 'UserPoint@saveSettingOthers')
	->name('module.online.userpoint-setting-others')
	->where('course_id', '[0-9]+');

	Route::post('/edit-userpoint-modules/{type}/{id}', 'UserPoint@showModalModule')
	->name('module.online.edit-userpoint-setting-module')
	->where('course_id', '[0-9]+')->where('id', '[0-9]+');

    Route::post('/edit-userpoint-complete/{type}/{id}', 'UserPoint@showModalComplete')
	->name('module.online.edit-userpoint-setting-complete')
	->where('course_id', '[0-9]+')->where('id', '[0-9]+');

	Route::post('/delete/{type}/{id}', 'UserPoint@deleteSetting')
	->name('module.online.userpoint-setting.delete')
	->where('course_id', '[0-9]+')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/online/{course_id}/quiz/userpoint-setting/{quiz_id}', 'middleware' => ['auth','permission:online-course']], function() {
    Route::post('/setting-quiz', 'UserPoint@showModalQuiz')
        ->name('module.online.quiz.userpoint-setting-quiz')
        ->where('course_id', '[0-9]+')
        ->where('quiz_id', '[0-9]+');

    Route::post('/add-userpoint-quiz', 'UserPoint@saveSettingQuiz')
        ->name('module.online.quiz.add-userpoint-setting-quiz')
        ->where('course_id', '[0-9]+')
        ->where('quiz_id', '[0-9]+');

    Route::post('/edit-userpoint-quiz/{id}', 'UserPoint@showModalQuiz')
        ->name('module.online.quiz.edit-userpoint-setting-quiz')
        ->where('course_id', '[0-9]+')
        ->where('quiz_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/delete/{id}', 'UserPoint@deleteSettingQuiz')
        ->name('module.online.quiz.userpoint-setting.delete')
        ->where('course_id', '[0-9]+')
        ->where('quiz_id', '[0-9]+')
        ->where('id', '[0-9]+');
});
