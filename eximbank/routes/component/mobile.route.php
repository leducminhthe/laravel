<?php

Route::get('/', 'Mobile\HomeController@index')->name('themes.mobile.frontend.home');

Route::post('/night-mode-mobile', 'Mobile\HomeController@settingNightMode')->name('themes.mobile.night_mode');

Route::get('/faq-mobile', 'Mobile\FAQController@index')->name('themes.mobile.faq.frontend.index');

Route::get('/search-mobile', 'Mobile\SearchController@index')->name('themes.mobile.frontend.search.index');

Route::group(['prefix' => '/attendance-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\AttendanceController@index')->name('theme.mobile.frontend.attendance');
    Route::get('/getdata/', 'Mobile\AttendanceController@getData')->name('theme.mobile.frontend.attendance.getData');
    Route::get('/course/{course_id}/', 'Mobile\AttendanceController@showStudents')->name('theme.mobile.frontend.attendance.course')->where('course_id', '[0-9]+');
    Route::get('/course/{course_id}/students', 'Mobile\AttendanceController@getStudents')->name('theme.mobile.frontend.attendance.getStudents')->where('course_id', '[0-9]+');
    Route::post('/course/showModal/{course_id}/{schedule_id}/', 'Mobile\AttendanceController@showModal')->name('theme.mobile.frontend.attendance.show_modal')->where('course_id', '[0-9]+')->where('schedule_id', '[0-9]+');

    Route::get('/course/showModalImageUser/{user_id}/', 'Mobile\AttendanceController@showModalImageUser')
    ->name('theme.mobile.frontend.attendance.show_modal_image_user')
    ->where('user_id', '[0-9]+');
});

Route::group(['prefix' => '/calendar-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\CalendarController@index')->name('theme.mobile.frontend.calendar');
    Route::get('/ajax-calendar', 'Mobile\CalendarController@getData')->name('theme.mobile.frontend.calendar.getdata');
});


Route::group(['prefix' => '/profile-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\ProfileController@index')->name('themes.mobile.frontend.profile');

    Route::get('/getdata-emulation-badge', 'Mobile\ProfileController@getDataEmulationBadge')
        ->name('themes.mobile.frontend.profile.getdata.emulation_badge');

    Route::get('/qr-code', 'Mobile\ProfileController@qrCodeUser')
        ->name('themes.mobile.frontend.profile.qr_code');

    Route::post('/change-avatar', 'Mobile\ProfileController@changeAvatar')
        ->name('themes.mobile.frontend.profile.change_avatar');

    Route::get('/training-process-mobile', 'Mobile\ProfileController@trainingProcess')
        ->name('themes.mobile.frontend.training_process');

    Route::get('/my-course-mobile', 'Mobile\ProfileController@myCourse')
        ->name('themes.mobile.frontend.my_course');

    Route::get('/get-rank-mobile', 'Mobile\ProfileController@getRank')
        ->name('themes.mobile.frontend.get_rank');

    Route::post('/remove-account', 'Mobile\ProfileController@removeAccount')
        ->name('themes.mobile.frontend.remove_account');

    Route::get('/my-promotion-mobile', 'Mobile\ProfileController@myPromotion')
        ->name('themes.mobile.frontend.my_promotion');

    Route::get('/my-course-like-mobile', 'Mobile\ProfileController@myCourseLike')
        ->name('themes.mobile.frontend.my_course_like');
});

Route::group(['prefix' => '/chart-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\ChartController@chart')->name('themes.mobile.frontend.chart');
    Route::post('/data-chart', 'Mobile\ChartController@dataChart')->name('themes.mobile.frontend.chart.data');

    Route::get('/training-roadmap-course-mobile', 'Mobile\ChartController@trainingRoadmapCourse')
        ->name('themes.mobile.frontend.training_roadmap_course');
});

