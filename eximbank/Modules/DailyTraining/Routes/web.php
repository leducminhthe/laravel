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

Route::group(['prefix' => '/daily-training', 'middleware' => 'auth'], function() {
    Route::get('/', 'Frontend\DailyTrainingVideoController@index')->name('module.daily_training.frontend');

    Route::get('/my-video', 'Frontend\DailyTrainingVideoController@myVideo')->name('module.daily_training.frontend.my_video');

    Route::get('/cate/{id}', 'Frontend\DailyTrainingVideoController@dailyCate')->name('module.daily_training_cate.frontend');

    Route::get('/add-video', 'Frontend\DailyTrainingVideoController@addVideo')->name('module.daily_training.frontend.add_video');

    Route::post('/save-video', 'Frontend\DailyTrainingVideoController@saveVideo')->name('module.daily_training.frontend.save_video');

    Route::post('/upload-video', 'Frontend\DailyTrainingVideoController@upload')->name('module.daily_training.frontend.upload_video');

    Route::post('/disable-video', 'Frontend\DailyTrainingVideoController@disableVideo')->name('module.daily_training.frontend.disable_video');

    Route::get('/search-AppM', 'Frontend\DailyTrainingVideoController@search')
        ->name('module.daily_training.frontend.search');

    Route::get('/detail-video/{id}', 'Frontend\DailyTrainingVideoController@detailVideo')
        ->name('module.daily_training.frontend.detail_video')
        ->where('id', '[0-9]+');

    Route::post('/like-video/{id}', 'Frontend\DailyTrainingVideoController@likeVideo')
        ->name('module.daily_training.frontend.like_video')
        ->where('id', '[0-9]+');

    Route::post('/comment-video/{id}', 'Frontend\DailyTrainingVideoController@commentVideo')
        ->name('module.daily_training.frontend.comment_video')
        ->where('id', '[0-9]+');

    Route::post('/like-comment-video/{id}', 'Frontend\DailyTrainingVideoController@likeCommentVideo')
        ->name('module.daily_training.frontend.like_comment_video')
        ->where('id', '[0-9]+');

});

Route::group(['prefix' => '/admin-cp/daily-training', 'middleware' => 'auth'], function() {

    Route::get('/', 'Backend\DailyTrainingCategoryController@index')->name('module.daily_training')->middleware('permission:daily-training');

    Route::get('/getdata', 'Backend\DailyTrainingCategoryController@getData')->name('module.daily_training.getdata')->middleware('permission:daily-training');

    Route::post('/edit', 'Backend\DailyTrainingCategoryController@form')->name('module.daily_training.edit')
        ->where('id', '[0-9]+')->middleware('permission:daily-training-edit');

    Route::post('/save', 'Backend\DailyTrainingCategoryController@save')->name('module.daily_training.save')->middleware('permission:daily-training-save|daily-training-create');

    Route::post('/remove', 'Backend\DailyTrainingCategoryController@remove')->name('module.daily_training.remove')->middleware('permission:daily-training-delete');

    Route::get('/permission/{cate_id}', 'Backend\DailyTrainingCategoryController@permission')->name('module.daily_training.permission');

    Route::get('/user/getdata/{cate_id}', 'Backend\DailyTrainingCategoryController@getUserPermission')
        ->name('module.daily_training.user.getdata')
        ->where('category','[0-9]+');

    Route::post('/user/save-permission', 'Backend\DailyTrainingCategoryController@savePermissionUser')
        ->name('module.daily_training.user.save_permission');

    Route::get('/reward-point/{cate_id}', 'Backend\DailyTrainingCategoryController@rewardPoint')
        ->name('module.daily_training.reward_point')->middleware('permission:daily-training');
});

