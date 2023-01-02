<?php

Route::group(['prefix' => '/admin-cp/master-data', 'middleware' => 'superadmin'], function() {
    Route::get('/', 'PermissionMasterDataController@index')->name('backend.master_data.index');
    Route::post('/remove', 'PermissionMasterDataController@destroy')->name('backend.master_data.remove');
    Route::post('/edit', 'PermissionMasterDataController@form')->name('backend.master_data.edit');
    Route::post('/save', 'PermissionMasterDataController@save')->name('backend.master_data.save');
});
