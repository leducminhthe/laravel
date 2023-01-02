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

Route::group(['prefix' => '/admin-cp/dashboard', 'middleware' => 'auth'], function() {
    Route::get('/', 'DashboardController@index')->name('module.dashboard')->middleware('permission:dashboard');
    Route::post('/search-course-complete', 'DashboardController@searchCourseComplete')->name('module.dashboard.search_course_complete')->middleware('permission:dashboard');
});
