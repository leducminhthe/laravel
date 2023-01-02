<?php

Route::get('/asset/{path}', 'Frontend\AssetController@asset')->name('stream.asset')->where('path', '(.+)');

Route::get('/video/{file}', 'Frontend\StreamController@video')->name('stream.video');

Route::get('/uploads/{path}', 'Frontend\StreamController@stream')->name('stream.upload')->where('path', '(.+)');

Route::get('/load-ajax/{func}', 'AjaxLoadController@loadAjax')->name('load_ajax');

Route::get('/download/{path}', 'MediaController@dataFileDownload')->name('download_file');

Route::get('/dashboard', 'Frontend\HomeController@index')->name('frontend.home');

Route::get('/', 'Frontend\HomeController@homeAfterLogin')->name('frontend.home_after_login');

Route::post('/update-survey-popup', 'Controller@updateSurveyPopup')->name('frontend.update_survey_popup');

Route::post('/search-user', 'Controller@searchUser')->name('frontend.search_user');

// CHI TIẾT TỔNG GIỜ HỌC CỦA HV
Route::get('/detail-total-time-user', 'Frontend\HomeController@detailTotalTimeUser')->name('frontend.detail_total_time_user');
Route::get('/getdata-detail-total-time-user', 'Frontend\HomeController@getDataDetailTotalTimeUser')->name('frontend.getdata_detail_total_time_user');

//Lấy khóa học đã đăng ký theo năm chart
Route::post('/get-register-online-by-year', 'Frontend\HomeController@getRegisterOnlineByYear')->name('frontend.home.get_register_online_by_year');
Route::post('/get-register-offline-by-year', 'Frontend\HomeController@getRegisterOfflineByYear')->name('frontend.home.get_register_offline_by_year');

//ĐÓNG MỞ MENU DƯỚI
Route::post('/', 'Frontend\HomeController@closeOpendMenuBottom')->name('frontend.close_open_menu_bottom');

//ĐÓNG MỞ MENU
Route::post('/close-open-menu', 'Frontend\HomeController@closeOpendMenu')->name('frontend.close_open_menu');

//LƯU SỐ LẦN MỞ ĐIỀU HƯỚNG TRẢI NGHIỆM
Route::post('/save-experience-navigate', 'Frontend\HomeController@saveExperienceNavigate')->name('frontend.save_experience_navigate');

// Ghi chú
Route::get('/note', 'Frontend\NoteController@index')->name('frontend.note');
Route::get('/get-data-note', 'Frontend\NoteController@getData')->name('frontend.get_data.note');
Route::post('/remove-note', 'Frontend\NoteController@remove')->name('frontend.remove.note');
Route::post('/save-note', 'Frontend\NoteController@saveNote')->name('frontend.save.note');
Route::post('/close-note', 'Frontend\NoteController@closeNote')->name('frontend.close.note');

// Liên hệ
Route::get('/contact', 'Frontend\ContactController@index')->name('frontend.contact');
Route::get('/contact/detail/{id}', 'Frontend\ContactController@contactDetail')->name('frontend.contact.detail');

// google map
Route::get('/google-map','Frontend\GoogleMapController@index')->name('frontend.google.map');
Route::post('/google-map/post/','Frontend\GoogleMapController@store')->name('frontend.google.map.store');

// TẤT CẢ KHÓA HỌC
Route::get('/all-course/{type}','Frontend\AllCourseController@index')->name('frontend.all_course')->middleware('pagespeed');
Route::get('/all-course-search','Frontend\AllCourseController@search')->name('frontend.all_course_search');
Route::post('/ajax-content-course','Frontend\AllCourseController@ajaxConentCourse')->name('frontend.ajax_content_course');
Route::post('/ajax-summary-course','Frontend\AllCourseController@ajaxSummaryCourse')->name('frontend.ajax_summary_course');
Route::post('/ajax-object-course','Frontend\AllCourseController@ajaxObjectCourse')->name('frontend.ajax_object_course');
Route::post('/ajax-bonus-course','Frontend\AllCourseController@ajaxBonusCourse')->name('frontend.ajax_bonus_course');
Route::post('/ajax-course-training-program','Frontend\AllCourseController@ajaxCourseTrainingProgram')->name('frontend.ajax_course_training_program');
Route::post('/ajax-modal-activity/{course_id}/{course_type}','Frontend\AllCourseController@ajaxModalActivity')
    ->name('frontend.ajax_modal_activity')
    ->where('course_id', '[0-9]+')
    ->where('course_type', '[0-9]+');

Route::post('/ajax-modal-class/{course_id}','Frontend\AllCourseController@ajaxModalClass')
    ->name('frontend.ajax_modal_class')
    ->where('course_id', '[0-9]+')
    ->where('course_type', '[0-9]+');

Route::post('/ajax-modal-condition-register','Frontend\AllCourseController@ajaxModalConditionRegister')
    ->name('frontend.ajax_modal_condition_register')
    ->where('course_id', '[0-9]+')
    ->where('course_type', '[0-9]+');

Route::post('/ajax-register-quiz','Frontend\AllCourseController@ajaxRegisterQuiz')
    ->name('frontend.ajax_register_quiz')
    ->where('course_id', '[0-9]+')
    ->where('course_type', '[0-9]+');

