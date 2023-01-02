<?php

Route::group(['prefix' => '/admin-cp/quiz', 'middleware' => 'auth'], function() {
    Route::get('/all', 'Backend\BackendController@index')->name('module.quiz.manager')
    ->middleware('permission:quiz');

    Route::get('/getdata', 'Backend\BackendController@getData')->name('module.quiz.getdata')
    ->middleware('permission:quiz');

    Route::post('/save', 'Backend\BackendController@save')->name('module.quiz.save')
    ->middleware('permission:quiz-create');

    Route::post('/send-mail-approve', 'Backend\BackendController@sendMailApprove')->name('module.quiz.send_mail_approve')
    ->middleware('permission:quiz');

    Route::post('/send-mail-change', 'Backend\BackendController@sendMailChange')->name('module.quiz.send_mail_change')
    ->middleware('permission:quiz');

    Route::post('/send-mail-invitation', 'Backend\BackendController@sendMailInvitation')->name('module.quiz.send_mail_invitation')
    ->middleware('permission:quiz');

    Route::get('/edit/{id}', 'Backend\BackendController@form')->name('module.quiz.edit')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-edit');

    Route::post('/edit/{id}/save-part', 'Backend\BackendController@savePart')->name('module.quiz.edit.savepart')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::post('/edit/{id}/edit-part', 'Backend\BackendController@editPart')->name('module.quiz.edit.edit_part')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::post('/edit/{id}/update-part', 'Backend\BackendController@updatePart')->name('module.quiz.edit.updatepart')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::get('/edit/{id}/get-part', 'Backend\BackendController@getDataPart')->name('module.quiz.edit.getpart')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-edit');

    Route::post('/edit/{id}/remove-part', 'Backend\BackendController@removePart')->name('module.quiz.edit.removepart')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::post('/edit/{id}/save-rank', 'Backend\BackendController@saveRank')->name('module.quiz.edit.saverank')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::get('/edit/{id}/get-rank', 'Backend\BackendController@getDataRank')->name('module.quiz.edit.getrank')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::post('/edit/{id}/remove-rank', 'Backend\BackendController@removeRank')->name('module.quiz.edit.removerank')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-remove');

    Route::post('/edit/{id}/save-teacher', 'Backend\BackendController@saveTeacher')->name('module.quiz.save_teacher')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::get('/edit/{id}/get-permission-teacher', 'Backend\BackendController@getDataPermissionTeacher')
        ->name('module.quiz.edit.getPermissionTeacher')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::post('/edit/{id}/remove-permission-teacher', 'Backend\BackendController@removePermissionTeacher')
        ->name('module.quiz.edit.removePermissionTeacher')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::post('/edit/{id}/update-permission-teacher', 'Backend\BackendController@updatePermissionTeacher')
        ->name('module.quiz.edit.updatePermissionTeacher')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::post('/edit/{id}/save-setting', 'Backend\BackendController@saveSetting')->name('module.quiz.save_setting')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::get('/get-unit', 'Backend\BackendController@loadUnit')->name('module.quiz.edit.getunit')
    ->middleware('permission:quiz');

    Route::get('/create', 'Backend\BackendController@form')->name('module.quiz.create')
    ->middleware('permission:quiz');

    Route::post('/remove', 'Backend\BackendController@remove')->name('module.quiz.remove')
    ->middleware('permission:quiz-delete');

    Route::post('/ajax-is-open', 'Backend\BackendController@saveIsOpen')->name('module.quiz.ajax_is_open')
    ->middleware('permission:quiz');

    Route::post('/ajax-status', 'Backend\BackendController@saveStatus')->name('module.quiz.ajax_status')
    ->middleware('permission:quiz');

    Route::post('/ajax-view-result', 'Backend\BackendController@saveViewResult')->name('module.quiz.ajax_view_result')
    ->middleware('permission:quiz');

    Route::post('/ajax-copy-quiz', 'Backend\BackendController@copyQuiz')->name('module.quiz.ajax_copy_quiz')
    ->middleware('permission:quiz');

    Route::get('/export/{id}', 'Backend\BackendController@exportQuiz')->name('module.quiz.export_quiz')
    ->middleware('permission:quiz');

    Route::post('/modal-info/{id}', 'Backend\BackendController@modalInfo')->name('module.quiz.modal_info')
    ->where('id', '[0-9]+')
    ->middleware('permission:quiz');

    Route::post('/modal-course/{course_id}/{course_type}', 'Backend\BackendController@modalCourse')->name('module.quiz.modal_course')
    ->where('course_id', '[0-9]+')
    ->where('course_type', '[0-9]+')
    ->middleware('permission:quiz');

    Route::post('/get-user-create-quiz/{user_id}', 'Backend\BackendController@getUserCreateQuiz')
        ->name('module.quiz.get_user_create_quiz')
        ->where('user_id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::post('/ajax-load-exam-template', 'Backend\BackendController@loadExamTemplate')->name('module.quiz.load.exam.template')
    ->middleware('permission:quiz');

    Route::get('/setting-alert', 'Backend\BackendController@settingAlert')->name('module.quiz.setting_alert')
    ->middleware('permission:quiz');

    Route::post('/save-setting-alert', 'Backend\BackendController@saveSettingAlert')->name('module.quiz.save_setting_alert')
    ->middleware('permission:quiz');

    Route::get('/user-second-note', 'Backend\BackendController@userSecondNote')->name('module.quiz.user_second_note')
    ->middleware('permission:quiz');

    Route::get('/getdata-note-by-user-second', 'Backend\BackendController@getDataNoteByUserSecond')->name('module.quiz.user_second_note.getdata')
    ->middleware('permission:quiz');

    Route::post('/remove-user-second-note', 'Backend\BackendController@removeUserSecondNote')->name('module.quiz.remove_user_second_note')
    ->middleware('permission:quiz');

    Route::get('/dashboard', 'Backend\BackendController@dashboard')->name('module.quiz.dashboard')
    ->middleware('permission:quiz');

    Route::post('/dashboard/chart', 'Backend\BackendController@getChartUser')->name('module.quiz.dashboard.chart_user')
    ->middleware('permission:quiz');

    Route::get('/dashboard/export', 'Backend\BackendController@exportDashboard')->name('module.quiz.dashboard.export')
    ->middleware('permission:quiz');

    Route::get('/data-old', 'Backend\BackendController@dataOldQuiz')->name('module.quiz.data_old_quiz')
    ->middleware('permission:quiz');

    Route::get('/get-data-old', 'Backend\BackendController@getDataOldQuiz')->name('module.quiz.get_data_old_quiz')
    ->middleware('permission:quiz');

    Route::post('/remove-data-old', 'Backend\BackendController@removeDataOldQuiz')->name('module.quiz.data_old_quiz.remove')
    ->middleware('permission:quiz');

    Route::get('/export-data-old', 'Backend\BackendController@exportDataOldQuiz')->name('module.quiz.data_old_quiz.export')
    ->middleware('permission:quiz');

    Route::get('/edit-data-old/{id}', 'Backend\BackendController@editDataOldQuiz')->name('module.quiz.data_old_quiz.edit')
    ->middleware('permission:quiz');

    Route::post('/save-edit-data-old', 'Backend\BackendController@saveEditDataOldQuiz')->name('module.quiz.save_edit_data_old')
    ->middleware('permission:quiz');

    Route::post('/import-data-old', 'Backend\BackendController@importDataOldQuiz')->name('module.quiz.data_old_quiz.import')
    ->middleware('permission:quiz');

    Route::get('/edit/{id}/get-suggestions', 'Backend\BackendController@getDataSuggestions')->name('module.quiz.edit.get_suggestions')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::post('/ajax-quiz-part', 'Backend\BackendController@ajaxQuizPart')->name('module.quiz.ajax_text_quiz_part');

    Route::get('/text-quiz/{id}', 'Backend\BackendController@textQuiz')->name('module.quiz.text_quiz');
});

Route::group(['prefix' => '/admin-cp/quiz/{id}', 'middleware' => 'auth'], function() {

    Route::get('/add-question', 'Backend\QuizQuestionController@index')
        ->name('module.quiz.question')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question-create');

    Route::post('/get-modal-quiz-question', 'Backend\QuizQuestionController@showModal')
        ->name('module.quiz.question.get_modal_quiz_question')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::post('/get-modal-question-category', 'Backend\QuizQuestionController@showModalCategory')
        ->name('module.quiz.question.get_modal_question_category')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::post('/save-question-random', 'Backend\QuizQuestionController@saveQuestionRandom')
        ->name('module.quiz.question.save_question_random')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::post('/save-category-question', 'Backend\QuizQuestionController@saveCategoryQuestion')
        ->name('module.quiz.question.save_category_question')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::get('/getdata-question', 'Backend\QuizQuestionController@getDataQuestion')
        ->name('module.quiz.question.getdata_question')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::post('/remove-quiz-question', 'Backend\QuizQuestionController@removeQuizQuestion')
        ->name('module.quiz.question.remove_quiz_question')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question-delete');

    Route::post('/update-max-score', 'Backend\QuizQuestionController@updateMaxScore')
        ->name('module.quiz.question.update_max_score')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::post('/update-num-order', 'Backend\QuizQuestionController@updateNumOrder')
        ->name('module.quiz.question.update_num_order')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::post('/modal-qqcategory', 'Backend\QuizQuestionController@showModalQQCategory')
        ->name('module.quiz.question.modal_qqcategory')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::post('/save-qqcategory', 'Backend\QuizQuestionController@saveQQCategory')
        ->name('module.quiz.question.save_qqcategory')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::post('/remove-qqcategory', 'Backend\QuizQuestionController@removeQQCategory')
        ->name('module.quiz.question.remove_qqcategory')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::get('/review-quiz', 'Backend\QuizQuestionController@reviewQuiz')
        ->name('module.quiz.question.review_quiz')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::post('/get-question-review-quiz', 'Backend\QuizQuestionController@getQuestionReviewQuiz')
        ->name('module.quiz.question.get_question_review_quiz')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');

    Route::post('/update-template-quiz', 'Backend\QuizQuestionController@updateTemplateQuiz')
        ->name('module.quiz.question.update_template_quiz')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-question');
});

Route::group(['prefix' => '/admin-cp/quiz/result/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\ResultController@index')
        ->name('module.quiz.result')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::get('/getdata', 'Backend\ResultController@getData')
        ->name('module.quiz.result.getdata')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::post('/save-grade', 'Backend\ResultController@saveGrade')
        ->name('module.quiz.result.save_grade')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::post('/save-reexamine', 'Backend\ResultController@saveReexamine')
        ->name('module.quiz.result.save_reexamine')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::post('/save-file', 'Backend\ResultController@saveFile')
        ->name('module.quiz.result.save_file')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::post('/update-user-locked', 'Backend\ResultController@updateUserLocked')
        ->name('module.quiz.result.update_user_locked')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::post('/import-result', 'Backend\ResultController@importResult')
        ->name('module.quiz.result.import_result')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::get('/export-result', 'Backend\ResultController@exportResult')
        ->name('module.quiz.result.export_result')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::get('/{part_id}/{type}/{user_id}/view', 'Backend\ResultController@view')
        ->name('module.quiz.result.user.view')
        ->where('id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('type', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::post('/{type}/{user_id}/{attempt_id}/question', 'Backend\ResultController@getQuestion')
        ->name('module.quiz.result.user.question')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::get('/{type}/{user_id}/image', 'Backend\ResultController@imageShooting')
        ->name('module.quiz.result.user.image')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-result');

    Route::get('/{type}/{user_id}/getdataImage', 'Backend\ResultController@getDataImageShooting')
        ->name('module.quiz.result.user.getdata_image')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-result');
});

Route::group(['prefix' => '/admin-cp/quiz/register/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\RegisterController@index')
        ->name('module.quiz.register')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::get('/getdata', 'Backend\RegisterController@getData')
        ->name('module.quiz.register.getdata')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::get('/getDataNotRegister', 'Backend\RegisterController@getDataNotRegister')
        ->name('module.quiz.register.getDataNotRegister')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::get('/create', 'Backend\RegisterController@form')
        ->name('module.quiz.register.create')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::post('/save', 'Backend\RegisterController@save')
        ->name('module.quiz.register.save')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::post('/remove', 'Backend\RegisterController@remove')
        ->name('module.quiz.register.remove')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::post('/import-register', 'Backend\RegisterController@importRegister')
        ->name('module.quiz.register.import_register')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::get('/export-register', 'Backend\RegisterController@exportRegister')
        ->name('module.quiz.register.export_register')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::post('/send-mail-user-registed/{type}', 'Backend\RegisterController@sendMailUserRegisted')
        ->name('module.quiz.register.send_mail_user_registed')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::get('/export-template-register', 'Backend\RegisterController@exportTemplateRegister')
        ->name('module.quiz.register.export_template_register')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::post('/attempts_again', 'Backend\RegisterController@attemptsAgain')
        ->name('module.quiz.register.attempts_again')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-register');

    Route::post('/block_quiz', 'Backend\RegisterController@blockQuiz')
        ->name('module.quiz.register.block_quiz')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz-register');
});

