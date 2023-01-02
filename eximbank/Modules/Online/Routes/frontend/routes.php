<?php

require_once (__DIR__ . '/scorm.route.php');
require_once (__DIR__ . '/classroom.route.php');

Route::group(['prefix' => '/online', 'middleware' => ['quiz.secondary','auth']], function() {
    Route::get('/', 'FrontendController@index')->name('module.online');

    //Route::get('/getdata', 'FrontendController@getData')->name('module.online.getdata');

    Route::post('/rating/{id}', 'FrontendController@rating')->name('module.online.rating')->where('id', '[0-9]+');

    Route::post('/register-course/{id}', 'FrontendController@registerCourse')->name('module.online.register_course')->where('id', '[0-9]+');

    Route::post('/commnent/{id}', 'FrontendController@comment')->name('module.online.comment')->where('id', '[0-9]+');

    Route::get('/search','FrontendController@search')->name('module.online.search');

    Route::get('/embed/{id}/{lesson}','EmbedUrlController@index')->name('module.online.embed');

    Route::get('/view-pdf/{id}', 'FrontendController@viewPDF')->name('module.online.view_pdf')->where('id', '[0-9]+');

    Route::get('/view-video/{file}', 'FrontendController@viewVideo')->name('module.online.view_video');

    Route::get('/tutorial-view-pdf/{id}/{key}', 'FrontendController@tutorialViewPDF')->name('module.online.tutorial.view_pdf')->where('id', '[0-9]+');

    Route::post('/referer/qrcode/{id}', 'FrontendController@showModalQrcodeReferer')->name('frontend.online.referer.show_modal')->where('id', '[0-9]+');

    Route::get('/get-object/{id}', 'FrontendController@getObject')->name('frontend.online.get_object')->where('id', '[0-9]+');

    Route::get('/getdata-rating-level/{id}', 'FrontendController@getDataRatingLevel')
        ->name('module.online.detail.rating_level.getdata')
        ->where('id', '[0-9]+');

    // BÌNH LUẬN KHÓA HỌC
    Route::post('/comment-course-online/{id}', 'FrontendController@commentCourseOnline')->name('frontend.online.comment.course_online')->where('id', '[0-9]+');
    Route::post('/delete-comment-course-online/{id}', 'FrontendController@deleteCommentCourseOnline')->name('frontend.online.delete.comment.course_online')->where('id', '[0-9]+');

    // ĐÁNH DẤU KHÓA HỌC
    Route::post('/bookmark-online', 'FrontendController@bookmarkOnline')->name('frontend.online.bookmark_online')
        ->where('id', '[0-9]+');

    Route::post('/ajax-rating-star/{course_id}', 'FrontendController@ajaxRatingStar')->name('module.online.detail.ajax_rating_star')->where('course_id', '[0-9]+');

    Route::post('/ajax-rating-level-offline/{id}', 'FrontendController@ajaxRatingLevelOnline')
    ->name('module.online.detail.ajax_rating_level_offline')
    ->where('id', '[0-9]+');

    Route::post('/ajax-document/{course_id}', 'FrontendController@ajaxDocument')->name('module.online.detail.ajax_document')->where('course_id', '[0-9]+');

    Route::get('/getdata-document/{id}', 'FrontendController@getDataDocument')
    ->name('module.online.detail.document.getdata')
    ->where('id', '[0-9]+');

    Route::get('/ajax_result_learn/{course_id}', 'FrontendController@ajaxResultLearn')->name('module.online.detail.ajax_result_learn')->where('course_id', '[0-9]+');

    Route::get('/ajax_history_activity_course/{course_id}', 'FrontendController@ajaxHistoryActivityCourse')->name('module.online.detail.ajax_history_activity_course')->where('course_id', '[0-9]+');
});
Route::group(['prefix' => '/online/detail-online', 'middleware' => ['quiz.secondary','auth']], function () {
    Route::get('/{id}', 'FrontendController@detail2')->name('module.online.detail_online')->where('id', '[0-9]+');

    Route::get('/new/{id}', 'FrontendController@detailNew')->name('module.online.detail_new')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/online/detail', 'middleware' => ['quiz.secondary','auth']], function () {
    Route::get('/{id}', 'FrontendController@detail')->name('module.online.detail')->where('id', '[0-9]+');

    Route::get('/first/{id}', 'FrontendController@detailFirst')->name('module.online.detail_first')->where('id', '[0-9]+');

    Route::post('/share-course/{id}/{type}', 'FrontendController@shareCourse')->name('module.online.detail.share_course')->where('id', '[0-9]+')->where('type', '[0-9]+');

    Route::get('/{id}/activity/{aid}/{lesson}', 'FrontendController@goActivity')->name('module.online.goactivity')->where('id', '[0-9]+')->where('aid', '[0-9]+');

    Route::post('/ajax-activity', 'FrontendController@ajaxActivity')->name('module.online.detail.ajax_activity');

    Route::post('/ajax-description-activity', 'FrontendController@ajaxDescriptionActivity')->name('module.online.detail.ajax_description_activity');

    Route::post('/ajax-run-last-quiz', 'FrontendController@ajaxRunLastQuiz')->name('module.online.detail.ajax_run_last_quiz');

    Route::post('/finish-activity-video', 'FrontendController@finishActivityVideo')->name('module.online.detail.finish_activity_video');

    Route::post('/user-book-mark-activity', 'FrontendController@userBookMarkActivity')->name('module.online.detail.user_book_mark_activity');

    Route::post('/save-user-time-learn', 'FrontendController@saveUserTimeLearn')->name('module.online.detail.save_user_time_learn');
    Route::get('/{id}/activity/{aid}/zoom/{type}', 'FrontendController@goZoom')->name('module.online.goactivity.zoom')->where('id', '[0-9]+')->where('aid', '[0-9]+')->where('type', '[0-9]+');

    //hoạt động khảo sát
    Route::get('/survey-user/{id}/{aid}', 'FrontendController@getSurveyUser')->name('module.online.survey.user')->where('id', '[0-9]+')->where('aid', '[0-9]+');
    Route::get('/edit-survey-user/{id}/{aid}/{user_id}', 'FrontendController@editSurveyUser')->name('module.online.survey.user.edit')->where('id', '[0-9]+')->where('aid', '[0-9]+')->where('user_id', '[0-9]+');
    Route::post('/save-online-survey-user', 'FrontendController@saveOnlineSurveyUser')->name('module.online.survey.user.save');

    //Lưu ghi chú khoá học
    Route::post('/save-note-course/{id}', 'FrontendController@saveNoteCourse')->name('module.online.detail.save_note_course')->where('id', '[0-9]+');
});

