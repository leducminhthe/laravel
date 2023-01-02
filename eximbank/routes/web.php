<?php
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
include_once __DIR__ . '/file-manager/routes.php';

include_once __DIR__ . '/component/auth.route.php';

//Backend
Route::group(['middleware' => 'auth'], function() {
    include_once __DIR__ . '/component/backend.route.php';
});

//Frontend
Route::group(['middleware' => ['notify', 'auth']], function() {
    include_once __DIR__ . '/component/frontend.route.php';
});

//Mobile
Route::group(['prefix' => '/AppM', 'middleware' => ['notify', 'auth']], function() {
    include_once __DIR__ . '/component/mobile.route.php';
});

//Change language
Route::group(['middleware' => 'locale'], function() {
    Route::get('change-language/{language}', 'Frontend\HomeController@changeLanguage')->name('change_language');
});

//Chọn đơn vị
Route::post('choose-unit-modal', 'ChooseUnit@chooseUnitModal')->name('choose_unit_modal');
Route::get('load-unit-modal', 'ChooseUnit@loadUnitModal')->name('load_unit_modal');
Route::get('back-unit-modal', 'ChooseUnit@backUnitModal')->name('back_unit_modal');
Route::get('search-unit-modal', 'ChooseUnit@searchUnitModal')->name('search_unit_modal');

// NIGHT MODE
Route::post('night-mode', 'NightModeController@settingNightMode')->name('setting_night_mode');

// THƯ VIỆN SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/library/{type}/{path?}', [
        'uses' => 'React\SinglePageController@index'
    ])->name('library')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');

    Route::match(['get', 'post'],'/get-libraries', 'React\SinglePageController@getLibraryBooks')->name('get_libraries_book');
    Route::post('/ratting-start-library', 'React\SinglePageController@rattingStartLibrary')->name('ratting_start_library');
    Route::post('/register-book-library/{id}', 'React\SinglePageController@registerBookLibrary')->name('register_book_library');
    Route::get('/detail-library-book/{id}', 'React\SinglePageController@detailLibraryBook')->name('detail_library_book');
    Route::get('/detail-library-ebook/{id}', 'React\SinglePageController@detailLibraryEbook')->name('detail_library_ebook');
    Route::get('/detail-library-audiobook/{id}', 'React\SinglePageController@detailLibraryAudiobook')->name('detail_library_audio_book');
    Route::get('/detail-library-video/{id}', 'React\SinglePageController@detailLibraryVideo')->name('detail_library_video');
    Route::get('/count-download-library/{id}', 'React\SinglePageController@download')->name('count_download_library');
    Route::get('/view-file-library/{id}', 'React\SinglePageController@viewFile')->name('view_file_library');
});

// KHẢO SÁT SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/survey-react/{path?}', [
        'uses' => 'React\SurveyReactController@index'
    ])->name('survey_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');

    Route::get('/get-survey', 'React\SurveyReactController@getSurvey');
    Route::get('/get-survey-user/{id}/{courseId}/{courseType}', 'React\SurveyReactController@getSurveyUser');
    Route::get('/get-survey-online/{id}', 'React\SurveyReactController@getSurveyOnline');
    Route::get('/edit-survey-user/{id}', 'React\SurveyReactController@editSurveyUser');
    Route::get('/edit-survey-user-online/{id}', 'React\SurveyReactController@editSurveyUserOnline');
    Route::post('/save-survey-user', 'React\SurveyReactController@saveSurveyUser');
    Route::post('/save-survey-online', 'React\SurveyReactController@saveSurveyOnline');
    Route::post('/save-survey-answer-online', 'React\SurveyReactController@saveSurveyAnwserOnline');
});

// GÓP Ý SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/suggest-react/{path?}', [
        'uses' => 'React\SuggestReactController@index'
    ])->name('suggest_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');

    Route::get('/get-suggest', 'React\SuggestReactController@getSuggest');
    Route::get('/get-comments-suggest/{id}', 'React\SuggestReactController@commentSugget');
    Route::post('/save-user-comment/{id}', 'React\SuggestReactController@saveUserComment');
    Route::post('/save-check-reply-suggest', 'React\SuggestReactController@saveCheckReplySuggest');
});