Route::group(['prefix' => '/admin-cp/quiz/history', 'middleware' => 'auth'], function() {
    Route::get('/user', 'Backend\BackendController@historyUser')->name('module.quiz.history_user')
    ->middleware('permission:quiz-history');

    Route::get('/getdataUser', 'Backend\BackendController@getDataHistoryUser')->name('module.quiz.history_user.getdata')
    ->middleware('permission:quiz-history');

    Route::get('/result-user/{user_id}', 'Backend\BackendController@historyResultUser')->name('module.quiz.history_result_user')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:quiz-history');

    Route::get('/getdataResultUser/{user_id}', 'Backend\BackendController@getDataHistoryResultUser')->name('module.quiz.history_result_user.getdata')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:quiz-history');

    Route::get('/export-user', 'Backend\BackendController@exportHistoryUser')->name('module.quiz.history_user.export')
    ->middleware('permission:quiz-history');

    Route::get('/user-second', 'Backend\BackendController@historyUserSecond')->name('module.quiz.history_user_second')
    ->middleware('permission:quiz-history-user-second');

    Route::get('/getdataUserSecond', 'Backend\BackendController@getDataHistoryUserSecond')->name('module.quiz.history_user_second.getdata')
    ->middleware('permission:quiz-history-user-second');

    Route::get('/result-user-second/{user_id}', 'Backend\BackendController@historyResultUserSecond')->name('module.quiz.history_result_user_second')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:quiz-history-user-second');

    Route::get('/getdataResultUserSecond/{user_id}', 'Backend\BackendController@getDataHistoryResultUserSecond')->name('module.quiz.history_result_user_second.getdata')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:quiz-history-user-second');

    Route::get('/export-user-second', 'Backend\BackendController@exportHistoryUserSecond')->name('module.quiz.history_user_second.export')
    ->middleware('permission:quiz-history-user-second');

    Route::get('/view-quiz/{quiz_id}/{attempt_id}/{user_id}/{type}', 'Backend\BackendController@viewQuizHistory')
        ->name('module.quiz.history_user.view_quiz')
        ->where('quiz_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('type', '[0-9]+');

    Route::post('/get-question-history/{quiz_id}/{attempt_id}/{user_id}/{type}', 'Backend\BackendController@getQuestionHistory')
        ->name('module.quiz.history_user.question_history')
        ->where('quiz_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('type', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/quiz/userpoint-setting/{quiz_id}', 'middleware' => ['auth','permission:quiz']], function() {
    Route::post('/add-userpoint-complete', 'UserPoint@saveSettingComplete')
        ->name('module.quiz.add-userpoint-setting-complete')
        ->where('quiz_id', '[0-9]+');

    Route::post('/userpoint-complete', 'UserPoint@showModalComplete')
        ->name('module.quiz.userpoint-setting-complete')
        ->where('quiz_id', '[0-9]+');

    Route::get('/save-setting-complete', 'UserPoint@saveSettingComplete')
        ->name('module.quiz.userpoint-save')
        ->where('quiz_id', '[0-9]+');

    Route::post('/setting-quiz', 'UserPoint@showModalQuiz')
        ->name('module.quiz.userpoint-setting-quiz')
        ->where('quiz_id', '[0-9]+');

    Route::post('/add-userpoint-quiz', 'UserPoint@saveSettingQuiz')
        ->name('module.quiz.add-userpoint-setting-quiz')
        ->where('quiz_id', '[0-9]+');

    Route::post('/remove-item-time-userpoint', 'UserPoint@removeItemTimeUserpoint')->name('module.quiz.remove_item_time_userpoint')
        ->where('id', '[0-9]+')
        ->middleware('permission:quiz');

    Route::post('/save-setting-others', 'UserPoint@saveSettingOthers')
        ->name('module.quiz.userpoint-setting-others')
        ->where('quiz_id', '[0-9]+');

    Route::post('/edit-userpoint-quiz/{id}', 'UserPoint@showModalQuiz')
        ->name('module.quiz.edit-userpoint-setting-quiz')
        ->where('quiz_id', '[0-9]+')->where('id', '[0-9]+');

    Route::post('/edit-userpoint-complete/{id}', 'UserPoint@showModalComplete')
        ->name('module.quiz.edit-userpoint-setting-complete')
        ->where('quiz_id', '[0-9]+')->where('id', '[0-9]+');

    Route::post('/delete/{id}', 'UserPoint@deleteSetting')
        ->name('module.quiz.userpoint-setting.delete')
        ->where('quiz_id', '[0-9]+')->where('id', '[0-9]+');
});


