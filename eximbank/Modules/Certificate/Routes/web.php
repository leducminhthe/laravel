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

//Route::prefix('certificate')->group(function() {
//    Route::get('/', 'CertificateController@index');
//});


Route::group(['prefix' => '/admin-cp/certificate', 'middleware' => 'auth'], function() {
    Route::get('/', 'CertificateController@index')->name('module.certificate')->middleware('permission:certificate-template');
    Route::get('/getdata', 'CertificateController@getData')->name('module.certificate.getdata')->middleware('permission:certificate-template');
    Route::post('/edit', 'CertificateController@form')->name('module.certificate.edit')->where('id', '[0-9]+')->middleware('permission:certificate-template-edit');
    Route::post('/save', 'CertificateController@save')->name('module.certificate.save')->middleware('permission:certificate-template-create|certificate-template-edit');
    Route::post('/remove', 'CertificateController@remove')->name('module.certificate.remove')->middleware('permission:certificate-template-delete');

    Route::get('/design', 'CertificateController@design')->name('module.certificate.design')->where('id', '[0-9]+')->middleware('permission:certificate-template');
    Route::post('/design/save', 'CertificateController@saveDesign')->name('module.certificate.design.save')->where('id', '[0-9]+')->middleware('permission:certificate-template-create|certificate-template-edit');

});
