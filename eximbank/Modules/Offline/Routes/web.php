<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require_once (__DIR__ . '/scorm.route.php');

Route::prefix(url_mobile()? 'AppM':'')->group(function() {
    Route::group(['prefix' => '/offline', 'middleware' => 'auth'], function() {
        Route::post('/register-course/{id}', 'FrontendController@registerCourse')
        ->name('module.offline.register_course')
        ->where('id', '[0-9]+');
    });
});

Route::group(['prefix' => '/offline', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontendController@index')
        ->name('module.offline');

    //Chi tiết khoá học mới
    Route::get('/detail-first/{id}', 'FrontendController@detailFirst')
        ->name('module.offline.detail_first')
        ->where('id', '[0-9]+');

    Route::get('/getdata-schedule/{id}', 'FrontendController@getDataSchedule')
        ->name('module.offline.detail_first.getdata_schedule')
        ->where('id', '[0-9]+');

    Route::get('/detail-new/{id}', 'FrontendController@detailNew')
        ->name('module.offline.detail_new')
        ->where('id', '[0-9]+');

    Route::post('/ajax-rating-level-offline/{id}', 'FrontendController@ajaxRatingLevelOffline')
        ->name('module.offline.detail.ajax_rating_level_offline')
        ->where('id', '[0-9]+');
    ///////////////////////////////////////////////////////////////////////////

    Route::get('/detail/{id}', 'FrontendController@detail')->name('module.offline.detail')->where('id', '[0-9]+'); //Chi tiết khoá học cũ

    Route::get('/detail/{id}/zoom', 'FrontendController@zoom')
        ->name('module.offline.detail.zoom')
        ->where('id', '[0-9]+');

    Route::post('/rating/{id}', 'FrontendController@rating')
        ->name('module.offline.rating')
        ->where('id', '[0-9]+');

    Route::post('/commnent/{id}', 'FrontendController@comment')
        ->name('module.offline.comment')
        ->where('id', '[0-9]+');

    Route::get('/view-pdf/{id}/{key}', 'FrontendController@viewPDF')->name('module.offline.view_pdf')->where('id', '[0-9]+');

    Route::get('/search','FrontendController@search')->name('module.offline.search');

    Route::post('/referer/qrcode/{id}', 'FrontendController@showModalQrcodeReferer')
        ->name('frontend.offline.referer.show_modal')
        ->where('id', '[0-9]+');

    Route::post('/share-course/{id}/{type}', 'FrontendController@shareCourse')
        ->name('module.offline.detail.share_course')
        ->where('id', '[0-9]+')->where('type', '[0-9]+');

    Route::get('/getdata-rating-level/{id}', 'FrontendController@getDataRatingLevel')
        ->name('module.offline.detail.rating_level.getdata')
        ->where('id', '[0-9]+');

    // BÌNH LUẬN KHÓA HỌC
    Route::post('/comment-course-offline/{id}', 'FrontendController@commentCourseOffline')
        ->name('frontend.offline.comment.course_offline')
        ->where('id', '[0-9]+');

    Route::post('/delete-comment-course-offline/{id}', 'FrontendController@deleteCommentCourseOffline')
        ->name('frontend.offline.delete.comment.course_offline')
        ->where('id', '[0-9]+');

    // TỰ ĐỘNG GHI DANH KHI CLICK VÀO HOẠT ĐỘNG KHÓA HỌC ONNLINE
    Route::post('/auto-register-activity-online', 'FrontendController@autoRegisterActivityOnline')
        ->name('frontend.offline.auto_register_activity_online')
        ->where('id', '[0-9]+');

    // ĐÁNH DẤU KHÓA HỌC
    Route::post('/bookmark-offline', 'FrontendController@bookmarkOffline')
        ->name('frontend.offline.bookmark_offline')
        ->where('id', '[0-9]+');

    Route::get('/rating-teaching-organization/{id}', 'FrontendController@ratingTeachingOrganization')
        ->name('module.offline.rating_teaching_organization')
        ->where('id', '[0-9]+');

    Route::get('/edit-rating-teaching-organization/{id}', 'FrontendController@editRatingTeachingOrganization')
        ->name('module.offline.edit_rating_teaching_organization')
        ->where('id', '[0-9]+');

    Route::post('/save-rating-teaching-organization/{id}', 'FrontendController@saveRatingTeachingOrganization')
        ->name('module.offline.save_rating_teaching_organization')
        ->where('id', '[0-9]+');

    Route::post('/ajax-activity', 'FrontendController@ajaxActivity')->name('module.offline.detail.ajax_activity');

    Route::post('/ajax-description-activity', 'FrontendController@ajaxDescriptionActivity')->name('module.offline.detail.ajax_description_activity');

    Route::post('/finish-activity-video', 'FrontendController@finishActivityVideo')->name('module.offline.detail.finish_activity_video');

    Route::post('/save-user-time-learn', 'FrontendController@saveUserTimeLearn')->name('module.offline.detail.save_user_time_learn');

    Route::post('/ajax-rating-star/{course_id}', 'FrontendController@ajaxRatingStar')->name('module.offline.detail.ajax_rating_star')->where('course_id', '[0-9]+');

    Route::post('/ajax-document/{course_id}', 'FrontendController@ajaxDocument')->name('module.offline.detail.ajax_document')->where('course_id', '[0-9]+');

    Route::get('/getdata-document/{id}', 'FrontendController@getDataDocument')
    ->name('module.offline.detail.document.getdata')
    ->where('id', '[0-9]+');

    //hoạt động khảo sát
    Route::get('/survey-user/{id}/{aid}', 'FrontendController@getSurveyUser')->name('module.offline.survey.user')->where('id', '[0-9]+')->where('aid', '[0-9]+');
    Route::get('/edit-survey-user/{id}/{aid}/{user_id}', 'FrontendController@editSurveyUser')->name('module.offline.survey.user.edit')->where('id', '[0-9]+')->where('aid', '[0-9]+')->where('user_id', '[0-9]+');
    Route::post('/save-offline-survey-user', 'FrontendController@saveOfflineSurveyUser')->name('module.offline.survey.user.save');

    Route::get('/ajax_history_activity_course/{course_id}', 'FrontendController@ajaxHistoryActivityCourse')->name('module.offline.detail.ajax_history_activity_course')->where('course_id', '[0-9]+');
});

