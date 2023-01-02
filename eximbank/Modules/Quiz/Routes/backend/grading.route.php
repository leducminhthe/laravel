<?php
Route::group(['prefix' => '/teacher-cp/quiz/grading', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\GradingController@index')
        ->name('module.quiz.grading');

    Route::get('/data-quiz', 'Backend\GradingController@getDataQuiz')
        ->name('module.quiz.grading.data_quiz');

    Route::post('/modal-teacher/{quiz_id}', 'Backend\GradingController@modalTeacherGraded')
        ->name('module.quiz.grading.modal_teacher')
        ->where('quiz_id', '[0-9]+');

    Route::group(['prefix' => '/{quiz_id}', 'middleware' => 'auth'], function() {
        Route::get('/', 'Backend\GradingController@user')
            ->name('module.quiz.grading.user')
            ->where('quiz_id', '[0-9]+');

        Route::get('/data-user', 'Backend\GradingController@getDataUser')
            ->name('module.quiz.grading.user.data_user')
            ->where('quiz_id', '[0-9]+');
    });

    Route::group(['prefix' => '/{quiz_id}/{part_id}/{type}/{user_id}/{attempt_id}', 'middleware' => 'auth'], function() {
        Route::get('/', 'Backend\GradingController@grading')
            ->name('module.quiz.grading.user.grading')
            ->where('quiz_id', '[0-9]+')
            ->where('part_id', '[0-9]+')
            ->where('type', '[0-9]+')
            ->where('user_id', '[0-9]+')
            ->where('attempt_id', '[0-9]+');

        Route::post('/question', 'Backend\GradingController@getQuestion')
            ->name('module.quiz.grading.user.question')
            ->where('quiz_id', '[0-9]+')
            ->where('part_id', '[0-9]+')
            ->where('type', '[0-9]+')
            ->where('user_id', '[0-9]+')
            ->where('attempt_id', '[0-9]+');

        Route::post('/save-score', 'Backend\GradingController@saveScore')
            ->name('module.quiz.grading.user.save_score')
            ->where('quiz_id', '[0-9]+')
            ->where('part_id', '[0-9]+')
            ->where('type', '[0-9]+')
            ->where('user_id', '[0-9]+')
            ->where('attempt_id', '[0-9]+');

        Route::post('/save-comment', 'Backend\GradingController@saveComment')
            ->name('module.quiz.grading.user.save_comment')
            ->where('quiz_id', '[0-9]+')
            ->where('part_id', '[0-9]+')
            ->where('type', '[0-9]+')
            ->where('user_id', '[0-9]+')
            ->where('attempt_id', '[0-9]+');

        Route::post('/complete', 'Backend\GradingController@gradeComplete')
            ->name('module.quiz.grading.user.complete')
            ->where('quiz_id', '[0-9]+')
            ->where('part_id', '[0-9]+')
            ->where('type', '[0-9]+')
            ->where('user_id', '[0-9]+')
            ->where('attempt_id', '[0-9]+');
    });
});
