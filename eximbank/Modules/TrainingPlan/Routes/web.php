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

/* backend */
Route::group(['prefix' => '/admin-cp/training-plan', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.training_plan')->middleware('permission:training-plan')
    ->middleware('permission:training-plan');

    Route::get('/getdata', 'BackendController@getData')->name('module.training_plan.getdata')
    ->middleware('permission:training-plan');

    Route::get('/edit/{id}', 'BackendController@form')->name('module.training_plan.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:training-plan-edit');

    Route::get('/create', 'BackendController@form')->name('module.training_plan.create')
    ->middleware('permission:training-plan-create');

    Route::post('/save', 'BackendController@save')->name('module.training_plan.save')
    ->middleware('permission:training-plan-create');

    Route::post('/remove', 'BackendController@remove')->name('module.training_plan.remove')
    ->middleware('permission:training-plan-delete');

    Route::post('/ajax-isopen-publish', 'BackendController@ajaxIsopenPublish')->name('module.training_plan.ajax_isopen_publish')
    ->middleware('permission:training-plan');

    /* training plan detail */
    Route::get('/plan-detail/{id}', 'PlanDetailController@index')->name('module.training_plan.detail')
    ->where('id', '[0-9]+')
    ->middleware('permission:training-plan-detail');

    Route::get('/plan-detail/{id}/getdata', 'PlanDetailController@getData')->name('module.training_plan.detail.getdata')
    ->middleware('permission:training-plan-detail');

    Route::get('/plan-detail/{id}/edit/{plan_detail_id}', 'PlanDetailController@form')->name('module.training_plan.detail.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:training-plan-detail-edit');

    Route::get('/plan-detail/{id}/create', 'PlanDetailController@form')->name('module.training_plan.detail.create')
    ->middleware('permission:training-plan-detail-create');

    Route::post('/plan-detail/{id}/save', 'PlanDetailController@save')->name('module.training_plan.detail.save')
    ->middleware('permission:training-plan-detail-create');

    Route::post('/plan-detail/{id}/remove', 'PlanDetailController@remove')->name('module.training_plan.detail.remove')
    ->middleware('permission:training-plan-detail-delete');

    Route::post('/plan-detail/{id}/import-plan', 'PlanDetailController@importPlanDetail')->name('module.training_plan.detail.import_plan')
    ->middleware('permission:training-plan-detail');

    Route::get('/plan-detail/{id}/export-plan', 'PlanDetailController@exportPlanDetail')->name('module.training_plan.detail.export_plan')
    ->middleware('permission:training-plan-detail');

    Route::get('/plan-detail/{id}/export-template', 'PlanDetailController@exportTemplate')->name('module.training_plan.detail.export_template')
    ->middleware('permission:training-plan-detail');

    Route::post('/ajax-level-subject', 'PlanDetailController@ajaxLevelSubject')->name('module.training_plan.detail.ajax_level_subject')
    ->middleware('permission:training-plan-detail');

    Route::post('/ajax-cost-calculate/{id}', 'PlanDetailController@ajaxCostCalculate')->name('module.training_plan.detail.ajax_cost_calculate')
    ->middleware('permission:training-plan-detail');

    Route::post('/ajax-detail-cost/{id}', 'PlanDetailController@ajaxDetailCost')->name('module.training_plan.detail.ajax_detail_cost')
    ->middleware('permission:training-plan-detail');

    Route::post('/ajax-type-cost-calculate/{id}', 'PlanDetailController@ajaxTypeCostCalculate')->name('module.training_plan.detail.ajax_type_cost_calculate')
    ->middleware('permission:training-plan-detail');
});

