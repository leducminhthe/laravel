<?php
Route::prefix(url_mobile()? 'AppM':'')->group(function() {
    Route::group(['prefix' => '/training-by-title'], function() {
        Route::get('/', 'Frontend\TrainingByTitleController@index')->name('module.frontend.training_by_title');
    });
});

Route::group(['prefix' => '/admin-cp/training-by-title'], function() {
    Route::get('/', 'Backend\TrainingByTitleController@index')->name('module.training_by_title')
    ->middleware('permission:training-by-title');

    Route::post('/save', 'Backend\TrainingByTitleController@save')->name('module.training_by_title.save')
    ->middleware('permission:training-by-title-create');

    Route::get('/getdata', 'Backend\TrainingByTitleController@getData')->name('module.training_by_title.getdata')
    ->middleware('permission:training-by-title');

    Route::post('/remove', 'Backend\TrainingByTitleController@remove')->name('module.training_by_title.remove')
    ->middleware('permission:training-by-title-delete');

    Route::post('/ajax-copy', 'Backend\TrainingByTitleController@copy')->name('module.training_by_title.ajax_copy')
    ->middleware('permission:training-by-title');

    Route::post('/ajax-check-training-roadmap', 'Backend\TrainingByTitleController@checkTrainingByTitle')->name('module.training_by_title.ajax_check_training_by_title')
    ->middleware('permission:training-by-title');

    Route::post('/import', 'Backend\TrainingByTitleController@import')->name('module.training_by_title.import')
    ->middleware('permission:training-by-title');

    Route::get('/export', 'Backend\TrainingByTitleController@export')->name('module.training_by_title.export')
    ->middleware('permission:training-by-title');

    Route::get('/upload-image', 'Backend\TrainingByTitleController@uploadImage')->name('module.training_by_title.upload_image')
    ->middleware('permission:training-by-title');

    Route::get('/get-data-upload-image', 'Backend\TrainingByTitleController@getDataUploadImage')->name('module.training_by_title.getdata_upload_image')
    ->middleware('permission:training-by-title');

    Route::post('/edit-upload-image', 'Backend\TrainingByTitleController@editUploadImage')->name('module.training_by_title.edit_upload_image')
    ->middleware('permission:training-by-title');

    Route::post('/save-upload-image', 'Backend\TrainingByTitleController@saveUploadImage')->name('module.training_by_title.save_upload_image')
    ->middleware('permission:training-by-title');

    Route::post('/ajax-training-by-title-category', 'Backend\TrainingByTitleController@ajaxTrainingByTitleCategory')->name('module.ajax_training_by_title_category')
    ->middleware('permission:training-by-title');
});

Route::group(['prefix' => '/admin-cp/training-by-title/detail/{id}'], function() {
    Route::get('/', 'Backend\TrainingByTitleDetailController@index')
        ->name('module.training_by_title.detail')
        ->where('id', '[0-9]+')
        ->middleware('permission:training-by-title');

    Route::post('/save-category', 'Backend\TrainingByTitleDetailController@saveCategory')
        ->name('module.training_by_title.detail.save_category')
        ->where('id', '[0-9]+')
        ->middleware('permission:training-by-title-detail-create');

    Route::post('/remove-category', 'Backend\TrainingByTitleDetailController@removeCategory')
        ->name('module.training_by_title.detail.remove_category')
        ->where('id', '[0-9]+')
        ->middleware('permission:training-by-title-detail-delete');

    Route::post('/save', 'Backend\TrainingByTitleDetailController@save')
        ->name('module.training_by_title.detail.save')
        ->where('id', '[0-9]+')
        ->middleware('permission:training-by-title-detail-create');

    Route::post('/remove', 'Backend\TrainingByTitleDetailController@remove')
        ->name('module.training_by_title.detail.remove')
        ->where('id', '[0-9]+')
        ->middleware('permission:training-by-title-detail-delete');

    Route::post('/edit-detail', 'Backend\TrainingByTitleDetailController@editDetail')
        ->name('module.training_by_title.detail.edit_detail')
        ->where('id', '[0-9]+')
        ->middleware('permission:training-by-title-detail-edit');
});

Route::group(['prefix' => '/admin-cp/training-by-title-result'], function() {
    Route::get('/', 'Backend\TrainingByTitleResultController@index')->name('module.training_by_title.result')
    ->middleware('permission:training-by-title-result');

    Route::get('/getdata', 'Backend\TrainingByTitleResultController@getDataUser')->name('module.training_by_title.result.getdata_user')
    ->middleware('permission:training-by-title-result');

    Route::get('/detail/{user_id}', 'Backend\TrainingByTitleResultController@detail')
        ->name('module.training_by_title.result.detail')
        ->where('user_id', '[0-9]+')
        ->middleware('permission:training-by-title-result');

    Route::get('/getdata-detail/{user_id}', 'Backend\TrainingByTitleResultController@getDataUserDetail')
        ->name('module.training_by_title.result.getdata_detail')
        ->where('user_id', '[0-9]+')
        ->middleware('permission:training-by-title-result');
});