Route::group(['prefix' => '/profile-unit-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\ProfileUnitController@index')->name('themes.mobile.frontend.profile_unit');
    Route::get('/{user_id}/detail', 'Mobile\ProfileUnitController@detail')
        ->name('themes.mobile.frontend.profile_unit.detail')
        ->where('user_id', '[0-9]+');
    Route::get('/{user_id}/detail/{type}/model', 'Mobile\ProfileUnitController@detailModel')
        ->name('themes.mobile.frontend.profile_unit.detail_model')
        ->where('type', '[0-9]+')
        ->where('user_id', '[0-9]+');

    Route::get('/{user_id}/data-online', 'Mobile\ProfileUnitController@dataOnline')->name('themes.mobile.frontend.profile_unit.data_online')->where('user_id', '[0-9]+');
    Route::get('/{user_id}/data-offline', 'Mobile\ProfileUnitController@dataOffline')->name('themes.mobile.frontend.profile_unit.data_offline')->where('user_id', '[0-9]+');
    Route::get('/{user_id}/data-quiz', 'Mobile\ProfileUnitController@dataQuiz')->name('themes.mobile.frontend.profile_unit.data_quiz')->where('user_id', '[0-9]+');

    Route::get('/{user_id}/training-by-title', 'Mobile\ProfileUnitController@trainingByTitle')
    ->name('themes.mobile.frontend.profile_unit.training_by_title')
    ->where('user_id', '[0-9]+');

    Route::get('/{user_id}/training-process', 'Mobile\ProfileUnitController@trainingProcess')
    ->name('themes.mobile.frontend.profile_unit.training_process')
    ->where('user_id', '[0-9]+');

    Route::post('/{user_id}/info-user', 'Mobile\ProfileUnitController@infoUser')
    ->name('themes.mobile.frontend.profile_unit.info_user')
    ->where('user_id', '[0-9]+');
});

Route::group(['prefix' => '/dasboard-unit-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\DashboardUnitController@index')->name('themes.mobile.frontend.dashboard_unit');
    Route::get('/user-online', 'Mobile\DashboardUnitController@userOnline')->name('themes.mobile.frontend.dashboard_unit.user_online');
    Route::get('/user-offline', 'Mobile\DashboardUnitController@userOffline')->name('themes.mobile.frontend.dashboard_unit.user_offline');
    Route::get('/user-quiz', 'Mobile\DashboardUnitController@userQuiz')->name('themes.mobile.frontend.dashboard_unit.user_quiz');

    Route::post('/search-course', 'Mobile\DashboardUnitController@searchCourse')->name('themes.mobile.frontend.dashboard_unit.search_course');

    Route::get('/data-user-online', 'Mobile\DashboardUnitController@dataUserOnline')->name('themes.mobile.frontend.dashboard_unit.data_user_online');
    Route::get('/data-user-offline', 'Mobile\DashboardUnitController@dataUserOffline')->name('themes.mobile.frontend.dashboard_unit.data_user_offline');
    Route::get('/data-user-quiz', 'Mobile\DashboardUnitController@dataUserQuiz')->name('themes.mobile.frontend.dashboard_unit.data_user_quiz');

    Route::post('/sync-data', 'Mobile\DashboardUnitController@syncData')->name('themes.mobile.frontend.dashboard_unit.sync_data');
});