// GHI CHÚ SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/note-react/{path?}', [
        'uses' => 'React\NoteReactController@index'
    ])->name('note_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');

    Route::get('/data-note-user', 'React\NoteReactController@getData');
    Route::post('/remove-note-user', 'React\NoteReactController@remove');
});

// QUÀ TẶNG SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/promotion-react/{path?}', [
        'uses' => 'React\PromotionReactController@index'
    ])->name('promotion_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');

    Route::get('/data-promotion', 'React\PromotionReactController@getData');
    Route::post('/get-promotion-order/{id}', 'React\PromotionReactController@getPromotion');
    Route::get('/data-user-max-point', 'React\PromotionReactController@getUserMaxPoint');
});

// CÂU HỎI SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/faq-react/{path?}', [
        'uses' => 'React\FaqReactController@index'
    ])->name('faq_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');
    Route::get('/data-faq', 'React\FaqReactController@getData')->name('data_faq');
});

// XỬ LÝ TÌNH HUỐNG SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/topic-situation-react/{path?}', [
        'uses' => 'React\TopicSituationsReactController@index'
    ])->name('topic_situation_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');

    Route::get('/data-topic', 'React\TopicSituationsReactController@getDataTopic');
    Route::get('/data-situation/{id}', 'React\TopicSituationsReactController@getDataSituation');
    Route::get('/data-situation-detail/{topic}/{id}', 'React\TopicSituationsReactController@dataSituationDetail');
    Route::get('/data-comment-situation/{topic}/{id}', 'React\TopicSituationsReactController@dataCommentSituation');
    Route::post('/user-like-situation/{id}', 'React\TopicSituationsReactController@userLikeSituation');
    Route::post('/user-like-comment-situation', 'React\TopicSituationsReactController@userLikeComment');
    Route::post('/user-like-reply-situation', 'React\TopicSituationsReactController@userLikeReply');
    Route::post('/user-comment-situation', 'React\TopicSituationsReactController@userCommentSituation');
    Route::post('/user-reply-comment-situation', 'React\TopicSituationsReactController@userReplyCommentSituation');
    Route::post('/user-delete-comment-situation', 'React\TopicSituationsReactController@userDeleteCommentSituation');
});

// HƯỚNG DẪN SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/guide-react/{type}/{path?}', [
        'uses' => 'React\GuideReactController@index'
    ])->name('guide_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');

    Route::get('/data-guide/{type}', 'React\GuideReactController@dataGuide')->name('data_guide');
    Route::get('/data-post-detail/{id}', 'React\GuideReactController@postDetail')->name('data_post_detail');
});

// DIỄN ĐÀN SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/forums-react/{path?}', [
        'uses' => 'React\ForumsReactController@index'
    ])->name('forums_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');

    Route::get('/data-forums', 'React\ForumsReactController@dataForums')->name('data_forums');
    Route::get('/data-topic/{id}', 'React\ForumsReactController@dataTopic')->name('data_topic');
    Route::get('/data-thread/{id}', 'React\ForumsReactController@dataThread')->name('data_thread');
    Route::get('/create-thread/{topic_id}', 'React\ForumsReactController@createThread')->name('create_thread');
    Route::get('/edit-thread/{id}', 'React\ForumsReactController@editThread')->name('edit_thread');
    Route::post('/save-thread/{id}', 'React\ForumsReactController@saveThread')->name('save_thread');
    Route::post('/like-dislike-thread/{id}/{type}','React\ForumsReactController@likeThread')->name('like_dislike_thread');
    Route::get('/data-thread-comment/{id}', 'React\ForumsReactController@dataThreadComment')->name('data_thread_comment');
    Route::post('/send-thread-comment/{id}', 'React\ForumsReactController@sendThreadComment')->name('send_thread_comment');
    Route::post('/remove-thread/{id}', 'React\ForumsReactController@removeThread')->name('remove_thread');
    Route::post('/remove-thread-comment/{id}', 'React\ForumsReactController@removeThreadComment')->name('remove_thread_comment');
});

