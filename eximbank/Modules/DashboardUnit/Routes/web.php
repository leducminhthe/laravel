<?php

Route::group(['prefix' => '/leader-cp/dashboard-unit', 'middleware' => 'auth'], function() {
    Route::get('/', 'DashboardUnitController@index')->name('module.dashboard_unit'); //->middleware('permission:dashboard-unit');

    Route::get('/export-dashboard-training_form/{type}', 'DashboardUnitController@exportDashboardTrainingForm')
        ->name('module.dashboard_unit.export_dashboard_training_form');

    Route::get('/export-dashboard-user-training-form', 'DashboardUnitController@exportDashboardUserTrainingForm')
        ->name('module.dashboard_unit.export_dashboard_user_training_form');

    Route::get('/export-dashboard-course-employee/{type}', 'DashboardUnitController@exportDashboardCourseEmployee')
        ->name('module.dashboard_unit.export_dashboard_course_employee');

    Route::get('/export-dashboard-user-course-employee', 'DashboardUnitController@exportDashboardUserCourseEmployee')
        ->name('module.dashboard_unit.export_dashboard_user_course_employee');

    Route::get('/export-dashboard-quiz/{type}', 'DashboardUnitController@exportDashboardQuiz')
        ->name('module.dashboard_unit.export_dashboard_quiz');

    Route::get('/export-dashboard-user-quiz', 'DashboardUnitController@exportDashboardUserQuiz')
        ->name('module.dashboard_unit.export_dashboard_user_quiz');

    Route::post('/search-course', 'DashboardUnitController@searchCourse')
    ->name('module.dashboard_unit.search_course');

    Route::get('/data-user-online', 'DashboardUnitController@dataUserOnline')->name('module.dashboard_unit.data_user_online');
    Route::get('/data-user-offline', 'DashboardUnitController@dataUserOffline')->name('module.dashboard_unit.data_user_offline');
    Route::get('/data-user-quiz', 'DashboardUnitController@dataUserQuiz')->name('module.dashboard_unit.data_user_quiz');
});