Route::group(['prefix' => '/online-course-mobile', 'middleware' => ['auth']], function () {
    Route::get('/', 'Mobile\OnlineController@index')
        ->name('themes.mobile.frontend.online.index');

    Route::get('/view-scorm/{id}/{activity_id}/{attempt_id}', 'Mobile\OnlineController@viewScorm')
        ->name('themes.mobile.frontend.online.view_scorm')
        ->where('id', '[0-9]+')
        ->where('activity_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::get('/view-xapi/{id}/{activity_id}/{attempt_id}', 'Mobile\OnlineController@viewXapi')
        ->name('themes.mobile.frontend.online.view_xapi')
        ->where('id', '[0-9]+')
        ->where('activity_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::get('/detail/{course_id}', 'Mobile\OnlineController@detail')
        ->name('themes.mobile.frontend.online.detail')
        ->where('course_id', '[0-9]+');

    Route::get('/detail/{id}/activity/{aid}/{lesson}', 'Mobile\OnlineController@goActivity')
        ->name('themes.mobile.frontend.online.goactivity')
        ->where('id', '[0-9]+')
        ->where('aid', '[0-9]+')
        ->where('lesson', '[0-9]+');

    Route::post('/comment/{course_id}', 'Mobile\OnlineController@comment')
        ->name('themes.mobile.frontend.online.comment')
        ->where('course_id', '[0-9]+');

    Route::post('/ask_answer/{course_id}', 'Mobile\OnlineController@ask_answer')
        ->name('themes.mobile.frontend.online.ask_answer')
        ->where('course_id', '[0-9]+');

    Route::post('/note/{course_id}', 'Mobile\OnlineController@note')
        ->name('themes.mobile.frontend.online.note')
        ->where('course_id', '[0-9]+');

    Route::post('/remove-note-course', 'Mobile\OnlineController@removeNoteCourse')
        ->name('themes.mobile.frontend.online.remove_note_course');

    Route::post('/modal-object/{course_id}', 'Mobile\OnlineController@modalObject')
        ->name('themes.mobile.frontend.online.modal_object')
        ->where('course_id', '[0-9]+');

    Route::get('/go-activity/{course_id}', 'Mobile\OnlineController@goActivityDetail')
        ->name('themes.mobile.frontend.online.detail.go_activity')
        ->where('course_id', '[0-9]+');

    Route::post('/modal-note-course/{course_id}', 'Mobile\OnlineController@modalNoteCourse')
        ->name('themes.mobile.frontend.online.modal_note_course')
        ->where('course_id', '[0-9]+');

    Route::post('/modal-result-course/{course_id}', 'Mobile\OnlineController@modalResultCourse')
        ->name('themes.mobile.frontend.online.modal_result_course')
        ->where('course_id', '[0-9]+');

    Route::get('/modal-history-course/{course_id}', 'Mobile\OnlineController@modalHistoryCourse')
        ->name('themes.mobile.frontend.online.modal_history_course')
        ->where('course_id', '[0-9]+');

    Route::get('/modal-history-detail-course/{course_id}/{course_activity_id}', 'Mobile\OnlineController@modalHistoryDetailCourse')
        ->name('themes.mobile.frontend.online.modal_history_detail_course')
        ->where('course_id', '[0-9]+')
        ->where('course_activity_id', '[0-9]+');
});

Route::group(['prefix' => '/offline-course-mobile', 'middleware' => ['auth']], function () {
    Route::get('/', 'Mobile\OfflineController@index')
        ->name('themes.mobile.frontend.offline.index');

    Route::post('/check-pdf', 'Mobile\OfflineController@checkPDF')
        ->name('themes.mobile.frontend.offline.check_pdf');

    Route::post('/view-pdf', 'Mobile\OfflineController@viewPDF')
        ->name('themes.mobile.frontend.offline.view_pdf');

    Route::get('/{course_id}', 'Mobile\OfflineController@detail')
        ->name('themes.mobile.frontend.offline.detail')
        ->where('course_id', '[0-9]+');

    Route::post('/comment/{course_id}', 'Mobile\OfflineController@comment')
        ->name('themes.mobile.frontend.offline.comment')
        ->where('course_id', '[0-9]+');

    Route::get('/go-activity/{course_id}', 'Mobile\OfflineController@goActivity')
        ->name('themes.mobile.frontend.offline.detail.go_activity')
        ->where('course_id', '[0-9]+');

    Route::get('/rating-teacher/{course_id}', 'Mobile\OfflineController@ratingTeacher')
        ->name('themes.mobile.frontend.offline.detail.rating_teacher')
        ->where('course_id', '[0-9]+');

    Route::get('/edit-rating-teaching/{course_id}', 'Mobile\OfflineController@editRatingTeaching')->name('themes.mobile.frontend.offline.detail.rating_teacher.edit')
    ->where('id', '[0-9]+');

    Route::post('/save-rating-teaching/{course_id}', 'Mobile\OfflineController@saveRatingTeaching')->name('themes.mobile.frontend.offline.detail.rating_teacher.save')
    ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/notify-mobile', 'middleware' => ['auth']], function () {
    Route::get('/', 'Mobile\NotifyController@index')
        ->name('themes.mobile.frontend.notify.index');

    Route::get('/viewed', 'Mobile\NotifyController@viewed')
        ->name('themes.mobile.frontend.notify.viewed');

    Route::get('/not-seen', 'Mobile\NotifyController@notSeen')
        ->name('themes.mobile.frontend.notify.not_seen');

    Route::get('/detail/{id}/{type}', 'Mobile\NotifyController@detail')
        ->name('themes.mobile.frontend.notify.detail')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+');
});

Route::group(['prefix' => '/approve-course-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\ApproveCourseController@index')
        ->name('themes.mobile.frontend.approve_course.course');

    Route::get('/user/{id}/{type}', 'Mobile\ApproveCourseController@course')
        ->name('themes.mobile.frontend.approve_course.user')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+');

    Route::post('/approve/{id}/{type}', 'Mobile\ApproveCourseController@approveCourse')
        ->name('themes.mobile.frontend.approve_course.approve')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+');
});

Route::group(['prefix' => '/manager-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\ManagerController@index')
        ->name('themes.mobile.frontend.manager');

    Route::post('/chart-all-courses', 'Mobile\ManagerController@dataChartAllCourses')
        ->name('themes.mobile.frontend.manager.chart_all_courses');

    Route::post('/chart-user-new', 'Mobile\ManagerController@dataChartUserNew')
        ->name('themes.mobile.frontend.manager.chart_user_new');

    Route::post('/chart-user-by-course', 'Mobile\ManagerController@dataChartUserByCourse')
        ->name('themes.mobile.frontend.manager.chart_user_by_course');
});