Route::get('/guide', 'Frontend\GuideController@index')->name('frontend.guide');
Route::get('/guide/pdf', 'Frontend\GuideController@index')->name('frontend.guide.pdf');
Route::get('/guide/video-guide', 'Frontend\GuideController@video')->name('module.frontend.guide.video');
Route::get('/guide/posts-guide', 'Frontend\GuideController@posts')->name('module.frontend.guide.posts');
Route::get('/guide/posts-guide/detail/{id}', 'Frontend\GuideController@postDetail')->name('module.frontend.guide.post.detail');
Route::get('/guide/view-pdf/{id}', 'Frontend\GuideController@viewPDF')->name('frontend.guide.view_pdf')->where('id', '[0-9]+');

Route::get('/map', 'Frontend\MapController@index')->name('frontend.map');

Route::get('/my-course', 'Frontend\MyCourseController@index')->name('frontend.my_course');

Route::get('/calendar', 'Frontend\CalendarController@index')->name('frontend.calendar')->middleware('pagespeed');
Route::get('/calendar-week', 'Frontend\CalendarController@calendarWeek')->name('frontend.calendar.week')->middleware('pagespeed');
Route::get('/ajax-calendar', 'Frontend\CalendarController@getData')->name('frontend.calendar.getdata');

Route::get('/my-course/getdata', 'Frontend\MyCourseController@getData')->name('frontend.my_course.getdata');

//Route::get('/account', 'Frontend\AccountController@index')->name('frontend.account');

Route::get('/account/getdataTrainingRoadmap','Frontend\AccountController@getDataTrainingRoadmap' )->name('frontend.account.getdataTrainingRoadmap');
Route::get('/account/getdataTrainingRoadmapOther','Frontend\AccountController@getDataTrainingRoadmapOther' )->name('frontend.account.getdataTrainingRoadmapOther');

Route::post('/save-course-bookmark/{course_id}/{course_type}/{my_course}', 'Frontend\HomeController@saveCourseBookmark')
    ->name('frontend.home.save_course_bookmark')
    ->where('course_id', '[0-9]+')
    ->where('course_type', '[0-9]+');

Route::post('/remove-course-bookmark/{course_id}/{course_type}/{my_course}', 'Frontend\HomeController@removeCourseBookmark')
    ->name('frontend.home.remove_course_bookmark')
    ->where('course_id', '[0-9]+')
    ->where('course_type', '[0-9]+');

Route::get('/search','Frontend\HomeController@search')->name('home.search');

Route::group(['prefix' => '/plan-app', 'middleware' => 'auth'], function() {
    Route::get('/', 'Frontend\PlanAppController@index')->name('frontend.plan_app');
    Route::get('/getdata', 'Frontend\PlanAppController@getData')->name('frontend.plan_app.getdata');
    Route::get('/form/{course}/{type}', 'Frontend\PlanAppController@form')->name('frontend.plan_app.get')->where('course','[0-9]+')->where('type','[0-9]+');
    Route::post('/form/{course}/{type}', 'Frontend\PlanAppController@form')->name('frontend.plan_app.form')->where('course','[0-9]+')->where('type','[0-9]+');
    Route::get('/form-evaluation/{course}/{type}', 'Frontend\PlanAppController@formEvaluation')->name('frontend.plan_app.get.evaluation')->where('course','[0-9]+')->where('type','[0-9]+');
    Route::post('/form-evaluation/{course}/{type}', 'Frontend\PlanAppController@saveFormEvaluation')->name('frontend.plan_app.form.evaluation')->where('course','[0-9]+')->where('type','[0-9]+');
    Route::post('/delete', 'Frontend\PlanAppController@delete');
    Route::post('/send-mail-approve', 'Frontend\PlanAppController@sendMailApprove')->name('frontend.plan_app.send_mail_approve');
});

Route::post('load-media', 'MediaController@showModal')->name('load_media');

Route::post('load-ajax-media', 'MediaController@ajaxLoadMedia')->name('load_ajax_media');

Route::prefix(url_mobile()? 'AppM':'')->group(function() {
    Route::group(['prefix' => '/qrcode', 'middleware' => 'auth'], function() {
        Route::get('/', 'QrcodeController@index')->name('qrcode');

        Route::get('/process', 'QrcodeController@process')->name('qrcode_process');

        Route::get('/message', 'QrcodeController@message')->name('qrcode_message');
    });
});

Route::get('/roadmap/getData', 'Frontend\HomeController@getDataRoadmap')->name('frontend.home.user_roadmap.getDataRoadmap');

//Thống kê của tôi
Route::get('/dashboard_by_user', 'Frontend\DashboardByUserController@index')->name('frontend.dashboard_by_user')->middleware('pagespeed');

//Lịch sử tương tác
Route::get('/interaction_history', 'Frontend\HomeController@interactionHistory')->name('frontend.interaction_history');
Route::post('/get_interaction_history', 'Frontend\HomeController@getInteractionHistory')->name('frontend.get_interaction_history');

/*Route::get('/test', function () {
    if (\Modules\VirtualClassroom\Helpers\BBBApi::isConnect()) {
        $bbb = new \Modules\VirtualClassroom\Helpers\BBBApi('test', 'Test');
        $bbb->create();
        return redirect()->to($bbb->join(2));

    }
});*/

/**Cập nhật token. Dùng trong khi thi**/
Route::get('refresh-csrf', function () {
   return csrf_token();
})->middleware('auth');
