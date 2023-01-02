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
Route::group(['prefix' => '/libraries', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontendController@index')->name('module.libraries');

    Route::post('/get-author', 'FrontendController@getAuthor')->name('module.libraries.book.get_author');

    Route::post('/get-name-libraries', 'FrontendController@getNameLibraries')->name('module.libraries.book.get_name_libraries');

    Route::match(['get', 'post'],'/book/{id}','FrontendController@book')->name('module.frontend.libraries.book');

    Route::get('/book/detail/{id}', 'FrontendController@bookDetail')->name('module.libraries.book.detail')->where('id', '[0-9]+');

    Route::post('/book/detail/{id}/register-book', 'FrontendController@registerBook')
        ->name('module.frontend.libraries.book.register')
        ->where('id', '[0-9]+');

    Route::match(['get', 'post'],'/ebook/{id}','FrontendController@ebook')->name('module.frontend.libraries.ebook');

    Route::get('/ebook/detail/{id}', 'FrontendController@ebookDetail')->name('module.libraries.ebook.detail')->where('id', '[0-9]+');

    Route::match(['get', 'post'],'/document/{id}','FrontendController@document')->name('module.frontend.libraries.document');

    Route::get('/document/detail/{id}', 'FrontendController@documentDetail')->name('module.libraries.document.detail')->where('id', '[0-9]+');

    Route::match(['get', 'post'],'/audiobook/{id}','FrontendController@audiobook')->name('module.frontend.libraries.audiobook');

    Route::get('/audiobook/detail/{id}', 'FrontendController@audiobookDetail')->name('module.libraries.audiobook.detail')->where('id', '[0-9]+');

    Route::match(['get', 'post'],'/video/{id}','FrontendController@video')->name('module.frontend.libraries.video');

    Route::get('/video/detail/{id}', 'FrontendController@videoDetail')->name('module.libraries.video.detail')->where('id', '[0-9]+');

    Route::post('/update', 'FrontendController@updateItemViews')->name('module.frontend.libraries.update_view');

    Route::post('/save-libraries-bookmark/{id}/{type}', 'FrontendController@saveLibrariesBookmark')
        ->name('module.frontend.libraries.save_bookmark')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+');

    Route::post('/remove-libraries-bookmark/{id}/{type}', 'FrontendController@removeLibrariesBookmark')
        ->name('module.frontend.libraries.remove_bookmark')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+');

    Route::get('/view-pdf/{id}', 'FrontendController@viewPDF')->name('module.libraries.view_pdf')->where('id', '[0-9]+');

    Route::get('/download/{id}', 'FrontendController@download')->name('module.frontend.download')->where('id', '[0-9]+');

    Route::post('/like', 'FrontendController@like')->name('module.frontend.like');

    Route::post('/ratting', 'FrontendController@ratting')->name('module.frontend.ratting');

    Route::get('/cate/{cate_id}/{type}', 'FrontendController@cateLibraries')->name('module.libraries.cate')->where('cate_id', '[0-9]+')->where('type', '[0-9]+');

    Route::get('/search', 'FrontendController@search')->name('module.libraries.search');

    Route::get('/salekit', 'FrontendController@salekit')->name('module.frontend.libraries.salekit');
    Route::get('/salekit/detail/{cate_id}', 'FrontendController@salekitDetail')->name('module.frontend.libraries.salekit.detail')->where('cate_id', '[0-9]+');
});
Route::prefix(url_mobile()? 'AppM':'')->group(function() {
    Route::group(['prefix' => '/libraries-mobile', 'middleware' => 'auth'], function () {
        Route::get('/', 'MobileController@index')->name('themes.mobile.libraries');

        Route::post('/get-author', 'MobileController@getAuthor')->name('themes.mobile.libraries.book.get_author');

        Route::post('/get-name-libraries', 'MobileController@getNameLibraries')->name('themes.mobile.libraries.book.get_name_libraries');

        Route::match(['get', 'post'], '/book/{id}', 'MobileController@book')->name('themes.mobile.frontend.libraries.book');

        Route::get('/book/detail/{id}', 'MobileController@bookDetail')->name('themes.mobile.libraries.book.detail')->where('id', '[0-9]+');

        Route::post('/book/detail/{id}/register-book', 'MobileController@registerBook')
            ->name('themes.mobile.frontend.libraries.book.register')
            ->where('id', '[0-9]+');

        Route::match(['get', 'post'], '/ebook/{id}', 'MobileController@ebook')->name('themes.mobile.frontend.libraries.ebook');

        Route::get('/ebook/detail/{id}', 'MobileController@ebookDetail')->name('themes.mobile.libraries.ebook.detail')->where('id', '[0-9]+');

        Route::match(['get', 'post'], '/document/{id}', 'MobileController@document')->name('themes.mobile.frontend.libraries.document');

        Route::get('/document/detail/{id}', 'MobileController@documentDetail')->name('themes.mobile.libraries.document.detail')->where('id', '[0-9]+');

        Route::match(['get', 'post'], '/audiobook/{id}', 'MobileController@audiobook')->name('themes.mobile.frontend.libraries.audiobook');

        Route::get('/audiobook/detail/{id}', 'MobileController@audiobookDetail')->name('themes.mobile.libraries.audiobook.detail')->where('id', '[0-9]+');

        Route::match(['get', 'post'], '/video/{id}', 'MobileController@video')->name('themes.mobile.frontend.libraries.video');

        Route::get('/video/detail/{id}', 'MobileController@videoDetail')->name('themes.mobile.libraries.video.detail')->where('id', '[0-9]+');

        Route::post('/update', 'MobileController@updateItemViews')->name('themes.mobile.frontend.libraries.update_view');

        Route::post('/save-libraries-bookmark/{id}/{type}', 'MobileController@saveLibrariesBookmark')
            ->name('themes.mobile.frontend.libraries.save_bookmark')
            ->where('id', '[0-9]+')
            ->where('type', '[0-9]+');

        Route::post('/remove-libraries-bookmark/{id}/{type}', 'MobileController@removeLibrariesBookmark')
            ->name('themes.mobile.frontend.libraries.remove_bookmark')
            ->where('id', '[0-9]+')
            ->where('type', '[0-9]+');

        Route::get('/view-pdf/{id}', 'MobileController@viewPDF')->name('themes.mobile.libraries.view_pdf')->where('id', '[0-9]+');

        Route::get('/download/{id}', 'MobileController@download')->name('themes.mobile.libraries.frontend.download')->where('id', '[0-9]+');

        Route::post('/like', 'MobileController@like')->name('themes.mobile.libraries.frontend.like');

        Route::post('/ratting', 'MobileController@ratting')->name('themes.mobile.libraries.frontend.ratting');

        Route::get('/cate/{cate_id}/{type}', 'MobileController@cateLibraries')->name('themes.mobile.libraries.cate')->where('cate_id', '[0-9]+')->where('type', '[0-9]+');

        Route::get('/search', 'MobileController@search')->name('themes.mobile.libraries.search');

        Route::get('/salekit', 'MobileController@salekit')->name('themes.mobile.libraries.salekit');
        Route::get('/salekit/detail/{cate_id}', 'MobileController@salekitDetail')->name('themes.mobile.libraries.salekit.detail')->where('cate_id', '[0-9]+');
    });
});
Route::group(['prefix' => '/admin-cp/libraries/book', 'middleware' => ['auth','permission:libraries-book']], function() {

    Route::get('/', 'BookController@index')->name('module.libraries.book')->middleware('permission:libraries-book');

    Route::get('/getdata', 'BookController@getData')->name('module.libraries.book.getdata')->middleware('permission:libraries-book');

    Route::get('/edit/{id}', 'BookController@form')->name('module.libraries.book.edit')->where('id', '[0-9]+')->middleware('permission:libraries-book-edit');

    Route::get('/create', 'BookController@form')->name('module.libraries.book.create')->middleware('permission:libraries-book-create');

    Route::post('/save', 'BookController@save')->name('module.libraries.book.save')->middleware('permission:libraries-book-create|libraries-book-edit');

    Route::post('/remove', 'BookController@remove')->name('module.libraries.book.remove')->middleware('permission:libraries-book-delete');

    Route::get('/register', 'BookController@register')->name('module.libraries.book.register')->middleware('permission:libraries-book-register');

    Route::get('/register/export', 'BookController@registerExport')->name('module.libraries.book.register.export')->middleware('permission:libraries-book-register');

    Route::get('/getdata-register', 'BookController@getDataRegister')->name('module.libraries.book.register.getdata')->middleware('permission:libraries-book-register');

    Route::post('/remove-register', 'BookController@removeRegister')->name('module.libraries.book.register.remove')->middleware('permission:libraries-book-register-delete');

    Route::post('/approve', 'BookController@approve')->name('module.libraries.book.register.approve')->middleware('permission:libraries-book-register-approve');

    Route::post('/status', 'BookController@status')->name('module.libraries.book.register.status')->middleware('permission:libraries-book-register-borrow');

    Route::get('/export', 'BookController@export')->name('module.libraries.book.export');

    Route::post('/edit/{id}/save-object', 'BookController@saveObject')
    ->name('module.libraries.book.save_object')
    ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-object', 'BookController@getObject')
        ->name('module.libraries.book.get_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-user-object', 'BookController@getUserObject')
        ->name('module.libraries.book.get_user_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'BookController@removeObject')
        ->name('module.libraries.book.remove_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/import-object', 'BookController@importObject')
        ->name('module.libraries.book.import_object')
        ->where('id', '[0-9]+');

    Route::post('/ajax-isopen-publish', 'BookController@ajaxIsopenPublish')->name('module.book.ajax_isopen_publish');

    Route::get('/tree', 'BookController@treeFolder')->name('module.book.tree')->middleware('permission:libraries-book');

    Route::post('/tree/getChild', 'BookController@getChild')->name('module.book.unit.tree.get_child')->middleware('permission:libraries-book');

    Route::post('/tree/get-item', 'BookController@getTreeItem')->name('module.book.unit.tree.get_item')->middleware('permission:libraries-book');
});

// SÁCH NÓI
Route::group(['prefix' => '/admin-cp/libraries/audiobook', 'middleware' => ['auth','permission:libraries-audiobook']], function() {

    Route::get('/', 'AudiobookController@index')->name('module.libraries.audiobook')->middleware('permission:libraries-ebook');

    Route::get('/getdata', 'AudiobookController@getData')->name('module.libraries.audiobook.getdata')->middleware('permission:libraries-ebook');

    Route::get('/edit/{id}', 'AudiobookController@form')->name('module.libraries.audiobook.edit')->where('id', '[0-9]+')->middleware('permission:libraries-ebook-edit');

    Route::get('/create', 'AudiobookController@form')->name('module.libraries.audiobook.create')->middleware('permission:libraries-ebook-create');

    Route::post('/save', 'AudiobookController@save')->name('module.libraries.audiobook.save')->middleware('permission:libraries-ebook-create|libraries-ebook-edit');

    Route::post('/remove', 'AudiobookController@remove')->name('module.libraries.audiobook.remove')->middleware('permission:libraries-ebook-delete');

    Route::post('/edit/{id}/save-object', 'AudiobookController@saveObject')
        ->name('module.libraries.audiobook.save_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-object', 'AudiobookController@getObject')
        ->name('module.libraries.audiobook.get_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-user-object', 'AudiobookController@getUserObject')
        ->name('module.libraries.audiobook.get_user_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'AudiobookController@removeObject')
        ->name('module.libraries.audiobook.remove_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/import-object', 'AudiobookController@importObject')
        ->name('module.libraries.audiobook.import_object')
        ->where('id', '[0-9]+');

    Route::get('/export', 'AudiobookController@export')->name('module.libraries.audiobook.export');

    Route::post('/ajax-isopen-publish', 'AudiobookController@ajaxIsopenPublish')->name('module.audiobook.ajax_isopen_publish');

    Route::get('/tree', 'AudiobookController@treeFolder')->name('module.audiobook.tree')->middleware('permission:libraries-audiobook');

    Route::post('/tree/getChild', 'AudiobookController@getChild')->name('module.audiobook.unit.tree.get_child')->middleware('permission:libraries-audiobook');

    Route::post('/tree/get-item', 'AudiobookController@getTreeItem')->name('module.audiobook.unit.tree.get_item')->middleware('permission:libraries-audiobook');
});

Route::group(['prefix' => '/admin-cp/libraries/ebook', 'middleware' => ['auth','permission:libraries-ebook']], function() {
    Route::get('/', 'EbookController@index')->name('module.libraries.ebook')->middleware('permission:libraries-ebook');

    Route::get('/getdata', 'EbookController@getData')->name('module.libraries.ebook.getdata')->middleware('permission:libraries-ebook');

    Route::get('/edit/{id}', 'EbookController@form')->name('module.libraries.ebook.edit')->where('id', '[0-9]+')->middleware('permission:libraries-ebook-edit');

    Route::get('/create', 'EbookController@form')->name('module.libraries.ebook.create')->middleware('permission:libraries-ebook-create');

    Route::post('/save', 'EbookController@save')->name('module.libraries.ebook.save')->middleware('permission:libraries-ebook-create|libraries-ebook-edit');

    Route::post('/remove', 'EbookController@remove')->name('module.libraries.ebook.remove')->middleware('permission:libraries-ebook-delete');

    Route::post('/edit/{id}/save-object', 'EbookController@saveObject')
        ->name('module.libraries.ebook.save_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-object', 'EbookController@getObject')
        ->name('module.libraries.ebook.get_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-user-object', 'EbookController@getUserObject')
        ->name('module.libraries.ebook.get_user_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'EbookController@removeObject')
        ->name('module.libraries.ebook.remove_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/import-object', 'EbookController@importObject')
        ->name('module.libraries.ebook.import_object')
        ->where('id', '[0-9]+');

    Route::get('/export', 'EbookController@export')->name('module.libraries.ebook.export');

    Route::post('/ajax-isopen-publish', 'EbookController@ajaxIsopenPublish')->name('module.ebook.ajax_isopen_publish');

    Route::get('/tree', 'EbookController@treeFolder')->name('module.ebook.tree')->middleware('permission:libraries-ebook');

    Route::post('/tree/getChild', 'EbookController@getChild')->name('module.ebook.unit.tree.get_child')->middleware('permission:libraries-ebook');

    Route::post('/tree/get-item', 'EbookController@getTreeItem')->name('module.ebook.unit.tree.get_item')->middleware('permission:libraries-ebook');
});

Route::group(['prefix' => '/admin-cp/libraries/document', 'middleware' => ['auth','permission:libraries-document']], function() {
    Route::get('/', 'DocumentController@index')->name('module.libraries.document')->middleware('permission:libraries-document');

    Route::get('/getdata', 'DocumentController@getData')->name('module.libraries.document.getdata')->middleware('permission:libraries-document');

    Route::get('/edit/{id}', 'DocumentController@form')->name('module.libraries.document.edit')->where('id', '[0-9]+')->middleware('permission:libraries-document-edit');

    Route::get('/create', 'DocumentController@form')->name('module.libraries.document.create')->middleware('permission:libraries-document-create');

    Route::post('/save', 'DocumentController@save')->name('module.libraries.document.save')->middleware('permission:libraries-document-create|libraries-document-edit');

    Route::post('/remove', 'DocumentController@remove')->name('module.libraries.document.remove')->middleware('permission:libraries-document-delete');

    Route::post('/edit/{id}/save-object', 'DocumentController@saveObject')
        ->name('module.libraries.document.save_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-object', 'DocumentController@getObject')
        ->name('module.libraries.document.get_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-user-object', 'DocumentController@getUserObject')
        ->name('module.libraries.document.get_user_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'DocumentController@removeObject')
        ->name('module.libraries.document.remove_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/import-object', 'DocumentController@importObject')
        ->name('module.libraries.document.import_object')
        ->where('id', '[0-9]+');

    Route::get('/export', 'DocumentController@export')->name('module.libraries.document.export');

    Route::post('/ajax-isopen-publish', 'DocumentController@ajaxIsopenPublish')->name('module.document.ajax_isopen_publish');

    Route::get('/tree', 'DocumentController@treeFolder')->name('module.document.tree')->middleware('permission:libraries-document');

    Route::post('/tree/getChild', 'DocumentController@getChild')->name('module.document.unit.tree.get_child')->middleware('permission:libraries-document');

    Route::post('/tree/get-item', 'DocumentController@getTreeItem')->name('module.document.unit.tree.get_item')->middleware('permission:libraries-document');
});

Route::group(['prefix' => '/admin-cp/libraries/category-libraries', 'middleware' => ['auth','permission:libraries-category']], function() {
    Route::get('/', 'CategoryController@index')->name('module.libraries.category')->middleware('permission:libraries-category');

    Route::get('/getdata', 'CategoryController@getData')->name('module.libraries.category.getdata')->middleware('permission:libraries-category');

    Route::post('/edit', 'CategoryController@form')->name('module.libraries.category.edit')->where('id', '[0-9]+')->middleware('permission:libraries-category-edit');

    Route::get('/create', 'CategoryController@form')->name('module.libraries.category.create')->middleware('permission:libraries-category-create');

    Route::post('/save', 'CategoryController@save')->name('module.libraries.category.save')->middleware('permission:libraries-category-create|libraries-category-edit');

    Route::post('/remove', 'CategoryController@remove')->name('module.libraries.category.remove')->middleware('permission:libraries-category-delete');

    Route::post('/ajax-load-parent', 'CategoryController@ajaxLoadParent')->name('module.libraries.category.ajax_load_parent')->middleware('permission:libraries-category-create');
});

Route::group(['prefix' => '/admin-cp/libraries/video', 'middleware' => ['auth','permission:libraries-video']], function() {
    Route::get('/', 'VideoController@index')->name('module.libraries.video')->middleware('permission:libraries-video');

    Route::get('/getdata', 'VideoController@getData')->name('module.libraries.video.getdata')->middleware('permission:libraries-video');

    Route::get('/edit/{id}', 'VideoController@form')->name('module.libraries.video.edit')->where('id', '[0-9]+')->middleware('permission:libraries-video-edit');

    Route::get('/create', 'VideoController@form')->name('module.libraries.video.create')->middleware('permission:libraries-video-create');

    Route::post('/save', 'VideoController@save')->name('module.libraries.video.save')->middleware('permission:libraries-video-create|libraries-video-edit');

    Route::post('/remove', 'VideoController@remove')->name('module.libraries.video.remove')->middleware('permission:libraries-video-delete');

    Route::post('/edit/{id}/save-object', 'VideoController@saveObject')
        ->name('module.libraries.video.save_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-object', 'VideoController@getObject')
        ->name('module.libraries.video.get_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-user-object', 'VideoController@getUserObject')
        ->name('module.libraries.video.get_user_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'VideoController@removeObject')
        ->name('module.libraries.video.remove_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/import-object', 'VideoController@importObject')
        ->name('module.libraries.video.import_object')
        ->where('id', '[0-9]+');

    Route::get('/export', 'VideoController@export')->name('module.libraries.video.export');

    Route::post('/ajax-isopen-publish', 'VideoController@ajaxIsopenPublish')->name('module.video.ajax_isopen_publish');

    Route::get('/tree', 'VideoController@treeFolder')->name('module.video.tree')->middleware('permission:libraries-video');

    Route::post('/tree/getChild', 'VideoController@getChild')->name('module.video.unit.tree.get_child')->middleware('permission:libraries-video');

    Route::post('/tree/get-item', 'VideoController@getTreeItem')->name('module.video.unit.tree.get_item')->middleware('permission:libraries-video');
});

Route::group(['prefix' => '/admin-cp/libraries/salekit', 'middleware' => ['auth','permission:libraries-salekit']], function() {
    Route::get('/', 'SalekitController@index')->name('module.libraries.salekit')->middleware('permission:libraries-salekit');

    Route::get('/getdata', 'SalekitController@getData')->name('module.libraries.salekit.getdata')->middleware('permission:libraries-salekit');

    Route::get('/edit/{id}', 'SalekitController@form')->name('module.libraries.salekit.edit')->where('id', '[0-9]+')->middleware('permission:libraries-salekit-edit');

    Route::get('/create', 'SalekitController@form')->name('module.libraries.salekit.create')->middleware('permission:libraries-salekit-create');

    Route::post('/save', 'SalekitController@save')->name('module.libraries.salekit.save')->middleware('permission:libraries-salekit-create|libraries-salekit-edit');

    Route::post('/remove', 'SalekitController@remove')->name('module.libraries.salekit.remove')->middleware('permission:libraries-salekit-delete');

    Route::post('/edit/{id}/save-object', 'SalekitController@saveObject')->name('module.libraries.salekit.save_object')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-object', 'SalekitController@getObject')->name('module.libraries.salekit.get_object')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-user-object', 'SalekitController@getUserObject')->name('module.libraries.salekit.get_user_object')->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'SalekitController@removeObject')->name('module.libraries.salekit.remove_object')->where('id', '[0-9]+');

    Route::post('/edit/{id}/import-object', 'SalekitController@importObject')->name('module.libraries.salekit.import_object')->where('id', '[0-9]+');

    Route::get('/export', 'SalekitController@export')->name('module.libraries.salekit.export');

    Route::post('/ajax-isopen-publish', 'SalekitController@ajaxIsopenPublish')->name('module.salekit.ajax_isopen_publish');
});

Route::group(['prefix' => '/admin-cp/libraries/reward-point', 'middleware' => 'auth'], function() {
    Route::get('/getdata/{id}/{type}', 'BackendController@getDataRewardPoint')->name('module.libraries.reward_point.get_data');
    Route::post('/save/{id}/{type}', 'BackendController@saveRewardPoint')->name('module.libraries.reward_point.save');
});