Route::group([
    'prefix' => '/admin-cp/daily-training/training-video/{cate_id}',
    'middleware' => 'auth',
    'where' => [
        'cate_id' => '[0-9]+'
    ]
], function() {

    Route::get('/', 'Backend\DailyTrainingVideoController@index')->name('module.daily_training.video')
    ->middleware('permission:daily-training-video');

    Route::get('/getdata', 'Backend\DailyTrainingVideoController@getData')->name('module.daily_training.video.getdata')
    ->middleware('permission:daily-training-video');

    Route::post('/remove', 'Backend\DailyTrainingVideoController@remove')->name('module.daily_training.video.remove')
    ->middleware('permission:daily-training-video-delete');

    Route::post('/approve', 'Backend\DailyTrainingVideoController@approve')->name('module.daily_training.video.approve')
    ->middleware('permission:daily-training-video-acceept');

    Route::post('/modal-info/{video_id}', 'Backend\DailyTrainingVideoController@modalInfo')->name('module.daily_training.video.modal_info')
    ->where('video_id','[0-9]+')
    ->middleware('permission:daily-training-video');

    Route::get('/view-comment/{video_id}', 'Backend\DailyTrainingVideoController@viewComment')
        ->name('module.daily_training.video.view_comment')
        ->middleware('permission:daily-training-video');

    Route::post('/check-failed-comment/{video_id}', 'Backend\DailyTrainingVideoController@checkFailedComment')
        ->name('module.daily_training.video.check_failed_comment')
        ->middleware('permission:daily-training-video');

    Route::get('/view-report/{video_id}', 'Backend\DailyTrainingVideoController@viewReport')
        ->name('module.daily_training.video.view_report')
        ->middleware('permission:daily-training-video');

    Route::get('/view-report/{video_id}/getdata', 'Backend\DailyTrainingVideoController@getDataReport')
        ->name('module.daily_training.video.report.getdata')
        ->middleware('permission:daily-training-video');
});

Route::group(['prefix' => '/admin-cp/score-views/{category_id}', 'middleware' => 'auth'], function() {

    Route::get('/getdata', 'Backend\DailyTrainingScoreViewsController@getData')->name('module.daily_training.score_views.getdata')
    ->middleware('permission:daily-training');

    Route::post('/edit', 'Backend\DailyTrainingScoreViewsController@form')->name('module.daily_training.score_views.edit')
        ->where('id', '[0-9]+')->middleware('permission:score-edit')
        ->middleware('permission:daily-training');

    Route::post('/save', 'Backend\DailyTrainingScoreViewsController@save')->name('module.daily_training.score_views.save')
    ->middleware('permission:daily-training');

    Route::post('/remove', 'Backend\DailyTrainingScoreViewsController@remove')->name('module.daily_training.score_views.remove')
    ->middleware('permission:daily-training');
});

Route::group(['prefix' => '/admin-cp/score-like/{category_id}', 'middleware' => 'auth'], function() {

    Route::get('/getdata', 'Backend\DailyTrainingScoreLikeController@getData')->name('module.daily_training.score_like.getdata')
    ->middleware('permission:daily-training');

    Route::post('/edit', 'Backend\DailyTrainingScoreLikeController@form')->name('module.daily_training.score_like.edit')
        ->where('id', '[0-9]+')->middleware('permission:score-like-edit')
        ->middleware('permission:daily-training');

    Route::post('/save', 'Backend\DailyTrainingScoreLikeController@save')->name('module.daily_training.score_like.save')
    ->middleware('permission:daily-training');

    Route::post('/remove', 'Backend\DailyTrainingScoreLikeController@remove')->name('module.daily_training.score_like.remove')
    ->middleware('permission:daily-training');
});

Route::group(['prefix' => '/admin-cp/score-comment/{category_id}', 'middleware' => 'auth'], function() {

    Route::get('/getdata', 'Backend\DailyTrainingScoreCommentController@getData')->name('module.daily_training.score_comment.getdata')
    ->middleware('permission:daily-training');

    Route::post('/edit', 'Backend\DailyTrainingScoreCommentController@form')->name('module.daily_training.score_comment.edit')
        ->where('id', '[0-9]+')->middleware('permission:score-comment-edit')
        ->middleware('permission:daily-training');

    Route::post('/save', 'Backend\DailyTrainingScoreCommentController@save')->name('module.daily_training.score_comment.save')
    ->middleware('permission:daily-training');

    Route::post('/remove', 'Backend\DailyTrainingScoreCommentController@remove')->name('module.daily_training.score_comment.remove')
    ->middleware('permission:daily-training');
});

Route::group(['prefix' => '/admin-cp/another/{category_id}', 'middleware' => 'auth'], function() {

    Route::get('/getdata', 'Backend\DailyTrainingCategoryController@getDataAnother')->name('module.daily_training.another.getdata')
    ->middleware('permission:daily-training');

    Route::post('/save', 'Backend\DailyTrainingCategoryController@saveAnother')->name('module.daily_training.another.save')
    ->middleware('permission:daily-training');
});