Route::group(['prefix' => '/daily-training-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\DailyTrainingVideoController@index')->name('themes.mobile.daily_training.frontend');

    Route::get('/my-video', 'Mobile\DailyTrainingVideoController@myVideo')->name('themes.mobile.daily_training.frontend.my_video');

    Route::get('/cate/{id}', 'Mobile\DailyTrainingVideoController@dailyCate')->name('themes.mobile.daily_training_cate.frontend');

    Route::get('/add-video', 'Mobile\DailyTrainingVideoController@addVideo')->name('themes.mobile.daily_training.frontend.add_video');

    Route::post('/save-video', 'Mobile\DailyTrainingVideoController@saveVideo')->name('themes.mobile.daily_training.frontend.save_video');

    Route::post('/upload-video', 'Mobile\DailyTrainingVideoController@upload')->name('themes.mobile.daily_training.frontend.upload_video');

    Route::post('/disable-video', 'Mobile\DailyTrainingVideoController@disableVideo')->name('themes.mobile.daily_training.frontend.disable_video');

    Route::get('/search-mobile', 'Mobile\DailyTrainingVideoController@search')
        ->name('themes.mobile.daily_training.frontend.search');

    Route::get('/detail-video/{id}', 'Mobile\DailyTrainingVideoController@detailVideo')
        ->name('themes.mobile.daily_training.frontend.detail_video')
        ->where('id', '[0-9]+');

    Route::post('/like-video/{id}', 'Mobile\DailyTrainingVideoController@likeVideo')
        ->name('themes.mobile.daily_training.frontend.like_video')
        ->where('id', '[0-9]+');

    Route::post('/comment-video/{id}', 'Mobile\DailyTrainingVideoController@commentVideo')
        ->name('themes.mobile.daily_training.frontend.comment_video')
        ->where('id', '[0-9]+');

    Route::post('/like-comment-video/{id}', 'Mobile\DailyTrainingVideoController@likeCommentVideo')
        ->name('themes.mobile.daily_training.frontend.like_comment_video')
        ->where('id', '[0-9]+');

});

