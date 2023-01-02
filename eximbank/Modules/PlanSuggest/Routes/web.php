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

Route::group(['prefix' => '/admin-cp/plan-suggest', 'middleware' => 'auth'], function() {
    Route::get('/', 'PlanSuggestController@index')->name('module.plan_suggest')
    ->middleware('permission:plan-suggest');

    Route::get('/getData', 'PlanSuggestController@getDataPlanSuggest')->name('module.plan_suggest.getData')
    ->middleware('permission:plan-suggest');

    Route::get('/create', 'PlanSuggestController@Form')->name('module.plan_suggest.create')
    ->middleware('permission:plan-suggest-create');

    Route::post('/save', 'PlanSuggestController@save')->name('module.plan_suggest.save')
    ->middleware('permission:plan-suggest-create');

    Route::post('/remove', 'PlanSuggestController@remove')->name('module.plan_suggest.remove')
    ->middleware('permission:plan-suggest-delete');

    Route::post('/approved', 'PlanSuggestController@approved')->name('module.plan_suggest.approved')
    ->middleware('permission:plan-suggest-approve');

    Route::post('/deny', 'PlanSuggestController@deny')->name('module.plan_suggest.deny')
    ->middleware('permission:plan-suggest');

    Route::get('/edit/{id}', 'PlanSuggestController@Form')->name('module.plan_suggest.form.edit')
    ->where(['id'=>'[0-9]+'])
    ->middleware('permission:plan-suggest');

    Route::get('/download/{file}', 'PlanSuggestController@download')->name('module.plan_suggest.download')
    ->where(['file'=>'[a-z0-9.]+'])
    ->middleware('permission:plan-suggest');

    Route::get('/export', 'PlanSuggestController@export')->name('module.plan_suggest.export')
    ->middleware('permission:plan-suggest');

    Route::post('/ajax-user-by-title', 'PlanSuggestController@loadUserByTitle')->name('module.plan_suggest.ajax_user_by_title')
    ->middleware('permission:plan-suggest');
});