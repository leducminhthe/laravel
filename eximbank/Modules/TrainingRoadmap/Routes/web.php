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
Route::group(['prefix' => '/admin-cp/trainingroadmap', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.trainingroadmap')
    ->middleware('permission:training-roadmap');

    Route::get('/list', 'BackendController@listRoadmap')->name('module.trainingroadmap.list')
    ->middleware('permission:training-roadmap');

    Route::get('/getdata', 'BackendController@getData')->name('module.trainingroadmap.getdata')
    ->middleware('permission:training-roadmap');

    Route::post('/import', 'TrainingRoadmapController@import')->name('module.trainingroadmap.detail.import')
    ->middleware('permission:training-roadmap-import')
    ;
    Route::get('/export-roadmap', 'TrainingRoadmapController@exportRoadmap')->name('module.trainingroadmap.export_roadmap')
    ->middleware('permission:training-roadmap-export');

    Route::post('/ajax-copy', 'TrainingRoadmapController@copy')->name('module.trainingroadmap.ajax_copy')
    ->middleware('permission:training-roadmap');

    Route::post('/saveOrder', 'TrainingRoadmapController@saveOrder')->name('module.trainingroadmap.saveOrder')
    ->middleware('permission:training-roadmap');

    Route::post('/ajax-check-training-roadmap', 'TrainingRoadmapController@checkTrainingRoadmap')->name('module.trainingroadmap.ajax_check_training_roadmap')
    ->middleware('permission:training-roadmap');
});

Route::group(['prefix' => '/admin-cp/trainingroadmap/train-detail/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'TrainingRoadmapController@index')->name('module.trainingroadmap.detail')
    ->where('id', '[0-9]+')
    ->middleware('permission:training-roadmap-detail');

    Route::get('/getdata', 'TrainingRoadmapController@getData')->name('module.trainingroadmap.detail.getdata')
    ->middleware('permission:training-roadmap-detail');

    Route::get('/edit/{train_id}', 'TrainingRoadmapController@form')
        ->name('module.trainingroadmap.detail.edit')
        ->where('id', '[0-9]+')
        ->where('train_id', '[0-9]+')
        ->middleware('permission:training-roadmap-detail-edit');

    Route::get('/create', 'TrainingRoadmapController@form')->name('module.trainingroadmap.detail.create')
    ->middleware('permission:training-roadmap-detail-create');

    Route::post('/save', 'TrainingRoadmapController@save')->name('module.trainingroadmap.detail.save')
    ->middleware('permission:training-roadmap-detail-create');

    Route::post('/remove', 'TrainingRoadmapController@remove')->name('module.trainingroadmap.detail.remove')
    ->middleware('permission:training-roadmap-detail-delete');

    Route::get('/export', 'TrainingRoadmapController@export')->name('module.trainingroadmap.detail.export')
    ->middleware('permission:training-roadmap-detail-export');

    Route::post('/data-training-program', 'TrainingRoadmapController@dataTrainingProgram')->name('module.trainingroadmap.detail.data_training_program')
    ->middleware('permission:training-roadmap-detail');
});
