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

Route::group(['prefix' => '/admin-cp/emulation-badge', 'middleware' => 'auth'], function() {
    Route::get('/', 'EmulationBadgeController@index')->name('module.emulation_badge.list');

    Route::get('/getdata', 'EmulationBadgeController@getData')->name('module.emulation_badge.getdata');

    Route::get('/create', 'EmulationBadgeController@form')->name('module.emulation_badge.create');

    Route::get('/edit/{id}', 'EmulationBadgeController@form')->name('module.emulation_badge.edit')
    ->where('id', '[0-9]+');

    Route::get('/result/{id}', 'EmulationBadgeController@result')->name('module.emulation_badge.result')
    ->where('id', '[0-9]+');

    Route::get('/getdataResult/{id}', 'EmulationBadgeController@getDataResult')->name('module.emulation_badge.getdata_result');

    Route::post('/save', 'EmulationBadgeController@save')->name('module.emulation_badge.save');

    Route::post('/remove', 'EmulationBadgeController@remove')->name('module.emulation_badge.remove');

    Route::post('/edit-armorial', 'EmulationBadgeController@editPromotionChild')->name('module.emulation_badge.edit_armorial');

    Route::post('/save-armorial', 'EmulationBadgeController@saveArmorial')->name('module.emulation_badge.save_armorial');

    Route::post('/remove-armorial', 'EmulationBadgeController@removeArmorial')->name('module.emulation_badge.remove_armorial');

    Route::post('/save-course', 'EmulationBadgeController@saveCourse')->name('module.emulation_badge.save_course');

    Route::post('/remove-course', 'EmulationBadgeController@removeCourse')->name('module.emulation_badge.remove_course');

});