Route::group(['prefix' => '/career-roadmap-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\CareerRoadmapController@index')->name('themes.mobile.career_roadmap.frontend');

    Route::get('/get-course/{title_id}', 'Mobile\CareerRoadmapController@getCourses')->name('themes.mobile.career_roadmap.frontend.get_courses');

    Route::post('/tree/getChild', 'Mobile\CareerRoadmapController@getChild')->name('themes.mobile.career_roadmap.frontend.tree_folder.get_child');

    Route::post('/save', 'Mobile\CareerRoadmapController@save')->name('themes.mobile.career_roadmap.frontend.save');

    Route::get('/parents', 'Mobile\CareerRoadmapController@getParents')->name('themes.mobile.career_roadmap.frontend.getparents');

    Route::post('/remove-roadmap', 'Mobile\CareerRoadmapController@removeRoadmap')->name('themes.mobile.career_roadmap.frontend.remove_roadmap');

    Route::post('/add-title', 'Mobile\CareerRoadmapController@addTitle')->name('themes.mobile.career_roadmap.frontend.add_title');

    Route::get('/edit', 'Mobile\CareerRoadmapController@edit')->name('themes.mobile.career_roadmap.frontend.edit');

    Route::post('/save-edit-title', 'Mobile\CareerRoadmapController@saveEditTitle')->name('themes.mobile.career_roadmap.frontend.save_edit_title');

    Route::post('/remove-title', 'Mobile\CareerRoadmapController@remove')->name('themes.mobile.career_roadmap.frontend.remove');
});

Route::group(['prefix' => '/guide-mobile', 'middleware' => ['auth']], function (){
    Route::get('', 'Mobile\GuideController@index')->name('themes.mobile.frontend.guide');
    Route::get('/pdf', 'Mobile\GuideController@index')->name('themes.mobile.frontend.guide.pdf');
    Route::get('/video-guide', 'Mobile\GuideController@video')->name('themes.mobile.frontend.guide.video');
    Route::get('/posts-guide', 'Mobile\GuideController@posts')->name('themes.mobile.frontend.guide.posts');
    Route::get('/posts-guide/detail/{id}', 'Mobile\GuideController@postDetail')->name('themes.mobile.frontend.guide.post.detail');
    Route::get('/view-pdf/{id}', 'Mobile\GuideController@viewPDF')->name('themes.mobile.frontend.guide.view_pdf')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/suggest-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\SuggestController@index')->name('themes.mobile.suggest.index');
    Route::post('/save', 'Mobile\SuggestController@save')->name('themes.mobile.suggest.save');
    Route::get('/getdata', 'Mobile\SuggestController@getData')->name('themes.mobile.suggest.get_data');
    Route::get('/modal-comment/{id}', 'Mobile\SuggestController@modalComment')->name('themes.mobile.suggest.get_comment')->where('id', '[0-9]+');
    Route::post('/save-comment/{id}', 'Mobile\SuggestController@saveComment')->name('themes.mobile.suggest.save_comment')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/note-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\NoteMobileController@index')->name('themes.mobile.note_mobile.index');
    Route::get('/getdata', 'Mobile\NoteMobileController@getData')->name('themes.mobile.note_mobile.get_data');
    Route::post('/remove', 'Mobile\NoteMobileController@remove')->name('themes.mobile.note_mobile.remove');
    Route::post('/save', 'Mobile\NoteMobileController@saveNote')->name('themes.mobile.note_mobile.save');
    Route::post('/edit', 'Mobile\NoteMobileController@edit')->name('themes.mobile.note_mobile.edit');
    Route::post('/close', 'Mobile\NoteMobileController@closeNote')->name('themes.mobile.cloe_note_mobile');
});

Route::group(['prefix' => '/survey-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\SurveyController@index')->name('themes.mobile.survey');
    Route::get('/getdata', 'Mobile\SurveyController@getData')->name('themes.mobile.survey.get_data');
    Route::get('/user/{id}', 'Mobile\SurveyController@getSurveyUser')->name('themes.mobile.survey.user')->where('id', '[0-9]+');
    Route::get('/edit/{id}', 'Mobile\SurveyController@editSurveyUser')->name('themes.mobile.survey.user.edit')->where('id', '[0-9]+');
    Route::post('/save', 'Mobile\SurveyController@saveSurveyUser')->name('themes.mobile.survey.user.save');
});