Route::group(['prefix' => '/student-cost', 'middleware' => 'auth'], function() {
    Route::get('/','FrontendController@studentCost')->name('module.offline.student_cost');

    Route::get('/getdata', 'FrontendController@getDataCourse')->name('module.offline.student_cost.getdata');

    Route::post('/save', 'FrontendController@saveStudentCost')->name('module.offline.student_cost.save');

    Route::post('/modal', 'FrontendController@getModalStudentCost')->name('module.offline.student_cost.modal');
});

/* backend */
Route::group(['prefix' => '/admin-cp/offline', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.offline.management')->middleware('permission:offline-course');

    Route::get('/getdata', 'BackendController@getData')->name('module.offline.getdata')->middleware('permission:offline-course');

    Route::get('/edit/{id}', 'BackendController@form')->name('module.offline.edit')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    Route::get('/create', 'BackendController@form')->name('module.offline.create')->middleware('permission:offline-course-create');

    Route::post('/save', 'BackendController@save')->name('module.offline.save')->middleware('permission:offline-course-edit|offline-course-create');

    Route::post('/remove', 'BackendController@remove')->name('module.offline.remove')->middleware('permission:offline-course-delete');

    Route::post('/approve', 'BackendController@approve')->name('module.offline.approve')->middleware('permission:offline-course-approve');

    Route::post('/send-mail-approve', 'BackendController@sendMailApprove')->name('module.offline.send_mail_approve');

    Route::post('/send-mail-change', 'BackendController@sendMailChange')->name('module.offline.send_mail_change');

    Route::post('/ajax-get-course-code', 'BackendController@ajaxGetCourseCode')->name('module.offline.ajax_get_course_code');

    Route::post('/ajax-get-subject', 'BackendController@ajaxGetSubject')->name('module.offline.ajax_get_subject');

    Route::post('/ajax-isopen-publish', 'BackendController@ajaxIsopenPublish')->name('module.offline.ajax_isopen_publish');

    Route::post('/import-register-multiple-course', 'BackendController@importRegisterMultipleCourse')->name('module.offline.import_register_multiple_course');

    Route::post('/import-result-multiple-course', 'BackendController@importResultMultipleCourse')->name('module.offline.import_result_multiple_course');

    Route::get('/filter-location','BackendController@filterLocation')->name('module.offline.filter.location')->middleware('permission:offline-course');

    Route::get('/filter-traininglocation','BackendController@filterTrainingLocation')->name('module.offline.filter.traininglocation')->middleware('permission:offline-course');

    //update chạy cron hoàn thành khoá
    Route::post('/{id}/update-result-by-condition', 'BackendController@updateResultByCondition')->name('module.offline.update_result_by_condition')->where('id', '[0-9]+');

    //history
    Route::get('/history/getdata/{id}', 'BackendController@getDataHistory')->name('module.offline.history.getdata')->where('id', '[0-9]+')->middleware('permission:offline-course');
    //upload file
    Route::post('/uploadfile', 'BackendController@uploadfile')->name('module.offline.uploadfile');
    //Thư viện file
    Route::get('/get-data-library-file/{course_id}', 'BackendController@getDataLibraryFile')->name('module.offline.get_data_library_file')->middleware('permission:offline-course');
    Route::post('/library-file-remove', 'BackendController@removeLibraryFile')->name('module.offline.library_file_remove');

    //Tài liệu học tập
    Route::post('/upload-document/{course_id}', 'BackendController@uploadDocument')->name('module.offline.upload_document')->where('course_id', '[0-9]+');
    Route::get('/get-data-document/{course_id}', 'BackendController@getDataDocument')->name('module.offline.get_data_document')->where('course_id', '[0-9]+');
    Route::post('/remove-document', 'BackendController@removeDocument')->name('module.offline.remove_document');
    /////////////////////////////////////////////////////////////////////////////

    Route::post('/lock', 'BackendController@lockCourse')->name('module.offline.lock');

    // SAO CHÉP KHÓA HỌC
    Route::post('/copy', 'BackendController@copy')->name('module.offline.copy');

    // Đánh GIÁ KHÓA HỌC
    Route::post('/save-ratting-course/{course_id}', 'BackendController@saveRattingCourse')->where('course_id', '[0-9]+')->name('module.offline.save_ratting_course');

    Route::post('/modal-info/{course_id}', 'BackendController@modalInfo')->where('course_id', '[0-9]+')->name('module.offline.modal_info');

    Route::post('/modal-class/{course_id}', 'BackendController@modalClass')->where('course_id', '[0-9]+')->name('module.offline.modal_class');

    Route::get('/object/{id}', 'BackendController@form')->name('module.offline.edit_object')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    Route::get('/cost/{id}', 'BackendController@form')->name('module.offline.edit_cost')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');
    Route::get('/cost_student/{id}', 'BackendController@form')->name('module.offline.edit_cost_student')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');
    Route::get('/condition/{id}', 'BackendController@form')->name('module.offline.edit_condition')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');
    Route::get('/document/{id}', 'BackendController@form')->name('module.offline.edit_document')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');
    Route::get('/history/{id}', 'BackendController@form')->name('module.offline.edit_history')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');
    Route::get('/upload/{id}', 'BackendController@form')->name('module.offline.edit_upload')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');
    Route::get('/userpoint/{id}', 'BackendController@form')->name('module.offline.edit_userpoint')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');
    Route::get('/rating_level/{id}', 'BackendController@form')->name('module.offline.edit_rating_level')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    //Thiết lập tham gia khóa học
    Route::get('/setting_join/{id}', 'BackendController@form')->name('module.offline.edit_setting_join')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');
    Route::get('/setting_join/{id}/get', 'BackendController@getSettingJoin')->name('module.offline.get_setting_join')->where('id', '[0-9]+');
    Route::post('/setting_join/{id}/save', 'BackendController@saveSettingJoin')->name('module.offline.save_setting_join')->where('id', '[0-9]+');
    Route::post('/setting_join/{id}/remove', 'BackendController@removeSettingJoin')->name('module.offline.remove_setting_join')->where('id', '[0-9]+');
    Route::post('/setting_join/{id}/change-data-register', 'BackendController@changeDateRegister')->name('module.offline.setting_join.change_date_register')->where('id', '[0-9]+');
    Route::post('/setting_join/{id}/import', 'BackendController@importSettingJoin')->name('module.offline.setting_join.import')->where('id', '[0-9]+');
    Route::post('/run-cron-setting_join/{id}', 'BackendController@runCronSettingJoin')->name('module.offline.run_cron_setting_join')->where('id', '[0-9]+');

    // ĐIỀU KIỆN TIÊN QUYẾT
    Route::get('/prerequisite-condition/{id}', 'BackendController@form')->name('module.offline.edit.prerequisite_condition')
    ->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    Route::post('/save/prerequisite-condition/{id}', 'BackendController@savePrerequisite')->name('module.offline.edit.save_prerequisite_condition')
    ->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    // ĐIỀU KIỆN GHI DANH
    Route::get('/condition-register/{id}', 'BackendController@form')->name('module.offline.edit.condition_register')
    ->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    Route::post('/save/condition-register/{id}', 'BackendController@saveConditionRegister')->name('module.offline.edit.save_condition_register')
    ->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    // LỚP HỌC
    Route::get('/class/{id}', 'BackendController@form')->name('module.offline.class')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    Route::get('class/{id}/getdata', 'ClassController@index')->name('module.offline.class.getdata')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    Route::post('class/{courseId}/edit', 'ClassController@editClass')->name('module.offline.class.edit_class')
        ->where('id', '[0-9]+')
        ->middleware('permission:offline-course-edit');

    Route::post('class/{courseId}/remove', 'ClassController@removeClass')->name('module.offline.class.remove_class')
        ->where('id', '[0-9]+')
        ->middleware('permission:offline-course-edit');

    Route::post('class/{courseId}/save', 'ClassController@saveClass')->name('module.offline.class.save_class')
        ->where('id', '[0-9]+')
        ->middleware('permission:offline-course-create');

    Route::get('/{id}/class/{class_id}/teacher', 'TeacherController@index')->name('module.offline.teacher')->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course-teacher');

    Route::post('/{id}/class/{class_id}/teacher/save', 'TeacherController@save')->name('module.offline.teacher.class.save')->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course-teacher');

    Route::get('/{id}/class/{class_id}/register', 'RegisterController@index')
    ->name('module.offline.register')->where('id', '[0-9]+')
    ->where('class_id', '[0-9]+')
    ->middleware('permission:offline-course-register');

    Route::get('/{id}/class_default/register', 'RegisterController@classDefault')->name('module.offline.register.default')->where('id', '[0-9]+')->middleware('permission:offline-course-register');

    Route::get('/{id}/class/{class_id}/monitoring-staff', 'MonitoringStaffController@index')->name('module.offline.monitoring_staff')->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course');
    Route::get('/{id}/class/{class_id}/rating-level', 'RatingLevelController@listReport')->name('module.offline.rating_level')->where('id', '[0-9]+')->where('class_id', '[0-9]+');

    // GIẢNG VIÊN KHÓA HỌC
    Route::post('/ajax-get-teacher', 'TeacherController@ajaxGetTeacher')->name('module.offline.ajax_get_teacher')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    Route::post('/ajax-save-teacher-note', 'TeacherController@ajaxSaveTeacherNote')->name('module.offline.ajax_save_teacher_note')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    Route::post('/ajax-save-teacher-tnt', 'TeacherController@ajaxSaveTeacherTNT')->name('module.offline.ajax_save_teacher_tnt')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    // LỊCH HỌC
    Route::get('/{id}/class/{class_id}/schedule', 'ScheduleController@index')->name('module.offline.schedule')->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course-edit');

    Route::post('/{courseId}/class/{class_id}/save-schedule', 'ScheduleController@saveSchedule')->name('module.offline.save_schedule')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::post('/{courseId}/class/{class_id}/remove-schedule', 'ScheduleController@removeSchedule')->name('module.offline.remove_schedule')->where('id', '[0-9]+')->middleware('permission:offline-course-delete');

    Route::post('/{courseId}/class/change-teacher', 'ScheduleController@changeTeacher')->name('module.offline.change_teacher')->middleware('permission:offline-course-delete');

    Route::post('/{courseId}/class/change-teacher-tutors', 'ScheduleController@changeTeacherTutors')->name('module.offline.change_teacher_tutors');

    Route::post('/{courseId}/class/{class_id}/training-location-schedule', 'ScheduleController@trainingLocationSchedule')->name('module.offline.training_location_schedule')->middleware('permission:offline-course-delete');

    Route::post('/{courseId}/import-schedule', 'ScheduleController@importSchedule')->name('module.offline.import_schedule')->middleware('permission:offline-course-edit');

    Route::post('/{courseId}/save-import-schedule', 'ScheduleController@saveImportSchedule')->name('module.offline.save_import_schedule')->middleware('permission:offline-course-edit');

    Route::get('/{courseId}/export-template-schedule', 'ScheduleController@exportTemplateSchedule')->name('module.offline.export_template_schedule')->middleware('permission:offline-course-edit');

    Route::get('/{courseId}/{classId}/create-schedule', 'ScheduleController@form')->name('module.offline.create_schedule')->middleware('permission:offline-course-edit');

    Route::get('/{courseId}/edit-schedule/{classId}/{id}', 'ScheduleController@form')->name('module.offline.edit_schedule')->middleware('permission:offline-course-edit');

    Route::post('/{courseId}/save-new-teacher/{classId}/{id}', 'ScheduleController@saveNewTeacher')->name('module.offline.save_new_teacher')->middleware('permission:offline-course-edit');

    Route::post('/{courseId}/delete-new-teacher/{classId}/{id}', 'ScheduleController@deleteNewTeacher')->name('module.offline.delete_new_teacher')->middleware('permission:offline-course-edit');
    ////////////////////////

    Route::get('/meeting/{id}', 'MeetingController@index')->name('module.offline.meeting')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');
    Route::post('/{id}/meeting/save', 'MeetingController@save')->name('module.offline.meeting.save')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    //Import ghi danh trong tab lớp học
    Route::post('/import-register-class/{courseId}', 'RegisterController@importRegisterClass')
        ->name('module.offline.import_register_class')
        ->where('courseId', '[0-9]+');
    Route::post('/{courseId}/class/save-import-register', 'RegisterController@saveImportRegisterClass')
        ->name('module.offline.save_import_register_class')
        ->where('id', '[0-9]+')->where('class_id','[0-9]+');

    // BÀI HỌC
    Route::get('/{course_id}/class/{class_id}/schedule/{schedule_id}/activity-by-schedule', 'ActivityController@activityBySchedule')
        ->name('module.offline.activity_by_schedule')
        ->where('course_id', '[0-9]+')
        ->where('class_id','[0-9]+')
        ->where('schedule_id','[0-9]+');

    //Cập nhật điều kiện hoàn thành hoạt động
    Route::post('/{course_id}/class/{class_id}/schedule/{schedule_id}/update-condition-activity', 'ActivityController@updateConditionActivity')
        ->name('module.offline.activity.update_condition_activity')
        ->where('course_id', '[0-9]+')
        ->where('class_id','[0-9]+')
        ->where('schedule_id','[0-9]+');

    Route::post('/save-lesson/{course_id}', 'ActivityController@saveLesson')->name('module.offline.save_lesson')->where('course_id', '[0-9]+');
    Route::post('/remove-lesson', 'ActivityController@removeLesson')->name('module.offline.remove_lesson');
    Route::post('/edit-lesson-name/{course_id}', 'ActivityController@editLessonNameActivity')->name('module.offline.activity.edit_lesson_name')->where('course_id', '[0-9]+');

    Route::get('/activity-lesson/{id}', 'ActivityController@form')->name('module.offline.edit_activity_lesson')->where('id', '[0-9]+');
    Route::post('/modal-add-activity/{course_id}', 'ActivityController@modalAddActivity')->name('module.offline.modal_add_activity')->where('course_id', '[0-9]+');
    Route::post('/modal-activity/{course_id}', 'ActivityController@modalActivity')->name('module.offline.modal_activity')->where('course_id', '[0-9]+');
    Route::post('/activity/{course_id}/save/{activity}', 'ActivityController@saveActivity')->name('module.offline.activity.save')->where('course_id', '[0-9]+')->where('activity', '[0-9]+');
    Route::post('/activity/{course_id}/update-numorder', 'ActivityController@updateNumOrder')->name('module.offline.activity.update_numorder')->where('course_id', '[0-9]+');
    Route::post('/activity/{course_id}/remove', 'ActivityController@remove')->name('module.offline.activity.remove')->where('course_id', '[0-9]+');
    Route::post('/activity/{course_id}/update-status-activity', 'ActivityController@updateStatusActivity')->name('module.offline.activity.update_status_activity')->where('course_id', '[0-9]+');

    Route::post('/activity/{course_id}/edit-name', 'ActivityController@editNameActivity')->name('module.offline.activity.edit_name')->where('course_id', '[0-9]+')->where('activity', '[0-9]+');
    Route::post('/activity/{course_id}/update-lesson', 'ActivityController@updateLesson')->name('module.offline.activity.update_lesson')->where('course_id', '[0-9]+');
    Route::match(['get', 'post'], '/activity/{course_id}/load-data/{func}', 'ActivityController@loadData')->name('module.offline.activity.loaddata')->where('course_id', '[0-9]+');
    Route::get('/{course_id}/class/{class_id}/report_teams/{schedule_id}', 'ActivityController@reportTeams')->name('module.offline.activity.report_teams')->where('course_id', '[0-9]+')->where('class_id', '[0-9]+')->where('schedule_id', '[0-9]+');

    Route::post('/report_teams-info', 'ActivityController@reportTeamsInfo')->name('module.offline.activity.report_teams_info')->where('course_id', '[0-9]+')->where('class_id', '[0-9]+')->where('schedule_id', '[0-9]+');

    Route::get('/export-report-teams-info/{id}', 'ActivityController@exportReportTeamsInfo')->name('module.offline.activity.export_report_teams_info');

    Route::post('/update-report-teams-info', 'ActivityController@updateReportTeamsInfo')->name('module.offline.activity.update_report_teams_info');

    Route::get('/{course_id}/class/{class_id}/schedule/{schedule_id}/report_elearning', 'ActivityController@reportElearning')
        ->name('module.offline.activity.report_elearning')
        ->where('course_id', '[0-9]+')
        ->where('class_id', '[0-9]+')
        ->where('schedule_id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/offline/edit/{id}', 'middleware' => 'auth'], function() {
    Route::post('/save-object', 'BackendController@saveObject')->name('module.offline.save_object')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::get('/get-object', 'BackendController@getObject')->name('module.offline.get_object')->where('id', '[0-9]+')->middleware('permission:offline-course');

    Route::post('/remove-object', 'BackendController@removeObject')->name('module.offline.remove_object')->where('id', '[0-9]+')->middleware('permission:offline-course-delete');

    Route::post('/save-cost', 'BackendController@saveCost')->name('module.offline.save_cost')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::post('/save-commit-date', 'BackendController@saveCommitDate')->name('module.offline.save_commit_date')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::post('/save-student-cost', 'BackendController@saveStudentCost')->name('module.offline.save_student_cost')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::post('/modal-student-cost', 'BackendController@getModalStudentCost')->name('module.offline.modal_student_cost')->where('id', '[0-9]+');

    Route::post('/save-condition', 'BackendController@saveCondition')->name('module.offline.save_condition')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');


    Route::post('/check-unit-child', 'BackendController@getChild')->name('module.offline.get_child')->where('id', '[0-9]+');

    Route::get('/get-tree-child', 'BackendController@getTreeChild')
        ->name('module.offline.get_tree_child')
        ->where('id', '[0-9]+');


    Route::post('/remove-teacher', 'TeacherController@remove')
        ->name('module.offline.remove_teacher')
        ->where('id', '[0-9]+');

    Route::post('/teacher-save-note', 'TeacherController@saveNote')
        ->name('module.offline.teacher.save_note')
        ->where('id', '[0-9]+');
    //Cán bộ theo dõi
    Route::post('/save-monitoring-staff', 'MonitoringStaffController@save')
        ->name('module.offline.save_monitoring_staff')
        ->where('id', '[0-9]+');

    Route::get('/get-monitoring-staff', 'MonitoringStaffController@getData')
        ->name('module.offline.get_monitoring_staff')
        ->where('id', '[0-9]+');

    Route::post('/remove-monitoring-staff', 'MonitoringStaffController@remove')
        ->name('module.offline.remove_monitoring_staff')
        ->where('id', '[0-9]+');

    Route::post('/monitoring-staff-save-note', 'MonitoringStaffController@saveNote')
        ->name('module.offline.monitoring_staff.save_note')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/offline/register/{id}', 'middleware' => 'auth'], function() {

    Route::get('/getdata/class/{class_id}', 'RegisterController@getData')
        ->name('module.offline.register.getdata')
        ->where('id', '[0-9]+')->where('class_id','[0-9]+')->middleware('permission:offline-course-register');

    Route::get('/getDataNotRegister/class/{class_id}', 'RegisterController@getDataNotRegister')
        ->name('module.offline.register.getDataNotRegister')
        ->where('id', '[0-9]+')->where('class_id','[0-9]+')->middleware('permission:offline-course-register');

    Route::post('/save', 'RegisterController@save')
        ->name('module.offline.register.save')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-register-create');

    Route::post('/remove', 'RegisterController@remove')
        ->name('module.offline.register.remove')
        ->where('id', '[0-9]+')
        ->middleware('permission:offline-course-register-delete');

    Route::post('/import-register/class/{class_id}', 'RegisterController@importRegister')
        ->name('module.offline.register.import_register')
        ->where('id', '[0-9]+')->where('class_id','[0-9]+');

    Route::post('/approve', 'RegisterController@approve')
        ->name('module.offline.register.approve')
        ->where('id', '[0-9]+')
        ->middleware('permission:offline-course-register-approve');

    Route::post('/add-to-quiz', 'RegisterController@addToQuiz')
        ->name('module.offline.register.add_to_quiz')
        ->where('id', '[0-9]+');

    Route::get('/export-register/class/{class_id}', 'RegisterController@exportRegister')
        ->name('module.offline.register.export_register')
        ->where('id', '[0-9]+')->where('class_id','[0-9]+');

    Route::post('/invite-user-register', 'RegisterController@inviteUserRegister')->name('module.offline.register.invite_user')->where('id', '[0-9]+');

    Route::get('/invite-user-register/getdata', 'RegisterController@getDataInviteUserRegister')->name('module.offline.register.getdata.invite_user')->where('id', '[0-9]+');

    Route::post('/invite-user-register/remove', 'RegisterController@removeInviteUserRegister')->name('module.offline.register.remove.invite_user')->where('id', '[0-9]+');

    Route::post('/send-mail-user-registed', 'RegisterController@sendMailUserRegisted')->name('module.offline.register.send_mail_user_registed')->where('id', '[0-9]+');

    Route::post('/modal-info/{register_id}', 'RegisterController@modalInfo')->where('register_id', '[0-9]+')->name('module.offline.register.modal_info');

    Route::get('/class/{class_id}/create', 'RegisterController@form')->where('id', '[0-9]+')->where('class_id', '[0-9]+')->name('module.offline.register.class.create');
    Route::post('/class/{class_id}/save', 'ClassController@saveRegisterClass')->name('module.offline.register.class.save')->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course-register-create');

});

//Điểm danh
Route::group(['prefix' => '/admin-cp/offline/attendance/{id}', 'middleware' => 'auth'], function() {
    Route::get('/class/{class_id}', 'AttendanceController@index')
        ->name('module.offline.attendance')
        ->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::get('/class/{class_id}/get-attendance', 'AttendanceController@getData')
        ->name('module.offline.get_attendance')
        ->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::post('/class/{class_id}/save-all-attendance', 'AttendanceController@saveAll')
        ->name('module.offline.save_all_attendance')
        ->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::post('/save-attendance', 'AttendanceController@save')
        ->name('module.offline.save_attendance')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::post('/remove-attendance', 'AttendanceController@remove')
        ->name('module.offline.remove_attendance')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::post('/save-percent', 'AttendanceController@savePercent')
        ->name('module.offline.save_percent')
        ->where('id', '[0-9]+');

    Route::post('/attendance-save-note', 'AttendanceController@saveNote')
        ->name('module.offline.attendance.save_note')
        ->where('id', '[0-9]+');

    Route::post('/modal-reference', 'AttendanceController@getModalReference')
        ->name('module.offline.modal_reference')
        ->where('id', '[0-9]+');

    Route::post('/save-reference', 'AttendanceController@saveReference')
        ->name('module.offline.save_reference')
        ->where('id', '[0-9]+');

    Route::post('/save-absent', 'AttendanceController@saveAbsent')
        ->name('module.offline.save_absent')
        ->where('id', '[0-9]+');

    Route::post('/save-absent-reason', 'AttendanceController@saveAbsentReason')
        ->name('module.offline.save_absent_reason')
        ->where('id', '[0-9]+');

    Route::post('/save-discipline', 'AttendanceController@saveDiscipline')
        ->name('module.offline.save_discipline')
        ->where('id', '[0-9]+');

    Route::get('/class/{class_id}/attendance/export/{schedule}', 'AttendanceController@export')
        ->name('module.offline.attendance.export')
        ->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::post('/class/{class_id}/attendance/import', 'AttendanceController@import')
        ->name('module.offline.attendance.import')
        ->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course-attendance');
});
/*********************************************************************************/
//Kết quả đào tạo
Route::group(['prefix' => '/admin-cp/offline/result/{id}', 'middleware' => 'auth'], function() {

    Route::get('/class/{class_id}', 'ResultController@index')
        ->name('module.offline.result')
        ->where('id', '[0-9]+')->where('class_id', '[0-9]+')->middleware('permission:offline-course');

    Route::get('/class/{class_id}/get-result', 'ResultController@getData')
        ->name('module.offline.get_result')
        ->where('id', '[0-9]+')->where('class_id', '[0-9]+');

    Route::post('/save-score', 'ResultController@saveScore')
        ->name('module.offline.save_score')
        ->where('id', '[0-9]+');

    Route::post('/result-save-note', 'ResultController@saveNote')
        ->name('module.offline.result.save_note')
        ->where('id', '[0-9]+');

    Route::post('/class/{class_id}/import-result', 'ResultController@importResult')
        ->name('module.offline.result.import_result')
        ->where('id', '[0-9]+')->where('class_id', '[0-9]+');

    Route::get('/class/{class_id}/export-result', 'ResultController@exportResult')
        ->name('module.offline.result.export_result')
        ->where('id', '[0-9]+')->where('class_id', '[0-9]+');
});
/*********************************************************************************/
//Đánh giá 4 cấp độ
Route::group(['prefix' => '/admin-cp/offline/rating-level/{course_id}', 'middleware' => ['auth','permission:offline-course']], function() {
    Route::get('/get-data', 'RatingLevelController@getData')
        ->name('module.offline.rating_level.getData')
        ->where('course_id', '[0-9]+');

    Route::post('/save', 'RatingLevelController@save')
        ->name('module.offline.rating_level.save')
        ->where('course_id', '[0-9]+');

    Route::post('/remove', 'RatingLevelController@remove')
        ->name('module.offline.rating_level.remove')
        ->where('course_id', '[0-9]+');

    Route::post('/modal-add-object/{id}', 'RatingLevelController@modalAddObject')
        ->name('module.offline.rating_level.modal_add_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/modal-qrcode/{id}', 'RatingLevelController@modalQRCode')
        ->name('module.offline.rating_level.modal_qr_code')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/get-data-object/{id}', 'RatingLevelController@getDataObject')
        ->name('module.offline.rating_level.getDataObject')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/save-object/{id}', 'RatingLevelController@saveObject')
        ->name('module.offline.rating_level.save_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/ajax-get-object/{id}', 'RatingLevelController@ajaxGetObject')
        ->name('module.offline.rating_level.ajax_get_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/remove-object/{id}', 'RatingLevelController@removeObject')
        ->name('module.offline.rating_level.remove_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');


    Route::get('/list-report/getdata', 'RatingLevelController@getdataListReport')
        ->name('module.offline.rating_level.list_report.getdata')
        ->where('course_id', '[0-9]+');

    Route::get('/list-user-rating/{course_rating_level_id}/getdata', 'RatingLevelController@getdataListUserRating')
        ->name('module.offline.rating_level.list_user_rating.getdata')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/modal-rating-level', 'RatingLevelController@modalRatingLevel')
        ->name('module.offline.rating_level.modal_rating_level')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/modal-edit-rating-level', 'RatingLevelController@modalEditRatingLevel')
        ->name('module.offline.rating_level.modal_edit_rating_level')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/save-rating-level-course', 'RatingLevelController@saveRatingCourse')
        ->name('module.offline.rating_level.save_rating_course')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');
});

//Thiết lập điểm thưởng
Route::group(['prefix' => '/admin-cp/offline/userpoint-setting/{course_id}', 'middleware' => ['auth','permission:offline-course']], function() {

    Route::post('/add-userpoint-complete', 'UserPoint@saveSettingComplete')
        ->name('module.offline.add-userpoint-setting-complete')
        ->where('course_id', '[0-9]+');

    Route::post('/userpoint-complete', 'UserPoint@showModalComplete')
        ->name('module.offline.userpoint-setting-complete')
        ->where('course_id', '[0-9]+');

    Route::get('/save-setting-complete', 'UserPoint@saveSettingComplete')
        ->name('module.offline.userpoint-save')
        ->where('course_id', '[0-9]+');

    Route::post('/setting-quiz', 'UserPoint@showModalQuiz')
        ->name('module.offline.userpoint-setting-quiz')
        ->where('course_id', '[0-9]+');

    Route::post('/add-userpoint-quiz', 'UserPoint@saveSettingQuiz')
        ->name('module.offline.add-userpoint-setting-quiz')
        ->where('course_id', '[0-9]+');


    Route::post('/save-setting-others', 'UserPoint@saveSettingOthers')
        ->name('module.offline.userpoint-setting-others')
        ->where('course_id', '[0-9]+');

    Route::post('/edit-userpoint-quiz/{id}', 'UserPoint@showModalQuiz')
        ->name('module.offline.edit-userpoint-setting-quiz')
        ->where('course_id', '[0-9]+')->where('id', '[0-9]+');

    Route::post('/edit-userpoint-complete/{id}', 'UserPoint@showModalComplete')
        ->name('module.offline.edit-userpoint-setting-complete')
        ->where('course_id', '[0-9]+')->where('id', '[0-9]+');

    Route::post('/delete/{id}', 'UserPoint@deleteSetting')
        ->name('module.offline.userpoint-setting.delete')
        ->where('course_id', '[0-9]+')->where('id', '[0-9]+');

});

//Kết quả đánh giá công tác tổ chức giảng dạy
Route::group(['prefix' => '/admin-cp/offline/teaching-organization/{course_id}', 'middleware' => ['auth','permission:offline-course']], function() {
    Route::get('/', 'TeachingOrganizationController@index')
        ->name('module.offline.teaching_organization.index')
        ->where('course_id', '[0-9]+');

    Route::get('/get-data', 'TeachingOrganizationController@getData')
        ->name('module.offline.teaching_organization.getData')
        ->where('course_id', '[0-9]+');

     Route::get('/export', 'TeachingOrganizationController@export')
        ->name('module.offline.teaching_organization.export')
        ->where('course_id', '[0-9]+');

    Route::get('/view-rating-teacher/{user_id}', 'TeachingOrganizationController@viewRatingTeacher')
        ->name('module.offline.teaching_organization.view_rating')
        ->where('course_id', '[0-9]+')
        ->where('user_id', '[0-9]+');

    Route::post('/update-template-rating-teacher', 'TeachingOrganizationController@updateTemplateRatingTeacher')
        ->name('module.offline.teaching_organization.update_template_rating_teacher')
        ->where('course_id', '[0-9]+');

    Route::get('/view-rating-template/{template_id}', 'TeachingOrganizationController@viewRatingTemplate')
        ->name('module.offline.teaching_organization.view_rating_template')
        ->where('course_id', '[0-9]+')
        ->where('template_id', '[0-9]+');
});

//Kỳ thi khoá học
Route::group(['prefix' => '/admin-cp/offline/{course_id}/quiz', 'middleware' => ['auth','permission:offline-course']], function() {
    Route::get('/', 'QuizController@index')->name('module.offline.quiz')->where('course_id', '[0-9]+');

    Route::get('/get-quiz', 'QuizController@getData')->name('module.offline.get_quiz')->where('course_id', '[0-9]+');

    Route::get('/create', 'QuizController@form')->name('module.offline.quiz.create')->where('course_id', '[0-9]+');

    Route::get('/edit/{id}', 'QuizController@form')->name('module.offline.quiz.edit')->where('course_id', '[0-9]+')->where('id', '[0-9]+');

    Route::post('/save', 'QuizController@save')->name('module.offline.quiz.save')->where('course_id', '[0-9]+');

    Route::post('/remove', 'QuizController@remove')->name('module.offline.quiz.remove')->where('course_id', '[0-9]+');

    Route::post('/edit/{id}/save-part', 'QuizController@savePart')->name('module.offline.quiz.edit.savepart')->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-part', 'QuizController@getDataPart')->name('module.offline.quiz.edit.getpart')->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/add-question', 'QuizController@questionQuiz')
        ->name('module.offline.quiz.question')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/report/{id}', 'QuizController@report')->name('module.offline.quiz.report')->where('course_id', '[0-9]+')->where('id', '[0-9]+');
    Route::get('/getreport/{id}', 'QuizController@getReport')->name('module.offline.quiz.getreport')->where('course_id', '[0-9]+')->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'QuizController@removeAttempt')->name('module.offline.quiz.attempt.remove')->where('course_id', '[0-9]+')->where('id', '[0-9]+');
});