// ĐÀO TẠO VIDEO SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/daily-training-react/{type}/{path?}', [
        'uses' => 'React\DailyTrainingReactController@index'
    ])->name('daily_training_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');

    Route::get('/category-daily-training', 'React\DailyTrainingReactController@categoryDailyTraining');
    Route::get('/data-daily-training', 'React\DailyTrainingReactController@dataDailyTraining');
    Route::get('/related-video-daily-training/{id}', 'React\DailyTrainingReactController@relatedVideo');
    Route::get('/detail-daily-training/{id}', 'React\DailyTrainingReactController@detailDailyTraining');
    Route::get('/detail-comment-daily-training/{id}', 'React\DailyTrainingReactController@detailCommentDailyTraining');
    Route::post('/comment-daily-training/{id}', 'React\DailyTrainingReactController@commentDailyTraining');
    Route::post('/like-dislike-video-daily-training/{id}', 'React\DailyTrainingReactController@likeDislikeVideoDailyTraining');
    Route::post('/like-dislike-comment-daily-training/{id}', 'React\DailyTrainingReactController@likeDislikeCommentDailyTraining');
    Route::post('/create-video-daily-training', 'React\DailyTrainingReactController@createVideoDailyTraining');
    Route::post('/upload-video-daily-training', 'React\DailyTrainingReactController@uploadVideoDailyTraining');
    Route::get('/data-video-view-new', 'React\DailyTrainingReactController@dataDailyTrainingViewNew');
    Route::post('/user-save-video', 'React\DailyTrainingReactController@userSaveVideo');
    Route::post('/user-disable-video', 'React\DailyTrainingReactController@disableVideo');
});

// KỲ THI SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/quiz-react/{path?}', [
        'uses' => 'React\QuizReactController@index'
    ])->name('quiz_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');

    Route::get('/data-quiz-type', 'React\QuizReactController@dataQuizType')->name('data_quiz_type');
    Route::get('/data-quiz', 'React\QuizReactController@dataQuiz')->name('data_quiz');
});

// TIN TỨC SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/news-react/{path?}', [
        'uses' => 'React\NewsReactController@index'
    ])->name('news_react')->where('path', '[a-zA-Z0-9-/]+')->middleware('pagespeed');
    Route::get('/data-menu-news', 'React\NewsReactController@dataMenuNews');
    Route::get('/cate-news-name/{cate_id}/{id}', 'React\NewsReactController@cateNewsName');
    Route::get('/data-cate-news/{cate_id}/{id}', 'React\NewsReactController@dataCateNews');
    Route::get('/data-news-right', 'React\NewsReactController@dataNewsRight');
    Route::get('/data-news', 'React\NewsReactController@dataNews');
    Route::get('/data-advertisement', 'React\NewsReactController@dataAdvertisement');
    Route::get('/data-detail-new/{id}', 'React\NewsReactController@dataDetailNew');
    Route::get('/data-new-view-like/{type}', 'React\NewsReactController@dataNewViewLike');
    Route::get('/related-new/{cate_id}/{id}', 'React\NewsReactController@relatedNew');
    Route::post('/user-like-new', 'React\NewsReactController@likeNew');
});

// KHÓA HỌC SỬ DỤNG REACT
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/course-react/{type}/{path?}', [
        'uses' => 'React\CourseReactController@index'
    ])->name('course_react')->where('path', '[a-zA-Z0-9-/]+');
    Route::post('/data-course/{type}', 'React\CourseReactController@dataCourse')->name('data_course');
    Route::get('/data-training-program', 'React\CourseReactController@dataTrianingProgram')->name('data_training_program');
});