Route::group(['prefix' => '/forums-mobile', 'middleware' => ['auth']], function() {
    Route::get('/', 'Mobile\ForumsController@index')->name('themes.mobile.frontend.forums');

    Route::get('/topic/{id}', 'Mobile\ForumsController@forum')->name('themes.mobile.frontend.forums.topic');

    Route::post('/topic/{id}/delete', 'Mobile\ForumsController@forum_delete')
        ->name('themes.mobile.frontend.forums.deleteforum')
        ->where('id','[0-9]+');

    Route::get('/topic/form/{id}', 'Mobile\ForumsController@form')->name('themes.mobile.frontend.forums.form');

    Route::get('/topic/thread/{id}', 'Mobile\ForumsController@thread')->name('themes.mobile.frontend.forums.thread');

    Route::post('/topic/thread/{id}/cmt','Mobile\ForumsController@thread_cmt')
        ->name('themes.mobile.frontend.forums.comment')
        ->where('id', '[0-9]+');

    Route::post('/topic/thread/{id}/delete','Mobile\ForumsController@comment_delete')
        ->name('themes.mobile.frontend.forums.delete')
        ->where('id', '[0-9]+');

    Route::post('/topic/form/{id}/save', 'Mobile\ForumsController@saveTopic')
        ->name('themes.mobile.frontend.forums.form.save')
        ->where('id', '[0-9]+');

    Route::get('/topic/edit/{id}', 'Mobile\ForumsController@edit')->name('themes.mobile.frontend.forums.edit');

    Route::post('/topic/edit/{id}', 'Mobile\ForumsController@update')->name('themes.mobile.frontend.forums.update');

    Route::get('/topic/thread/comment/edit/{id}','Mobile\ForumsController@edit_cmt')
        ->name('themes.mobile.frontend.forums.comment.edit')
        ->where('id', '[0-9]+');

    Route::post('/topic/thread/comment/update/{id}','Mobile\ForumsController@update_cmt')
        ->name('themes.mobile.frontend.forums.comment.update')
        ->where('id', '[0-9]+');

    Route::post('/topic/thread/comment/like-dislike/{id}','Mobile\ForumsController@like_dislike_cmt')
        ->name('themes.mobile.frontend.forums.comment.like_dislike')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/promotion-mobile', 'middleware' => ['auth']], function () {
    Route::get('/', 'Mobile\PromotionController@index')->name('themes.mobile.front.promotion');
    Route::post('/get-promotion', 'Mobile\PromotionController@get')->name('themes.mobile.front.promotion.get');
    Route::get('/detail/{id}', 'Mobile\PromotionController@detail')->name('themes.mobile.front.promotion.detail');
});

Route::group(['prefix' => '/list-hight-score', 'middleware' => ['auth']], function () {
    Route::get('/', 'Mobile\ListHightSocreController@index')->name('themes.mobile.front.list_hight_score');
});

Route::group(['prefix' => '/history-point', 'middleware' => ['auth']], function () {
    Route::get('/', 'Mobile\ProfileController@historyPoint')->name('themes.mobile.front.history_point');
});

Route::group(['prefix' => '/emulation-badge-list', 'middleware' => ['auth']], function () {
    Route::get('/', 'Mobile\ProfileController@emulationBadgeList')->name('themes.mobile.front.emulation_badge_list');
});

Route::group(['prefix' => '/quiz-result', 'middleware' => ['auth']], function () {
    Route::get('/{user_id}', 'Mobile\ProfileController@quizResult')->name('themes.mobile.front.quiz_result');
});

//ĐỔI MẬT KHẨU
Route::group(['prefix' => '/change-password', 'middleware' => ['auth']], function () {
    Route::get('/', 'Mobile\ChangePassController@index')->name('themes.mobile.front.change_pass');
    Route::post('/save', 'Mobile\ChangePassController@save')->name('themes.mobile.front.change_pass.save');
});

//CHỨNG CHỈ BÊN NGOÀI
Route::group(['prefix' => '/my-certificate-mobile', 'middleware' => ['auth']], function () {
    Route::get('/', 'Mobile\ProfileController@myCertificate')->name('themes.mobile.front.my_certificate');
    Route::post('/save', 'Mobile\ProfileController@saveMyCertificate')->name('themes.mobile.front.my_certificate.save');
    Route::get('/create', 'Mobile\ProfileController@myCertificateEdit')->name('themes.mobile.front.my_certificate.create');
    Route::post('/delete', 'Mobile\ProfileController@myCertificateDelete')->name('themes.mobile.front.my_certificate.delete');
    Route::get('/edit/{id}', 'Mobile\ProfileController@myCertificateEdit')->name('themes.mobile.front.my_certificate.edit');
});


