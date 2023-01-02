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

Route::group(['prefix' => 'promotion', 'middleware' => 'auth'], function () {
    Route::get('/', 'frontend\PromotionController@index')->name('module.front.promotion');
    Route::get('/detail', 'frontend\PromotionController@index')->name('module.front.promotion.detail');
    Route::post('/get/{id}', 'frontend\PromotionController@get')->name('module.front.promotion.get');
});

Route::group(['prefix' => '/admin-cp/promotion', 'middleware' => 'auth'], function () {
    Route::get('/', 'PromotionController@index')->name('module.promotion')
    ->middleware('permission:promotion');

    Route::get('/getdata', 'PromotionController@getData')->name('module.promotion.getdata')
    ->middleware('permission:promotion');

    Route::get('/create', 'PromotionController@create')->name('module.promotion.create')
    ->middleware('permission:promotion-create');

    Route::post('/save', 'PromotionController@store')->name('module.promotion.save')
    ->middleware('permission:promotion-create');

    Route::get('/edit/{id}', 'PromotionController@edit')->name('module.promotion.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:promotion-edit');

    Route::post('/update/{id}', 'PromotionController@update')->name('module.promotion.update')
    ->middleware('permission:promotion-edit');

    Route::post('/remove', 'PromotionController@remove')->name('module.promotion.remove')
    ->middleware('permission:promotion-delete');

    Route::post('/save_setting', 'PromotionController@saveSetting')->name('module.promotion.save_setting')
    ->middleware('permission:promotion');

    Route::post('/delete_setting', 'PromotionController@deleteSettingMethod')->name('module.promotion.delete_setting')
    ->middleware('permission:promotion');

    Route::get('/get_setting/{courseId}/{course_type}/{code}','PromotionController@getPromotionSetting')->name('module.promotion.get_setting')
    ->middleware('permission:promotion');

    Route::post('/ajax-is-open', 'PromotionController@ajaxIsopenPublish')->name('module.promotion.ajax_is_open')
    ->middleware('permission:promotion');

    Route::post('/ajax-info', 'PromotionController@ajaxInfo')->name('module.promotion.ajax_info')
    ->middleware('permission:promotion');
});

Route::group(['prefix' => '/admin-cp/promotion-group', 'middleware' => 'auth'], function () {
    Route::get('/', 'PromotionGroupController@index')->name('module.promotion.group')
    ->middleware('permission:promotion-group');

    Route::get('/getdata', 'PromotionGroupController@getData')->name('module.promotion.group.getdata')
    ->middleware('permission:promotion-group');

    Route::post('/save', 'PromotionGroupController@save')->name('module.promotion.group.save')
    ->middleware('permission:promotion-group-create');

    Route::post('/edit', 'PromotionGroupController@form')->name('module.promotion.group.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:promotion-group-edit');

    Route::post('/remove', 'PromotionGroupController@remove')->name('module.promotion.group.remove')
    ->middleware('permission:promotion-group-delete');

    Route::post('/ajax-is-open', 'PromotionGroupController@ajaxIsopenPublish')->name('module.promotion.group.ajax_is_open')
    ->middleware('permission:promotion-group');
});

Route::group(['prefix' => '/admin-cp/promotion-level', 'middleware' => 'auth'], function () {
    Route::get('/', 'PromotionLevelController@index')->name('module.promotion.level')
    ->middleware('permission:promotion-level');

    Route::get('/getdata', 'PromotionLevelController@getData')->name('module.promotion.level.getdata')
    ->middleware('permission:promotion-level');

    Route::post('/save', 'PromotionLevelController@save')->name('module.promotion.level.save')
    ->middleware('permission:promotion-level-create');

    Route::post('/edit', 'PromotionLevelController@form')->name('module.promotion.level.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:promotion-level-edit');

    Route::post('/remove', 'PromotionLevelController@remove')->name('module.promotion.level.remove')
    ->middleware('permission:promotion-level-delete');

    Route::post('/ajax-is-open', 'PromotionLevelController@ajaxIsopenPublish')->name('module.promotion.level.ajax_is_open')
    ->middleware('permission:promotion-level');
});

Route::group(['prefix' => '/admin-cp/promotion-orders', 'middleware' => 'auth'], function () {
    Route::get('/', 'PromotionOrdersController@index')->name('module.promotion.orders.buy')
    ->middleware('permission:promotion-orders');

    Route::get('/getdata', 'PromotionOrdersController@getData')->name('module.promotion.orders.buy.getdata')
    ->middleware('permission:promotion-orders');

    Route::get('/detail/{id}', 'PromotionOrdersController@getDetail')->name('module.promotion.orders.buy.detail')
    ->middleware('permission:promotion-orders');

    Route::post('/remove', 'PromotionOrdersController@remove')->name('module.promotion.orders.buy.remove')
    ->middleware('permission:promotion-orders-delete');

    Route::post('/update_status/{id}', 'PromotionOrdersController@updateStatus')->name('module.promotion.orders.buy.update_status')
    ->middleware('permission:promotion-orders-edit');
});

Route::group(['prefix' => '/admin-cp/promotion-history', 'middleware' => 'auth'], function () {
    Route::get('/', 'PromotionHistoryController@index')->name('module.promotion.history')
    ->middleware('permission:promotion-history');

    Route::get('/getdata', 'PromotionHistoryController@getData')->name('module.promotion.history.getdata')
    ->middleware('permission:promotion-history');

    Route::get('/detail/{userId}', 'PromotionHistoryController@getDetail')->name('module.promotion.history.detail')
    ->middleware('permission:promotion-history');

    Route::get('/get-data-detail/{userId}', 'PromotionHistoryController@getDataDetail')->name('module.promotion.history.data_detail')
    ->middleware('permission:promotion-history');

    Route::get('/export', 'PromotionHistoryController@export')->name('module.promotion.history.export')
    ->middleware('permission:promotion-history');
});
