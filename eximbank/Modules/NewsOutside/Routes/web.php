<?php

Route::group(['prefix' => '/news-outside/{cate_id}'], function() {
    Route::get('/{parent_id}/{type}', 'FrontendController@index')->name('module.frontend.news_outside')->where('cate_id','[0-9]+')->where('parent_id','[0-9]+')->where('type','[0-9]+');

    Route::get('/detail/{id}', 'FrontendController@detail')->name('module.frontend.news_outside.detail')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/news-outside', 'middleware' => 'auth'], function() {
    Route::get('/', 'NewsOutsideController@index')->name('module.news_outside.manager')
    ->middleware('permission:news-outside-list');

    Route::get('/getdata', 'NewsOutsideController@getData')->name('module.news_outside.getdata')
    ->middleware('permission:news-outside-list');

    Route::get('/edit/{id}', 'NewsOutsideController@form')->name('module.news_outside.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:news-outside-list-edit');

    Route::get('/create', 'NewsOutsideController@form')->name('module.news_outside.create')
    ->middleware('permission:news-outside-list-create');

    Route::post('/save', 'NewsOutsideController@save')->name('module.news_outside.save')
    ->middleware('permission:news-outside-list-create');

    Route::post('/remove', 'NewsOutsideController@remove')->name('module.news_outside.remove')
    ->middleware('permission:news-outside-list-delete');

    Route::post('/ajax-isopen-publish', 'NewsOutsideController@ajaxIsopenPublish')->name('module.news_outside.ajax_isopen_publish')
    ->middleware('permission:news-outside-list');
});

Route::group(['prefix' => '/admin-cp/category-news-outside', 'middleware' => 'auth'], function() {
    Route::get('/', 'CategoryController@index')->name('module.news_outside.category')
    ->middleware('permission:news-outside-category');

    Route::get('/getdata', 'CategoryController@getData')->name('module.news_outside.category.getdata')
    ->middleware('permission:news-outside-category');

    Route::post('/edit', 'CategoryController@form')->name('module.news_outside.category.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:news-outside-category-edit');

    Route::post('/save', 'CategoryController@save')->name('module.news_outside.category.save')
    ->middleware('permission:news-outside-category-create');

    Route::post('/remove', 'CategoryController@remove')->name('module.news_outside.category.remove')
    ->middleware('permission:news-outside-category-delete');
});
