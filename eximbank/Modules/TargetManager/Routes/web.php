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

Route::group(['prefix'=>'/admin-cp/target-manager-parent', 'middleware' => 'auth'], function() {
    Route::get('/', 'TargetManagerParentController@index')->name('module.target_manager_parent')
    ->middleware('permission:target-manager-parent');

    Route::get('/getdata', 'TargetManagerParentController@getData')->name('module.target_manager_parent.getdata')
    ->middleware('permission:target-manager-parent');

    Route::post('/edit', 'TargetManagerParentController@form')->name('module.target_manager_parent.edit')
    ->middleware('permission:target-manager-parent-edit');

    Route::post('/save', 'TargetManagerParentController@save')->name('module.target_manager_parent.save')
    ->middleware('permission:target-manager-parent-create');

    Route::post('/remove', 'TargetManagerParentController@remove')->name('module.target_manager_parent.remove')
    ->middleware('permission:target-manager-parent-delete');
});

Route::group(['prefix'=>'/admin-cp/target-manager-parent/{parent_id}/target-manager', 'middleware' => 'auth'], function() {
    Route::get('/', 'TargetManagerController@index')->name('module.target_manager')
    ->where('parent_id','[0-9]+')
    ->middleware('permission:target-manager');

    Route::get('/getdata', 'TargetManagerController@getData')->name('module.target_manager.getdata')
    ->where('parent_id','[0-9]+')
    ->middleware('permission:target-manager');

    Route::post('/edit', 'TargetManagerController@form')->name('module.target_manager.edit')
    ->where('parent_id','[0-9]+')
    ->middleware('permission:target-manager-edit');

    Route::post('/save', 'TargetManagerController@save')->name('module.target_manager.save')
    ->where('parent_id','[0-9]+')
    ->middleware('permission:target-manager-create');

    Route::post('/remove', 'TargetManagerController@remove')->name('module.target_manager.remove')
    ->where('parent_id','[0-9]+')
    ->middleware('permission:target-manager-delete');

    Route::post('/copy', 'TargetManagerController@copy')->name('module.target_manager.copy')
    ->where('parent_id','[0-9]+')
    ->middleware('permission:target-manager-copy');

    Route::post('/import', 'TargetManagerController@import')->name('module.target_manager.import')
    ->where('parent_id','[0-9]+')
    ->middleware('permission:target-manager-create');
});
