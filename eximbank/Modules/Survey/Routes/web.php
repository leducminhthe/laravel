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
Route::group(['prefix' => '/survey', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontendController@index')->name('module.survey');
    Route::get('/getdata', 'FrontendController@getData')->name('module.survey.get_data');
    Route::get('/user/{id}', 'FrontendController@getSurveyUser')->name('module.survey.user')->where('id', '[0-9]+');
    Route::get('/edit/{id}', 'FrontendController@editSurveyUser')->name('module.survey.user.edit')->where('id', '[0-9]+');
    Route::post('/save', 'FrontendController@saveSurveyUser')->name('module.survey.user.save');
});

Route::group(['prefix' => '/admin-cp/survey/template/', 'middleware' => 'auth'], function() {
    Route::get('/', 'TemplateController@index')->name('module.survey.template')
    ->middleware('permission:survey-template');
    
    Route::get('/getdata', 'TemplateController@getData')->name('module.survey.template.getdata')
    ->middleware('permission:survey-template');

    Route::get('/edit/{survey_id}/{id}', 'TemplateController@form')->name('module.survey.template.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey-template-edit');

    Route::get('/create/{survey_id}', 'TemplateController@form')->name('module.survey.template.create')
    ->middleware('permission:survey-template-create');

    Route::post('/save', 'TemplateController@save')->name('module.survey.template.save')
    ->middleware('permission:survey-template-create');

    Route::post('/remove', 'TemplateController@remove')->name('module.survey.template.remove')
    ->middleware('permission:survey-template-delete');

    Route::post('/remove-category', 'TemplateController@removeCategory')->name('module.survey.template.remove_category')
    ->middleware('permission:survey-template-delete');

    Route::post('/remove-question', 'TemplateController@removeQuestion')->name('module.survey.template.remove_question')
    ->middleware('permission:survey-template-delete');

    Route::post('/remove-answer', 'TemplateController@removeAnswer')->name('module.survey.template.remove_answer')
    ->middleware('permission:survey-template-delete');

    Route::get('/review-template/{id}', 'TemplateController@reviewTemplate')->name('module.survey.template.review')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::post('/modal-view-question', 'TemplateController@modalViewQuestion')
    ->name('module.survey.template.modal_view_question')
    ->middleware('permission:survey');

    Route::post('/copy-template', 'TemplateController@copyTemplate')
    ->name('module.survey.template.copy_template')
    ->middleware('permission:survey');

    Route::post('/update-num-order/{id}', 'TemplateController@updateNumOrder')
    ->name('module.survey.template.update_num_order')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');
});

// MÃU KHẢO SÁT TRỰC TUYẾN
Route::group(['prefix' => '/admin-cp/survey/template-online', 'middleware' => 'auth'], function() {
    Route::get('/', 'TemplateOnlineController@index')->name('module.survey.template_online')
    ->middleware('permission:survey-template');

    Route::get('/getdata', 'TemplateOnlineController@getData')->name('module.survey.template_online.getdata')
    ->middleware('permission:survey-template');

    Route::get('/edit/{id}', 'TemplateOnlineController@form')->name('module.survey.template_online.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey-template-edit');

    Route::get('/create', 'TemplateOnlineController@form')->name('module.survey.template_online.create')
    ->middleware('permission:survey-template-create');

    Route::post('/save', 'TemplateOnlineController@save')->name('module.survey.template_online.save')
    ->middleware('permission:survey-template-create');

    Route::post('/remove', 'TemplateOnlineController@remove')->name('module.survey.template_online.remove')
    ->middleware('permission:survey-template-delete');

    Route::post('/remove-question', 'TemplateOnlineController@removeQuestion')->name('module.survey.template_online.remove_question')
    ->middleware('permission:survey-template-delete');

    Route::post('/remove-answer', 'TemplateOnlineController@removeAnswer')->name('module.survey.template_online.remove_answer')
    ->middleware('permission:survey-template-delete');

    Route::get('/review-template/{survey_id}/{id}', 'TemplateOnlineController@reviewTemplate')->name('module.survey.template_online.review')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::post('/copy-template-online', 'TemplateOnlineController@copyTemplateOnline')
    ->name('module.survey.template.copy_template_online')
    ->middleware('permission:survey');

    // CHI TIẾT HV TRẢ LỜI CÂU HỎI TRỰC TUYẾN
    Route::post('/detail-user-answer', 'TemplateOnlineController@detailUserAnswer')->name('module.survey.template_online.detail_user_anser')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/survey', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.survey.index')
    ->middleware('permission:survey');

    Route::get('/getdata', 'BackendController@getData')->name('module.survey.getdata')
    ->middleware('permission:survey');

    Route::get('/create', 'BackendController@form')->name('module.survey.create')
    ->middleware('permission:survey-create');

    Route::get('/edit/{id}', 'BackendController@form')->name('module.survey.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey-edit');

    Route::post('/save', 'BackendController@save')->name('module.survey.save')
    ->middleware('permission:survey-create');

    Route::post('/remove', 'BackendController@remove')->name('module.survey.remove')
    ->middleware('permission:survey-delete');

    Route::post('/ajax-isopen-publish', 'BackendController@ajaxIsopenPublish')->name('module.survey.ajax_isopen_publish')
    ->middleware('permission:survey');

    Route::post('/edit/{id}/save-object', 'BackendController@saveObject')->name('module.survey.save_object')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::get('/edit/{id}/get-object', 'BackendController@getObject')->name('module.survey.get_object')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::get('/edit/{id}/get-user-object', 'BackendController@getUserObject')->name('module.survey.get_user_object')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::post('/edit/{id}/remove-object', 'BackendController@removeObject')->name('module.survey.remove_object')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::post('/edit/{id}/import-object', 'BackendController@importObject')->name('module.survey.import_object')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::post('/edit/{id}/save-popup', 'BackendController@savePopup')->name('module.survey.save_popup')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::get('/edit/{id}/get-popup', 'BackendController@getPopup')->name('module.survey.get_popup')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::post('/edit/{id}/remove-popup', 'BackendController@removePopup')->name('module.survey.remove_popup')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::post('/edit/{id}/check-unit-child', 'BackendController@getChild')->name('module.survey.get_child')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::get('/edit/{id}/get-tree-child', 'BackendController@getTreeChild')
        ->name('module.survey.get_tree_child')
        ->where('id', '[0-9]+')
        ->middleware('permission:survey');

    Route::get('/review-template/{id}', 'BackendController@reviewTemplate')->name('module.survey.review_template')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');

    Route::post('/copy', 'BackendController@copy')->name('module.survey.copy')
    ->where('id', '[0-9]+')
    ->middleware('permission:survey');
});

Route::group(['prefix' => '/admin-cp/survey/report/{survey_id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'ReportController@index')->name('module.survey.report.index')
    ->where('survey_id', '[0-9]+')
    ->middleware('permission:survey-view-report');

    Route::get('/getdata', 'ReportController@getData')->name('module.survey.report.getdata')
    ->where('survey_id', '[0-9]+')
    ->middleware('permission:survey-view-report');

    Route::get('/edit/{user_id}', 'ReportController@form')->name('module.survey.report.edit')
    ->where('survey_id', '[0-9]+')
    ->where('user_id', '[0-9]+')
    ->middleware('permission:survey-view-report');

    Route::get('/export', 'ReportController@export')->name('module.survey.report.export')
    ->where('survey_id', '[0-9]+')
    ->middleware('permission:survey-export-report');
});
