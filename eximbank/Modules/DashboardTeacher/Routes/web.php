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

Route::group(['prefix' => '/teacher-cp/dashboard-teacher', 'middleware' => 'auth'], function() {
    Route::get('/', 'DashboardTeacherController@index')->name('module.dashboard_teacher');
    Route::get('/detail', 'DashboardTeacherController@detail')->name('module.dashboard_teacher_detail');
    Route::get('/get-data', 'DashboardTeacherController@getData')->name('module.dashboard_teacher_get_data');
});
