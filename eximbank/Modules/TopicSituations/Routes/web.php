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
// FRONTEND
Route::group(['prefix' => '/topic-situations', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontendController@index')->name('frontend.topic_situations');
    Route::get('/get-situation/{id}', 'FrontendController@getSituation')->name('frontend.get.situations');
    Route::get('/situation-detail/{id}/{situation_id}', 'FrontendController@situationDetail')->name('frontend.situations.detail');
    Route::post('/like-situation', 'FrontendController@likeSituation')->name('frontend.like.situations');
    Route::post('/like-comment-situation', 'FrontendController@likeComment')->name('frontend.like.comment_situation');
    Route::post('/like-reply-comment-situation', 'FrontendController@likeReplyComment')->name('frontend.like.reply.comment_situation');
});

// BACKEND
Route::group(['prefix' => '/admin-cp/topic-situations', 'middleware' => 'auth'], function() {
    Route::get('/', 'TopicSituationsController@index')->name('module.topic_situations')
    ->middleware('permission:topic');

    Route::get('/getdata', 'TopicSituationsController@getData')->name('module.topic_situations.getdata')
    ->middleware('permission:topic');

    Route::post('/edit', 'TopicSituationsController@form')->name('module.topic_situations.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:topic-edit');

    Route::post('/save', 'TopicSituationsController@save')->name('module.topic_situations.save')
    ->middleware('permission:topic-create');

    Route::post('/remove', 'TopicSituationsController@remove')->name('module.topic_situations.remove')
    ->middleware('permission:topic-delete');

    Route::post('/ajax-isopen-publish', 'TopicSituationsController@ajaxIsopenPublish')->name('module.topic_situations.ajax_isopen_publish')
    ->middleware('permission:topic');

    // TÌNH HUỐNG
    Route::get('/situations/{id}', 'TopicSituationsController@situation')->name('module.situations')
    ->middleware('permission:situation');

    Route::get('/get-situations/{id}', 'TopicSituationsController@getSituation')->name('module.get.situations')
    ->middleware('permission:situation');

    Route::get('/create-situations/{id}', 'TopicSituationsController@createSituations')->name('module.create.situations')
    ->middleware('permission:situation-create');

    Route::post('/ajax-edit-situations', 'TopicSituationsController@ajaxEditSituations')->name('module.ajax.edit.situations')
    ->middleware('permission:situation-edit');

    Route::post('/save-situations/{id}', 'TopicSituationsController@saveSituations')->name('module.save.situations')
    ->middleware('permission:situation-create');

    Route::post('/remove-situations/{id}', 'TopicSituationsController@removeSituations')->name('module.remove.situations')
    ->middleware('permission:situation-delete');

    // BÌNH LUẬN TÌNH HUỐNG
    Route::get('/comment-situations/{id}/{situation}', 'TopicSituationsController@commentSituations')->name('module.comment.situations');
    Route::get('/get-comment-situations/{id}/{situation}', 'TopicSituationsController@getCommentSituation')->name('module.get.comment.situations');
});