// MẠNG XÃ HỘI
Route::group(['middleware' => ['notify', 'auth']], function() {
    Route::get('/social-network/{path?}', [
        'uses' => 'React\SocialNetworkController@index'
    ])->name('social_network')->where('path', '[a-zA-Z0-9-/]+');
    Route::get('/data-auth', 'React\SocialNetworkController@dataAuth');
    Route::get('/data-list-friend-user-know', 'React\SocialNetworkController@dataListFriendUserKnow');
    Route::get('/data-list-friend/{userId}', 'React\SocialNetworkController@dataListFriend');
    Route::get('/data-reply-comment', 'React\SocialNetworkController@dataReplyComment');
    Route::get('/data-news-network', 'React\SocialNetworkController@dataNews');
    Route::post('/upload-video-network', 'React\SocialNetworkController@uploadVideo');
    Route::post('/upload-image-network', 'React\SocialNetworkController@uploadImage');
    Route::post('/add-new-network', 'React\SocialNetworkController@addNew');
    Route::post('/like-new-network', 'React\SocialNetworkController@likeNew');
    Route::get('/show-comment/{id}', 'React\SocialNetworkController@showComment');
    Route::post('/user-comment-network', 'React\SocialNetworkController@userComment');
    Route::post('/user-add-friend', 'React\SocialNetworkController@userAddFriend');
    Route::get('/data-noty', 'React\SocialNetworkController@dataNoty');
    Route::post('/accept-add-friend', 'React\SocialNetworkController@acceptAddFriend');
    Route::post('/like-comment', 'React\SocialNetworkController@likeComment');
    Route::post('/reply-comment', 'React\SocialNetworkController@replyComment');
    Route::post('/like-reply-comment', 'React\SocialNetworkController@likeReplyComment');
    Route::post('/chat', 'React\SocialNetworkController@chat');
    Route::get('/data-chat', 'React\SocialNetworkController@dataChat');
    Route::post('/delete-chat', 'React\SocialNetworkController@deleteChat');
    Route::post('/save-image-cover', 'React\SocialNetworkController@saveImageCover');
    Route::get('/data-image-cover/{userId}', 'React\SocialNetworkController@dataImageCover');
    Route::post('/save-story', 'React\SocialNetworkController@saveStory');
    Route::get('/data-story/{userId}', 'React\SocialNetworkController@dataStory');
    Route::post('/save-work-place', 'React\SocialNetworkController@saveWorkPlace');
    Route::post('/delete-work-place', 'React\SocialNetworkController@deleteWorkPlace');
    Route::get('/data-work-place/{userId}/{type}', 'React\SocialNetworkController@dataWorkPlace');
    Route::post('/save-study', 'React\SocialNetworkController@saveStudy');
    Route::post('/delete-study', 'React\SocialNetworkController@deleteStudy');
    Route::get('/data-study/{userId}/{type_study}/{type}', 'React\SocialNetworkController@dataStudy');
    Route::post('/save-city', 'React\SocialNetworkController@saveCity');
    Route::post('/delete-city', 'React\SocialNetworkController@deleteCity');
    Route::get('/data-city/{userId}/{type}', 'React\SocialNetworkController@dataCity');
    Route::post('/save-country', 'React\SocialNetworkController@saveCountry');
    Route::post('/delete-country', 'React\SocialNetworkController@deleteCountry');
    Route::get('/data-country/{userId}/{type}', 'React\SocialNetworkController@dataCountry');
    Route::get('/data-detail-post-photo/{id}/{idImage}', 'React\SocialNetworkController@dataDetailPostPhoto');
    Route::get('/data-image-post-photo/{id}/{idImage}', 'React\SocialNetworkController@dataImagePostPhoto');
    Route::get('/data-user-image-network/{userId}', 'React\SocialNetworkController@dataUserImage');
});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
Route::get('captcha/{param?}', function ($param = 'default') {
    return Captcha::create($param);
//    $res = app('captcha')->create($param, true);
//    return '<img src="'.$res['img'].'" />';
})->name('mycaptcha');
//Route::get('/captcha/{param?}', function (\Mews\Captcha\Captcha $captcha, $param = 'default') {
//    return $captcha->src($param);
//});
