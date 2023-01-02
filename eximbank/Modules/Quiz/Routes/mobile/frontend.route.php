<?php

require_once (__DIR__ . '/quiz.route.php');

Route::group(['prefix' => '/AppM/quiz', 'middleware' => 'quiz.secondary'], function() {
    Route::get('/', 'Mobile\FrontendController@index')->name('module.quiz.mobile');
});
