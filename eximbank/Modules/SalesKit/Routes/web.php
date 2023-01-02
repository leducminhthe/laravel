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

Route::group(['prefix' => '/saleskit', 'middleware' => 'auth'], function() {

    Route::get('/view-pdf/{id}', 'FrontendController@viewPDF')->name('module.saleskit.view_pdf')->where('id', '[0-9]+');

    Route::get('/salekit', 'FrontendController@salekit')->name('module.frontend.saleskit.salekit');
    Route::get('/salekit-child/{cate_id}', 'FrontendController@salekitChild')->name('module.frontend.saleskit.salekit_child')->where('cate_id', '[0-9]+');
    Route::get('/salekit/detail/{cate_id}', 'FrontendController@salekitDetail')->name('module.frontend.saleskit.salekit.detail')->where('cate_id', '[0-9]+');
});
Route::prefix(url_mobile()? 'AppM':'')->group(function() {
    Route::group(['prefix' => '/saleskit-mobile', 'middleware' => 'auth'], function () {
        
        Route::get('/view-pdf/{id}', 'MobileController@viewPDF')->name('themes.mobile.saleskit.view_pdf')->where('id', '[0-9]+');

        Route::get('/salekit', 'MobileController@salekit')->name('themes.mobile.saleskit.salekit');
        Route::get('/salekit/child/{cate_id}', 'MobileController@salekitChild')->name('themes.mobile.saleskit.salekit.child')->where('cate_id', '[0-9]+');
        Route::get('/salekit/detail/{cate_id}', 'MobileController@salekitDetail')->name('themes.mobile.saleskit.salekit.detail')->where('cate_id', '[0-9]+');
    });
});

Route::group(['prefix' => '/admin-cp/saleskit/category', 'middleware' => ['auth','permission:saleskit-category']], function() {
    Route::get('/', 'Backend\CategoryController@index')->name('module.saleskit.category')->middleware('permission:saleskit-category');

    Route::get('/getdata', 'Backend\CategoryController@getData')->name('module.saleskit.category.getdata')->middleware('permission:saleskit-category');

    Route::post('/edit', 'Backend\CategoryController@form')->name('module.saleskit.category.edit')->where('id', '[0-9]+')->middleware('permission:saleskit-category-edit');

    Route::get('/create', 'Backend\CategoryController@form')->name('module.saleskit.category.create')->middleware('permission:saleskit-category-create');

    Route::post('/save', 'Backend\CategoryController@save')->name('module.saleskit.category.save')->middleware('permission:saleskit-category-create|saleskit-category-edit');

    Route::post('/remove', 'Backend\CategoryController@remove')->name('module.saleskit.category.remove')->middleware('permission:saleskit-category-delete');
});

Route::group(['prefix' => '/admin-cp/saleskit/{cate_id}', 'middleware' => ['auth','permission:saleskit']], function() {
    Route::get('/', 'Backend\SalesKitController@index')->name('module.saleskit')->where('cate_id', '[0-9]+')->middleware('permission:saleskit');

    Route::get('/getdata', 'Backend\SalesKitController@getData')->name('module.saleskit.getdata')->where('cate_id', '[0-9]+')->middleware('permission:saleskit');

    Route::get('/edit/{id}', 'Backend\SalesKitController@form')->name('module.saleskit.edit')->where('cate_id', '[0-9]+')->where('id', '[0-9]+')->middleware('permission:saleskit-edit');

    Route::get('/create', 'Backend\SalesKitController@form')->name('module.saleskit.create')->where('cate_id', '[0-9]+')->middleware('permission:saleskit-create');

    Route::post('/save', 'Backend\SalesKitController@save')->name('module.saleskit.save')->where('cate_id', '[0-9]+')->middleware('permission:saleskit-create|saleskit-edit');

    Route::post('/remove', 'Backend\SalesKitController@remove')->name('module.saleskit.remove')->where('cate_id', '[0-9]+')->middleware('permission:saleskit-delete');

    Route::post('/edit/{id}/save-object', 'Backend\SalesKitController@saveObject')->name('module.saleskit.save_object')->where('cate_id', '[0-9]+')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-object', 'Backend\SalesKitController@getObject')->name('module.saleskit.get_object')->where('cate_id', '[0-9]+')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-user-object', 'Backend\SalesKitController@getUserObject')->name('module.saleskit.get_user_object')->where('cate_id', '[0-9]+')->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'Backend\SalesKitController@removeObject')->name('module.saleskit.remove_object')->where('cate_id', '[0-9]+')->where('id', '[0-9]+');

    Route::post('/edit/{id}/import-object', 'Backend\SalesKitController@importObject')->name('module.saleskit.import_object')->where('cate_id', '[0-9]+')->where('id', '[0-9]+');

    Route::get('/export', 'Backend\SalesKitController@export')->name('module.saleskit.export')->where('cate_id', '[0-9]+');

    Route::post('/ajax-isopen-publish', 'Backend\SalesKitController@ajaxIsopenPublish')->name('module.saleskit.ajax_isopen_publish')->where('cate_id', '[0-9]+');
});
