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

Route::group(['prefix' => '/leader-cp/register-training-plan', 'middleware' => 'auth'], function() {
    Route::get('/', 'RegisterTrainingPlanController@index')->name('module.register_training_plan.management');

    Route::get('/getdata', 'RegisterTrainingPlanController@getData')->name('module.register_training_plan.getdata');

    Route::get('/edit/{id}', 'RegisterTrainingPlanController@form')->name('module.register_training_plan.edit');

    Route::get('/create', 'RegisterTrainingPlanController@form')->name('module.register_training_plan.create');

    Route::post('/save', 'RegisterTrainingPlanController@save')->name('module.register_training_plan.save');

    Route::post('/remove', 'RegisterTrainingPlanController@remove')->name('module.register_training_plan.remove');

    Route::post('/import', 'RegisterTrainingPlanController@import')->name('module.register_training_plan.import');

    Route::get('/export-template', 'RegisterTrainingPlanController@exportTemplate')->name('module.register_training_plan.export_template');

    Route::post('/send', 'RegisterTrainingPlanController@send')->name('module.register_training_plan.send');
});
