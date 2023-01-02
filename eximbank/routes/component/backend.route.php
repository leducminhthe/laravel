<?php

Route::group(['prefix' => '/admin-cp'], function() {
    Route::get('/', 'Backend\CategoryController@dashboard')->name('backend.dashboard')
    ->middleware('permission:dashboard');

    Route::post('/get-user-creatd-updated/{created}/{updated}', 'Backend\CategoryController@getUserCreateUpdated')->name('backend.get_user_created_updated')
    ->where('created', '[0-9]+')
    ->where('updated', '[0-9]+');

    Route::post('/get-user-info/{created}/{updated}', 'Backend\CategoryController@getUserInfo')->name('backend.get_user_info')
    ->where('created', '[0-9]+')
    ->where('updated', '[0-9]+');

    Route::get('/cache', 'Backend\SettingController@cache')->name('backend.cache');
    Route::get('/clear_cache', 'Backend\SettingController@clearCache')->name('backend.clear_cache');

    Route::get('/update_source', 'Backend\SettingController@updateSource')->name('backend.update_source');
    Route::post('/save_update_source', 'Backend\SettingController@saveUpdateSource')->name('backend.save_update_source');
});
//Quyền giảng viên
Route::group(['prefix' => '/teacher-cp'], function() {
    Route::get('/teacher-manager', 'Backend\TrainingTeacherController@listPermission')->name('backend.category.training_teacher.list_permission');
    Route::get('/calendar-teacher', 'Backend\TrainingTeacherController@calendarTeacher')->name('backend.category.training_teacher.calendar_teacher');
    Route::match(['get', 'post'], '/attendance-user/qrcode-process', 'Backend\TrainingTeacherController@qrcodeProcess')->name('backend.category.training_teacher.attendance_user.qrcode_process');
    Route::get('/register-teach', 'Backend\TrainingTeacherController@registerTeach')->name('backend.category.training_teacher.register_teach');
    Route::get('/getdata-course-register', 'Backend\TrainingTeacherController@getdataCourseRegister')->name('backend.category.training_teacher.getdata_course_register');
    Route::get('/detail-register-teach/{id}', 'Backend\TrainingTeacherController@detailRegisterTeach')->name('backend.category.training_teacher.detail_register_teach');

    Route::get('/getdata-detail-register-teach/{id}', 'Backend\TrainingTeacherController@getdataDetailRegisterTeach')->name('backend.category.training_teacher.getdata_detail_register_teach');

    Route::post('/save-register-class', 'Backend\TrainingTeacherController@saveRegisterClass')->name('backend.category.training_teacher.save_register_class')->where('id', '[0-9]+');

    Route::get('/history-teacher', 'Backend\TrainingTeacherController@historyTeacher')->name('backend.category.training_teacher.history_teacher');
    Route::get('/list-course-teacher', 'Backend\TrainingTeacherController@listCourseTeacher')->name('backend.category.training_teacher.list_course_teacher');
    Route::get('/list-course-teacher/getdata/{type}', 'Backend\TrainingTeacherController@listCourseTeacherGetData')->name('backend.category.training_teacher.list_course_teacher_getdata');
    Route::group(['prefix' => '/list-course'], function() {
        Route::get('/', 'Backend\TrainingTeacherController@listCourse')->name('backend.category.training_teacher.list_course');
        Route::get('getdata', 'Backend\TrainingTeacherController@listCourseData')->name('backend.category.training_teacher.list_course.getdata');
        Route::get('/attendance-user/{course_id}', 'Backend\TrainingTeacherController@attendanceUser')->name('backend.category.training_teacher.attendance_user')->where('course_id', '[0-9]+');
        Route::get('/attendance-user/{course_id}/getdata', 'Backend\TrainingTeacherController@attendanceUserData')->name('backend.category.training_teacher.attendance_user.getdata')->where('course_id', '[0-9]+');
    });
});
Route::group(['prefix' => '/admin-cp/dashboard_by_user'], function() {
    Route::get('/', 'Backend\DashboardByUserController@index')->name('backend.dashboard_by_user')->middleware('permission:dashboard-by-user');

    Route::get('/getdata', 'Backend\DashboardByUserController@getData')->name('backend.dashboard_by_user.getdata')->middleware('permission:dashboard-by-user');

    Route::post('/edit', 'Backend\DashboardByUserController@form')->name('backend.dashboard_by_user.edit')->middleware('permission:dashboard-by-user-edit');

    Route::post('/save', 'Backend\DashboardByUserController@save')->name('backend.dashboard_by_user.save')->middleware('permission:dashboard-by-user-edit');
});

//ĐÓNG MỞ MENU
Route::post('/close-open-menu-backend', 'Backend\SettingController@closeOpendMenu')->name('backend.close_open_menu');

//zoom screen
Route::post('/zoom-screen-backend', 'Backend\SettingController@zoomScreen')->name('backend.zoom_screen');

Route::group(['prefix' => '/admin-cp/setting'], function() {
    Route::get('/', 'Backend\SettingController@index')->name('backend.setting');
});

Route::group(['prefix' => '/admin-cp/footer'], function() {
    Route::get('/', 'Backend\FooterController@index')->name('backend.footer');

    Route::get('/getdata', 'Backend\FooterController@getData')->name('backend.footer.getdata');

    Route::post('/remove', 'Backend\FooterController@remove')->name('backend.footer.remove');

    Route::get('/create', 'Backend\FooterController@form')->name('backend.footer.create');

    Route::get('/edit/{id}', 'Backend\FooterController@form')->name('backend.footer.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\FooterController@save')->name('backend.footer.save');
});

Route::group(['prefix'=> '/admin-cp'], function () {
    Route::get('/logo-outside', 'Backend\LogoController@logoOutside')->name('backend.logo_outside')->middleware('permission:config-logo');
    Route::post('/logo-outside/save', 'Backend\LogoController@saveLogoOutside')->name('backend.logo_outside.save')->middleware('permission:config-logo');

    Route::get('/favicon', 'Backend\LogoController@favicon')->name('backend.logo.favicon')->middleware('permission:config-favicon');
    Route::post('/save-favicon', 'Backend\LogoController@saveFavicon')->name('backend.logo.save.favicon')->middleware('permission:config-favicon');

    Route::get('/logo', 'Backend\LogoController@index')->name('backend.logo')->middleware('permission:config-logo');

    Route::get('/logo/getdata', 'Backend\LogoController@getData')->name('backend.logo.getdata')->middleware('permission:config-logo');

    Route::post('/logo/remove', 'Backend\LogoController@remove')->name('backend.logo.remove')->middleware('permission:config-logo');

    Route::get('/logo/create', 'Backend\LogoController@form')->name('backend.logo.create')->middleware('permission:config-logo');

    Route::get('/logo/edit/{id}', 'Backend\LogoController@form')->name('backend.logo.edit')->where('id', '[0-9]+')->middleware('permission:config-logo');

    Route::post('/logo/save', 'Backend\LogoController@save')->name('backend.logo.save')->middleware('permission:config-logo');

    Route::post('/logo/ajax_isopen_publish', 'Backend\LogoController@ajaxIsopenPublish')->name('backend.logo.ajax_isopen');
});

Route::group(['prefix'=> '/admin-cp/login-image'], function () {
    Route::get('/', 'Backend\LoginImageController@index')->name('backend.login_image')->middleware('permission:config-login-image');

    Route::get('/getdata', 'Backend\LoginImageController@getData')->name('backend.login_image.getdata')->middleware('permission:config-login-image');

    Route::post('/remove', 'Backend\LoginImageController@remove')->name('backend.login_image.remove')->middleware('permission:config-login-image');

    Route::post('/edit', 'Backend\LoginImageController@form')->name('backend.login_image.edit')->where('id', '[0-9]+')->middleware('permission:config-login-image');

    Route::post('/save', 'Backend\LoginImageController@save')->name('backend.login_image.save')->middleware('permission:config-login-image-save');

    Route::post('/ajax_isopen_publish', 'Backend\LoginImageController@ajaxIsopenPublish')->name('backend.login_image.ajax_isopen_publish');
});

//CHỈNH MÀU NÚT
Route::group(['prefix' => '/admin-cp/setting-color'], function() {
    Route::get('/', 'Backend\SettingColorController@index')->name('backend.setting_color')->middleware('permission:setting-color');

    Route::get('/create', 'Backend\SettingColorController@form')->name('backend.setting_color.create')->middleware('permission:setting-color-create');

    Route::get('/edit/{id}', 'Backend\SettingColorController@form')->name('backend.setting_color.edit')->where('id', '[0-9]+')->middleware('permission:setting-color-create');

    Route::post('/save', 'Backend\SettingColorController@save')->name('backend.setting_color.save')->middleware('permission:setting-color');
});

//CHỈNH THỜI GIAN
Route::group(['prefix' => '/admin-cp/setting-time'], function() {
    Route::get('/', 'Backend\SettingTimeController@index')->name('backend.setting_time')->middleware('permission:setting-time');

    Route::get('/getdata', 'Backend\SettingTimeController@getData')->name('backend.setting_time.getdata')->middleware('permission:setting-time');

    Route::get('/create', 'Backend\SettingTimeController@form')->name('backend.setting_time.create')->middleware('permission:setting-time-create');

    Route::get('/edit/{id}', 'Backend\SettingTimeController@form')->name('backend.setting_time.edit')->where('id', '[0-9]+')->middleware('permission:setting-time-edit');

    Route::post('/save', 'Backend\SettingTimeController@save')->name('backend.setting_time.save')->middleware('permission:setting-time-create');

    Route::post('/remove', 'Backend\SettingTimeController@remove')->name('backend.setting_time.remove')->middleware('permission:setting-time-delete');
});

//CHỈNH ĐIỀU HƯỚNG TRẢI NGHIỆM
Route::group(['prefix' => '/admin-cp/setting-experience-navigate'], function() {
    Route::get('/', 'Backend\SettingExperienceNavigateController@index')->name('backend.experience_navigate')->middleware('permission:setting-experience-navigate');

    Route::get('/getdata', 'Backend\SettingExperienceNavigateController@getData')->name('backend.experience_navigate.getdata')->middleware('permission:setting-experience-navigate');

    Route::get('/create', 'Backend\SettingExperienceNavigateController@form')->name('backend.experience_navigate.create')->middleware('permission:setting-experience-navigate-create');

    Route::get('/edit/{id}', 'Backend\SettingExperienceNavigateController@form')->name('backend.experience_navigate.edit')->where('id', '[0-9]+')->middleware('permission:setting-experience-navigate-edit');

    Route::post('/save', 'Backend\SettingExperienceNavigateController@save')->name('backend.experience_navigate.save')->middleware('permission:setting-experience-navigate-create');

    Route::post('/remove-experience', 'Backend\SettingExperienceNavigateController@remove')->name('backend.experience_navigate.remove')->middleware('permission:setting-experience-navigate-delete');

    Route::post('/save-object/{id}', 'Backend\SettingExperienceNavigateController@saveObject')->name('backend.experience_navigate.object')->middleware('permission:setting-experience-navigate-object-create');

    Route::get('/get-object/{id}', 'Backend\SettingExperienceNavigateController@getObject')->name('backend.experience_navigate.get_object')->middleware('permission:setting-experience-navigate');

    Route::post('/remove-object/{id}', 'Backend\SettingExperienceNavigateController@removeObject')->name('backend.experience_navigate.remove_object')->middleware('permission:setting-experience-navigate-object-delete');

    // TÊN ĐIỀU HƯỚNG TRẢI NGHIỆM
    Route::get('/name', 'Backend\SettingExperienceNavigateController@name')->name('backend.experience_navigate_name')->middleware('permission:setting-experience-navigate');

    Route::get('/getdata-name', 'Backend\SettingExperienceNavigateController@getDataName')->name('backend.experience_navigate.getdata_name')->middleware('permission:setting-experience-navigate');

    Route::post('/edit-name', 'Backend\SettingExperienceNavigateController@formName')->name('backend.experience_navigate.edit_name')->middleware('permission:setting-experience-navigate-name-eidt');

    Route::post('/save-name', 'Backend\SettingExperienceNavigateController@saveName')->name('backend.experience_navigate.save_name')->middleware('permission:setting-experience-navigate-name-eidt');

    Route::post('/ajax-isopen-publish', 'Backend\SettingExperienceNavigateController@ajaxIsopenPublish')
        ->name('backend.experience_navigate.ajax_isopen_publish')
        ->middleware('permission:setting-experience-navigate-name-eidt');
});

Route::group(['prefix' => '/admin-cp/webservice'], function() {
    Route::get('/', 'Backend\WebServiceController@index')->name('backend.webservice');

    Route::get('/getdata', 'Backend\WebServiceController@getData')->name('backend.webservice.getdata');

    Route::post('/remove', 'Backend\WebServiceController@remove')->name('backend.webservice.remove');

    Route::get('/create', 'Backend\WebServiceController@form')->name('backend.webservice.create');

    Route::get('/edit/{id}', 'Backend\WebServiceController@form')->name('backend.webservice.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\WebServiceController@save')->name('backend.webservice.save');
});

Route::group(['prefix' => '/admin-cp/slider'], function() {
    Route::get('/', 'Backend\SliderController@index')->name('backend.slider')
        ->middleware('permission:banner');

    Route::get('/getdata', 'Backend\SliderController@getData')->name('backend.slider.getdata')
        ->middleware('permission:banner');

    Route::post('/remove', 'Backend\SliderController@remove')->name('backend.slider.remove')
        ->middleware('permission:banner-delete');

    Route::get('/create', 'Backend\SliderController@form')->name('backend.slider.create')
        ->middleware('permission:banner-create');

    Route::get('/edit/{id}', 'Backend\SliderController@form')->name('backend.slider.edit')
        ->where('id', '[0-9]+')
        ->middleware('permission:banner-edit');

    Route::post('/save', 'Backend\SliderController@save')->name('backend.slider.save')
        ->middleware('permission:banner-create');

    Route::post('/ajax_isopen_publish', 'Backend\SliderController@ajaxIsopenPublish')
        ->name('backend.slider.ajax_isopen_publish')
        ->middleware('permission:banner');
});

Route::group(['prefix' => '/admin-cp/slider-outside'], function() {
    Route::get('/', 'Backend\SliderOutsideController@index')->name('backend.slider_outside')
        ->middleware('permission:banner');

    Route::get('/getdata', 'Backend\SliderOutsideController@getData')->name('backend.slider_outside.getdata')
        ->middleware('permission:banner');

    Route::post('/remove', 'Backend\SliderOutsideController@remove')->name('backend.slider_outside.remove')
        ->middleware('permission:banner-delete');

    Route::get('/create', 'Backend\SliderOutsideController@form')->name('backend.slider_outside.create')
        ->middleware('permission:banner-create');

    Route::get('/edit/{id}', 'Backend\SliderOutsideController@form')->name('backend.slider_outside.edit')
        ->where('id', '[0-9]+')
        ->middleware('permission:banner-edit');

    Route::post('/save', 'Backend\SliderOutsideController@save')->name('backend.slider_outside.save')
        ->middleware('permission:banner-create');

    Route::post('/ajax_isopen_publish', 'Backend\SliderOutsideController@ajaxIsopenPublish')
        ->name('backend.slider_outside.ajax_isopen_publish')
        ->middleware('permission:banner');
});

//BANNER LOGIN MOBILE
Route::group(['prefix' => '/admin-cp/banner-login-mobile'], function() {
    Route::get('/', 'Backend\BannerLoginMobileController@index')->name('backend.banner_login_mobile');

    Route::get('/getdata', 'Backend\BannerLoginMobileController@getData')->name('backend.banner_login_mobile.getdata');

    Route::post('/remove', 'Backend\BannerLoginMobileController@remove')->name('backend.banner_login_mobile.remove');

    Route::get('/create', 'Backend\BannerLoginMobileController@form')->name('backend.banner_login_mobile.create');

    Route::get('/edit/{id}', 'Backend\BannerLoginMobileController@form')->name('backend.banner_login_mobile.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\BannerLoginMobileController@save')->name('backend.banner_login_mobile.save');

    Route::post('/ajax_isopen_publish', 'Backend\BannerLoginMobileController@ajaxIsopenPublish')->name('backend.banner_login_mobile.ajax_isopen_publish');
});

// THÔNG TIN CÔNG TY
Route::group(['prefix' => '/admin-cp/infomation-company'], function() {
    Route::get('/', 'Backend\InfomationCompanyController@index')->name('backend.infomation_company')
        ->middleware('permission:infomation-company');

    Route::get('/create', 'Backend\InfomationCompanyController@form')->name('backend.infomation_company.create')
        ->middleware('permission:infomation-company-create');

    Route::get('/edit/{id}', 'Backend\InfomationCompanyController@form')->name('backend.infomation_company.edit')
        ->where('id', '[0-9]+')
        ->middleware('permission:infomation-company-create');

    Route::post('/save', 'Backend\InfomationCompanyController@save')->name('backend.infomation_company.save')
        ->middleware('permission:infomation-company');
});

Route::group(['prefix' => '/admin-cp/advertising-photo'], function() {
    Route::get('/{type}', 'Backend\AdvertisingPhotoController@index')->name('backend.advertising_photo')
        ->middleware('permission:advertising-photo');

    Route::get('/getdata/{type}', 'Backend\AdvertisingPhotoController@getData')->name('backend.advertising_photo.getdata')
        ->middleware('permission:advertising-photo');

    Route::post('/remove', 'Backend\AdvertisingPhotoController@remove')->name('backend.advertising_photo.remove')
        ->middleware('permission:advertising-photo-delete');

    Route::post('/edit/{type}', 'Backend\AdvertisingPhotoController@form')->name('backend.advertising_photo.edit')
        ->where('id', '[0-9]+')
        ->middleware('permission:advertising-photo-edit');

    Route::post('/save', 'Backend\AdvertisingPhotoController@save')->name('backend.advertising_photo.save')
        ->middleware('permission:advertising-photo-create');

    Route::post('/ajax_isopen_publish', 'Backend\AdvertisingPhotoController@ajaxIsopenPublish')
        ->name('backend.advertising_photo.ajax_isopen_publish')
        ->middleware('permission:advertising-photo');
});

Route::group(['prefix' => '/admin-cp/guide'], function() {
    Route::get('/', 'Backend\GuideController@index')->name('backend.guide')
    ->middleware('permission:guide');

    Route::get('/getdata', 'Backend\GuideController@getData')->name('backend.guide.getdata')
    ->middleware('permission:guide');

    Route::post('/remove', 'Backend\GuideController@remove')->name('backend.guide.remove')
    ->middleware('permission:guide-delete');

    Route::get('/create', 'Backend\GuideController@form')->name('backend.guide.create')
    ->middleware('permission:guide-create');

    Route::get('/edit/{id}', 'Backend\GuideController@form')->name('backend.guide.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:guide-edit');

    Route::post('/save', 'Backend\GuideController@save')->name('backend.guide.save')
    ->middleware('permission:guide-create');
});

// Liên hệ
Route::group(['prefix' => '/admin-cp/contact'], function() {
    Route::get('/', 'Backend\ContactController@index')->name('backend.contact')
    ->middleware('permission:contact');

    Route::get('/getdata', 'Backend\ContactController@getData')->name('backend.contact.getdata')
    ->middleware('permission:contact');

    Route::post('/remove', 'Backend\ContactController@remove')->name('backend.contact.remove')
    ->middleware('permission:contact-delete');

    Route::get('/create', 'Backend\ContactController@form')->name('backend.contact.create')
    ->middleware('permission:contact-create');

    Route::get('/edit/{id}', 'Backend\ContactController@form')->name('backend.contact.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:contact-edit');

    Route::post('/save', 'Backend\ContactController@save')->name('backend.contact.save')
    ->middleware('permission:contact-create');
});

// Địa điểm đào tạo
Route::group(['prefix' => '/admin-cp/google-map'], function() {
    Route::get('/','Backend\GoogleMapController@index')->name('backend.google.map')
    ->middleware('permission:google-map');

    Route::post('/post','Backend\GoogleMapController@store')->name('backend.google.map.store')
    ->middleware('permission:google-map');

    Route::get('/list-local', 'Backend\GoogleMapController@listLocal')->name('backend.google.map.list')
    ->middleware('permission:google-map');

    Route::get('/getdata', 'Backend\GoogleMapController@getData')->name('backend.google.map.getdata')
    ->middleware('permission:google-map');

    Route::post('/remove', 'Backend\GoogleMapController@remove')->name('backend.google.map.remove')
    ->middleware('permission:google-map-delete');

    Route::get('/create', 'Backend\GoogleMapController@form')->name('backend.google.map.create')
    ->middleware('permission:google-map-create');

    Route::get('/edit/{id}', 'Backend\GoogleMapController@form')->name('backend.google.map.edit')
    ->where('id', '[0-9]+')->middleware('permission:google-map-edit');

    Route::post('/save', 'Backend\GoogleMapController@save')->name('backend.google.map.save')
    ->middleware('permission:google-map-create');
});

/* MẪU KPI */
Route::group(['prefix' => '/admin-cp/kpi-template', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\KpiTemplateController@index')->name('backend.category.kpi_tempalte');
    Route::get('/getdata', 'Backend\KpiTemplateController@getData')->name('backend.category.kpi_tempalte.getdata');
    Route::post('/edit', 'Backend\KpiTemplateController@form')->name('backend.category.kpi_tempalte.edit')->where('id', '[0-9]+');
    Route::post('/save', 'Backend\KpiTemplateController@save')->name('backend.category.kpi_tempalte.save');
    Route::post('/remove', 'Backend\KpiTemplateController@remove')->name('backend.category.kpi_tempalte.remove');
    Route::post('/ajax_isopen_publish', 'Backend\KpiTemplateController@ajaxIsopenPublish')->name('backend.category.kpi_tempalte.ajax_isopen_publish');
    Route::get('/show-kpi', 'Backend\KpiTemplateController@showKpi')->name('module.category.kpi_tempalte.show_kpi');
});

Route::group(['prefix' => '/admin-cp/category'], function() {
    Route::get('/', 'Backend\CategoryController@index')->name('backend.category');
    /* province */
    Route::get('/province', 'Backend\ProvinceController@index')->name('backend.category.province')->middleware('permission:category-province');
    Route::get('/province/getdata', 'Backend\ProvinceController@getData')->name('backend.category.province.getdata')->middleware('permission:category-province');
    Route::post('/province/remove','Backend\ProvinceController@remove')->name('backend.category.province.remove')->middleware('permission:category-province-delete');
    Route::post('/province/edit','Backend\ProvinceController@form')->name('backend.category.province.edit')->where(['id'=>'[0-9]+'])->middleware('permission:category-province-edit');
    Route::post('/province/save','Backend\ProvinceController@save')->name('backend.category.province.save')->middleware('permission:category-province-create|category-province-edit');
    Route::post('/province/import', 'Backend\ProvinceController@import')->name('backend.category.province.import')->middleware('permission:category-province-import');

    /*district*/
    Route::get('/district', 'Backend\DistrictController@index')->name('backend.category.district')->middleware('permission:category-district');
    Route::get('/district/getdata', 'Backend\DistrictController@getData')->name('backend.category.district.getdata')->middleware('permission:category-district');
    Route::post('/district/remove','Backend\DistrictController@remove')->name('backend.category.district.remove')->middleware('permission:category-district-delete');
    Route::post('/district/edit','Backend\DistrictController@form')->name('backend.category.district.edit')->where(['id'=>'[0-9]+'])->middleware('permission:category-district-edit');
    Route::post('/district/save','Backend\DistrictController@save')->name('backend.category.district.save')->middleware('permission:category-district-create');
    Route::get('/district/filter','Backend\DistrictController@filter')->name('backend.category.district.filter')->middleware('permission:category-district');

    /* unit name */
    Route::get('/unit-name', 'Backend\UnitNameController@index')->name('backend.category.unit_name')->middleware('permission:category-unit');
    Route::post('/unit-name/save', 'Backend\UnitNameController@save')->name('backend.category.unit_name.save')->middleware('permission:category-unit-create|category-unit-edit');
    Route::post('/unit-name/edit', 'Backend\UnitNameController@form')->name('backend.category.unit_name.edit')->where('id', '[0-9]+')->middleware('permission:category-unit-edit');
    Route::post('/unit-name/remove', 'Backend\UnitNameController@remove')->name('backend.category.unit_name.remove')->middleware('permission:category-unit-delete');
    /* Unit */
    Route::get('/unit/{level}', 'Backend\UnitController@index')->name('backend.category.unit')->where('level', '[0-9]+')->middleware('permission:category-unit');
    Route::get('/unit/{level}/getdata', 'Backend\UnitController@getData')->name('backend.category.unit.getdata')->middleware('permission:category-unit');
    Route::post('/unit/{level}/edit', 'Backend\UnitController@form')->name('backend.category.unit.edit')->where('level', '[0-9]+')->where('id', '[0-9]+')->middleware('permission:category-unit-edit');
    Route::post('/unit/{level}/save', 'Backend\UnitController@save')->name('backend.category.unit.save')->where('level', '[0-9]+')->middleware('permission:category-unit-create|category-unit-edit');
    Route::post('/unit/{level}/remove', 'Backend\UnitController@remove')->name('backend.category.unit.remove')->where('level', '[0-9]+')->middleware('permission:category-unit-delete');
    Route::post('/unit/import', 'Backend\UnitController@import')->name('backend.category.unit.import')->middleware('permission:category-unit-import');
    Route::get('/unit/tree', 'Backend\UnitController@treeFolder')->name('backend.category.unit.tree_folder')->middleware('permission:category-unit');
    Route::post('/unit/tree/getChild', 'Backend\UnitController@getChild')->name('backend.category.unit.tree_folder.get_child')->middleware('permission:category-unit');
    Route::get('/unit/export/{level}', 'Backend\UnitController@export')->name('backend.category.unit.export')->middleware('permission:category-unit-export');
    Route::post('/unit/import_update', 'Backend\UnitController@importUpdate')->name('backend.category.unit.import_update')->middleware('permission:category-unit-import');
    Route::post('/unit/ajax_isopen_publish', 'Backend\UnitController@ajaxIsopenPublish')->name('backend.category.unit.ajax_isopen_publish');

    /* Area name */
    Route::get('/area-name', 'Backend\AreaNameController@index')->name('backend.category.area_name')->middleware('permission:category-area');
    Route::post('/area-name/save', 'Backend\AreaNameController@save')->name('backend.category.area_name.save')->middleware('permission:category-area-create|category-area-edit');
    Route::post('/area-name/edit', 'Backend\AreaNameController@form')->name('backend.category.area_name.edit')->where('id', '[0-9]+')->middleware('permission:category-area-edit');
    Route::post('/area-name/remove', 'Backend\AreaNameController@remove')->name('backend.category.area_name.remove')->middleware('permission:category-area-delete');

    /* Area */
    Route::get('/area/{level}', 'Backend\AreaController@index')->name('backend.category.area')->where('level', '[0-9]+')
    ->middleware('permission:category-area');
    Route::get('/area/{level}/getdata', 'Backend\AreaController@getData')->name('backend.category.area.getdata')
    ->middleware('permission:category-area');
    Route::post('/area/{level}/edit', 'Backend\AreaController@form')->name('backend.category.area.edit')
    ->where('level', '[0-9]+')
    ->where('id', '[0-9]+')
    ->middleware('permission:category-area-edit');
    Route::post('/area/{level}/save', 'Backend\AreaController@save')->name('backend.category.area.save')
    ->where('level', '[0-9]+')
    ->middleware('permission:category-area-create');
    Route::post('/area/{level}/remove', 'Backend\AreaController@remove')->name('backend.category.area.remove')
    ->where('level', '[0-9]+')
    ->middleware('permission:category-area-delete');
    Route::post('/area/import', 'Backend\AreaController@import')->name('backend.category.area.import')
    ->middleware('permission:category-area');
    Route::post('/area/ajax_isopen_publish', 'Backend\AreaController@ajaxIsopenPublish')->name('backend.category.area.ajax_isopen_publish')
    ->middleware('permission:category-area');

    /* Titles */
    Route::get('/titles', 'Backend\TitlesController@index')->name('backend.category.titles')->middleware('permission:category-titles');
    Route::get('/titles/getdata', 'Backend\TitlesController@getData')->name('backend.category.titles.getdata')->middleware('permission:category-titles');
    Route::post('/titles/edit', 'Backend\TitlesController@form')->name('backend.category.titles.edit')->where('id', '[0-9]+')->middleware('permission:category-titles-edit');
    Route::post('/titles/save', 'Backend\TitlesController@save')->name('backend.category.titles.save')->middleware('permission:category-titles-create|category-titles-edit');
    Route::post('/titles/remove', 'Backend\TitlesController@remove')->name('backend.category.titles.remove')->middleware('permission:category-titles-delete');
    Route::post('/titles/import', 'Backend\TitlesController@import')->name('backend.category.titles.import')->middleware('permission:category-titles-import');
    Route::get('/titles/export', 'Backend\TitlesController@export')->name('backend.category.titles.export')->middleware('permission:category-titles-export');
    Route::get('/titles/export-simple', 'Backend\TitlesController@export_simple')->name('backend.category.titles.export_simple')->middleware('permission:category-titles-export');
    Route::post('/title/ajax_isopen_publish', 'Backend\TitlesController@ajaxIsopenPublish')->name('backend.category.title.ajax_isopen_publish');
    Route::post('/title/kpi/{id}', 'Backend\TitlesController@getKpi')->name('backend.category.title.kpi');

    // CẤP BẬC CHỨC DANH
    Route::get('/title_rank', 'Backend\TitleRankController@index')->name('backend.category.title_rank')
    ->middleware('permission:category-title-rank');
    Route::get('/title_rank/getdata', 'Backend\TitleRankController@getData')->name('backend.category.title_rank.getdata')
    ->middleware('permission:category-title-rank');
    Route::post('/title_rank/edit', 'Backend\TitleRankController@form')->name('backend.category.title_rank.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:category-title-rank-edit');
    Route::post('/title_rank/save', 'Backend\TitleRankController@save')->name('backend.category.title_rank.save')
    ->middleware('permission:category-title-rank-create');
    Route::post('/title_rank/remove', 'Backend\TitleRankController@remove')->name('backend.category.title_rank.remove')
    ->middleware('permission:category-title-rank-delete');
    Route::post('/title_rank/ajax_isopen_publish', 'Backend\TitleRankController@ajaxIsopenPublish')->name('backend.category.title_rank.ajax_isopen_publish')
    ->middleware('permission:category-title-rank');

	/* Position */
    Route::get('/position', 'Backend\PositionController@index')->name('backend.category.position')->middleware('permission:category-position');
    Route::get('/position/getdata', 'Backend\PositionController@getData')->name('backend.category.position.getdata')->middleware('permission:category-position');
    Route::post('/position/edit', 'Backend\PositionController@form')->name('backend.category.position.edit')->where('id', '[0-9]+')->middleware('permission:category-position-edit');
    Route::post('/position/save', 'Backend\PositionController@save')->name('backend.category.position.save')->middleware('permission:category-position-create|category-position-edit');
    Route::post('/position/remove', 'Backend\PositionController@remove')->name('backend.category.position.remove')->middleware('permission:category-position-delete');
    Route::post('/position/ajax_isopen_publish', 'Backend\PositionController@ajaxIsopenPublish')->name('backend.category.position.ajax_isopen_publish');

	 /* Training Type */
    Route::get('/training-type', 'Backend\TrainingTypeController@index')->name('backend.category.training-type')->middleware('permission:category-training-type');
    Route::get('/training-type/getdata', 'Backend\TrainingTypeController@getData')->name('backend.category.training-type.getdata')->middleware('permission:category-training-type');
    Route::post('/training-type/edit', 'Backend\TrainingTypeController@form')->name('backend.category.training-type.edit')->where('id', '[0-9]+')->middleware('permission:category-training-type-edit');
    Route::post('/training-type/save', 'Backend\TrainingTypeController@save')->name('backend.category.training-type.save')->middleware('permission:category-training-type-create|category-training-type-edit');
    Route::post('/training-type/remove', 'Backend\TrainingTypeController@remove')->name('backend.category.training-type.remove')->middleware('permission:category-training-type-delete');

	 /* Absent */
    Route::get('/absent', 'Backend\AbsentController@index')->name('backend.category.absent')->middleware('permission:category-absent');
    Route::get('/absent/getdata', 'Backend\AbsentController@getData')->name('backend.category.absent.getdata')->middleware('permission:category-absent');
    Route::post('/absent/edit', 'Backend\AbsentController@form')->name('backend.category.absent.edit')->where('id', '[0-9]+')->middleware('permission:category-absent-edit');
    Route::post('/absent/save', 'Backend\AbsentController@save')->name('backend.category.absent.save')->middleware('permission:category-absent-create|category-absent-edit');
    Route::post('/absent/remove', 'Backend\AbsentController@remove')->name('backend.category.absent.remove')->middleware('permission:category-absent-delete');
    Route::post('/absent/ajax-isopen-publish', 'Backend\AbsentController@ajaxIsopenPublish')->name('backend.category.absent.ajax_isopen_publish');

	 /* Discipline */
    Route::get('/discipline', 'Backend\DisciplineController@index')->name('backend.category.discipline')->middleware('permission:category-discipline');
    Route::get('/discipline/getdata', 'Backend\DisciplineController@getData')->name('backend.category.discipline.getdata')->middleware('permission:category-discipline');
    Route::post('/discipline/edit', 'Backend\DisciplineController@form')->name('backend.category.discipline.edit')->where('id', '[0-9]+')->middleware('permission:category-discipline-edit');
    Route::post('/discipline/save', 'Backend\DisciplineController@save')->name('backend.category.discipline.save')->middleware('permission:category-discipline-create|category-discipline-edit');
    Route::post('/discipline/remove', 'Backend\DisciplineController@remove')->name('backend.category.discipline.remove')->middleware('permission:category-discipline-delete');
    Route::post('/discipline/ajax-isopen-publish', 'Backend\DisciplineController@ajaxIsopenPublish')->name('backend.category.discipline.ajax_isopen_publish');

	 /* Training Object */
    Route::get('/training-object', 'Backend\TrainingObjectController@index')->name('backend.category.training-object')->middleware('permission:category-training-object');
    Route::get('/training-object/getdata', 'Backend\TrainingObjectController@getData')->name('backend.category.training-object.getdata')->middleware('permission:category-training-object');
    Route::post('/training-object/edit', 'Backend\TrainingObjectController@form')->name('backend.category.training-object.edit')->where('id', '[0-9]+')->middleware('permission:category-training-object-edit');
    Route::post('/training-object/save', 'Backend\TrainingObjectController@save')->name('backend.category.training-object.save')->middleware('permission:category-training-object-create|category-training-object-edit');
    Route::post('/training-object/remove', 'Backend\TrainingObjectController@remove')->name('backend.category.training-object.remove')->middleware('permission:category-training-object-delete');
    Route::post('/training-object/ajax_isopen_publish', 'Backend\TrainingObjectController@ajaxIsopenPublish')->name('backend.category.training-object.ajax_isopen_publish');

	 /* Reason Absent */
     Route::get('/absent-reason', 'Backend\AbsentReasonController@index')->name('backend.category.absent-reason')->middleware('permission:category-absent-reason');
    Route::get('/absent-reason/getdata', 'Backend\AbsentReasonController@getData')->name('backend.category.absent-reason.getdata')->middleware('permission:category-absent-reason');
    Route::post('/absent-reason/edit', 'Backend\AbsentReasonController@form')->name('backend.category.absent-reason.edit')->where('id', '[0-9]+')->middleware('permission:category-absent-reason-edit');
    Route::post('/absent-reason/save', 'Backend\AbsentReasonController@save')->name('backend.category.absent-reason.save')->middleware('permission:category-absent-reason-create|category-absent-reason-edit');
    Route::post('/absent-reason/remove', 'Backend\AbsentReasonController@remove')->name('backend.category.absent-reason.remove')->middleware('permission:category-absent-reason-delete');
    Route::post('/discipline/ajax_isopen_publish', 'Backend\AbsentReasonController@ajaxIsopenPublish')->name('backend.category.absent-reason.ajax_isopen_publish');

    /* Chương trình đào tạo */
//    Route::group(['prefix' => '/subject-type', 'middleware' => 'auth'], function() {
//        Route::get('/', 'Backend\SubjectTypeController@index')->name('backend.category.subject_type')->middleware('permission:category-subject-type');
//        Route::get('/getdata', 'Backend\SubjectTypeController@getData')->name('backend.category.subject_type.getdata')->middleware('permission:category-subject-type');
//        Route::get('/edit', 'Backend\SubjectTypeController@form')->name('backend.category.subject_type.edit')->where('id', '[0-9]+')->middleware('permission:category-subject-type-edit');
//        Route::get('/create', 'Backend\SubjectTypeController@form')->name('backend.category.subject_type.create')->middleware('permission:category-subject-type-create');
//        Route::post('/save', 'Backend\SubjectTypeController@save')->name('backend.category.subject_type.save')->middleware('permission:category-subject-type-edit|category-subject-type-create');
//        Route::post('/remove', 'Backend\SubjectTypeController@remove')->name('backend.category.subject_type.remove')->middleware('permission:category-subject-type-delete');
//        Route::get('/export', 'Backend\SubjectTypeController@export')->name('backend.category.subject_type.export')->middleware('permission:category-subject-type-export');
//        Route::post('/import', 'Backend\SubjectTypeController@import')->name('backend.category.subject_type.import')->middleware('permission:category-subject-type-export');
//        Route::post('/ajax_isopen_publish', 'Backend\SubjectTypeController@ajaxIsopenPublish')->name('backend.category.subject_type.ajax_isopen_publish');
//
//        Route::post('/edit/{id}/save-object', 'Backend\SubjectTypeController@saveObject')->name('module.subject-type.save_object')->where('id', '[0-9]+');
//        Route::get('/edit/{id}/get-object', 'Backend\SubjectTypeController@getObject')->name('module.subject-type.get_object')->where('id', '[0-9]+');
//        Route::get('/edit/{id}/get-user-object', 'Backend\SubjectTypeController@getUserObject')->name('module.subject-type.get_user_object')->where('id', '[0-9]+');
//        Route::post('/edit/{id}/remove-object', 'Backend\SubjectTypeController@removeObject')->name('module.subject-type.remove_object')->where('id', '[0-9]+');
//        Route::post('/edit/{id}/import-object', 'Backend\SubjectTypeController@importObject')->name('module.subject-type.import_object')->where('id', '[0-9]+');
//        Route::post('/edit/{id}/check-unit-child', 'Backend\SubjectTypeController@getChild')->name('module.subject-type.get_child')->where('id', '[0-9]+');
//
//        Route::get('/edit/{id}/get-tree-child', 'Backend\SubjectTypeController@getTreeChild')
//            ->name('module.subject-type.get_tree_child')
//            ->where('id', '[0-9]+');
//    });

    /* Chủ đề */
    Route::get('/training-program', 'Backend\TrainingProgramController@index')->name('backend.category.training_program')->middleware('permission:category-training-program');
    Route::get('/training-program/getdata', 'Backend\TrainingProgramController@getData')->name('backend.category.training_program.getdata')->middleware('permission:category-training-program');
    Route::post('/training-program/edit', 'Backend\TrainingProgramController@form')->name('backend.category.training_program.edit')->where('id', '[0-9]+')->middleware('permission:category-training-program-edit');
    Route::post('/training-program/save', 'Backend\TrainingProgramController@save')->name('backend.category.training_program.save')->middleware('permission:category-training-program-edit|category-training-program-create');
    Route::post('/training-program/remove', 'Backend\TrainingProgramController@remove')->name('backend.category.training_program.remove')->middleware('permission:category-training-program-delete');
    Route::get('/training-program/export', 'Backend\TrainingProgramController@export')->name('backend.category.training_program.export')->middleware('permission:category-training-program-export');
    Route::post('/training-program/import', 'Backend\TrainingProgramController@import')->name('backend.category.training_program.import')->middleware('permission:category-training-program-export');
    Route::post('/training-program/ajax_isopen_publish', 'Backend\TrainingProgramController@ajaxIsopenPublish')->name('backend.category.training_program.ajax_isopen_publish');
    Route::post('/training-program/save-order', 'Backend\TrainingProgramController@saveOrder')->name('backend.category.training_program.save_order');

    /* Mảng nghiệp vụ/ Cấp độ */
    Route::get('/level-subject', 'Backend\LevelSubjectController@index')->name('backend.category.level_subject')->middleware('permission:category-level-subject');
    Route::get('/level-subject/getdata', 'Backend\LevelSubjectController@getData')->name('backend.category.level_subject.getdata')->middleware('permission:category-level-subject');
    Route::post('/level-subject/edit', 'Backend\LevelSubjectController@form')->name('backend.category.level_subject.edit')->where('id', '[0-9]+')->middleware('permission:category-level-subject-edit');
    Route::post('/level-subject/save', 'Backend\LevelSubjectController@save')->name('backend.category.level_subject.save')->middleware('permission:category-level-subject-create|category-level-subject-edit');
    Route::post('/level-subject/remove', 'Backend\LevelSubjectController@remove')->name('backend.category.level_subject.remove')->middleware('permission:category-level-subject-delete');
    Route::get('/level-subject/export', 'Backend\LevelSubjectController@export')->name('backend.category.level_subject.export')->middleware('permission:category-level-subject-export');
    Route::post('/level-subject/import', 'Backend\LevelSubjectController@import')->name('backend.category.level_subject.import')->middleware('permission:category-level-subject-export');
    Route::post('/level-subject/ajax_isopen_publish', 'Backend\LevelSubjectController@ajaxIsopenPublish')->name('backend.category.level_subject.ajax_isopen_publish');

    /* subject */
    Route::get('/subject', 'Backend\SubjectController@index')->name('backend.category.subject')->middleware('permission:category-subject');
    Route::get('/subject/getdata', 'Backend\SubjectController@getData')->name('backend.category.subject.getdata')->middleware('permission:category-subject');
    Route::post('/subject/edit', 'Backend\SubjectController@form')->name('backend.category.subject.edit')->where('id', '[0-9]+')->middleware('permission:category-subject');
    Route::post('/subject/save', 'Backend\SubjectController@save')->name('backend.category.subject.save')->middleware('permission:category-subject-create|category-subject-edit');
    Route::post('/subject/remove', 'Backend\SubjectController@remove')->name('backend.category.subject.remove')->middleware('permission:category-subject-delete');
    Route::post('/subject/import', 'Backend\SubjectController@import')->name('backend.category.subject.import')->middleware('permission:category-subject-import');
    Route::get('/subject/export', 'Backend\SubjectController@export')->name('backend.category.subject.export')->middleware('permission:category-subject-export');
    Route::post('/subject/ajax_isopen_publish', 'Backend\SubjectController@ajaxIsopenPublish')->name('backend.category.subject.ajax_isopen_publish');
    Route::post('/subject/save-related-subject', 'Backend\SubjectController@saveRelatedSubject')->name('backend.category.subject.save_related_subject');
    Route::post('/subject/edit-related', 'Backend\SubjectController@formRelated')->name('backend.category.subject.edit_related')->where('id', '[0-9]+')->middleware('permission:category-subject');

    /* training location */
    Route::get('/training-location', 'Backend\TrainingLocationController@index')->name('backend.category.training_location')->middleware('permission:category-training-location');
    Route::get('/training-location/getdata', 'Backend\TrainingLocationController@getData')->name('backend.category.training_location.getdata')->middleware('permission:category-training-location');
    Route::post('/training-location/edit', 'Backend\TrainingLocationController@form')->name('backend.category.training_location.edit')->where('id', '[0-9]+')->middleware('permission:category-training-location-edit');
    Route::post('/training-location/save', 'Backend\TrainingLocationController@save')->name('backend.category.training_location.save')->middleware('permission:category-training-location-create|category-training-location-edit');
    Route::post('/training-location/remove', 'Backend\TrainingLocationController@remove')->name('backend.category.training_location.remove')->middleware('permission:category-training-location-delete');
    Route::post('/training-location/ajax_isopen_publish', 'Backend\TrainingLocationController@ajaxIsopenPublish')->name('backend.category.training_location.ajax_isopen_publish');

    /* course categories */
    Route::get('/course-categories', 'Backend\CourseCategoriesController@index')->name('backend.category.course_categories');
    Route::get('/course-categories/getdata', 'Backend\CourseCategoriesController@getData')->name('backend.category.course_categories.getdata');
    Route::get('/course-categories/edit/{id}', 'Backend\CourseCategoriesController@form')->name('backend.category.course_categories.edit')->where('id', '[0-9]+');
    Route::get('/course-categories/create', 'Backend\CourseCategoriesController@form')->name('backend.category.course_categories.create');
    Route::post('/course-categories/save', 'Backend\CourseCategoriesController@save')->name('backend.category.course_categories.save');
    Route::post('/course-categories/remove', 'Backend\CourseCategoriesController@remove')->name('backend.category.course_categories.remove');

    /* LOẠI CHI PHÍ ĐÀO TẠO */
    Route::get('/type-cost', 'Backend\TypeCostController@index')->name('backend.category.type_cost')
    ->middleware('permission:category-type-cost');

    Route::get('/type-cost/getdata', 'Backend\TypeCostController@getData')->name('backend.category.type_cost.getdata')
    ->middleware('permission:category-type-cost');

    Route::post('/type-cost/edit', 'Backend\TypeCostController@form')->name('backend.category.type_cost.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:category-type-cost-edit');

    Route::post('/type-cost/save', 'Backend\TypeCostController@save')->name('backend.category.type_cost.save')
    ->middleware('permission:category-type-cost-create');

    Route::post('/type-cost/remove', 'Backend\TypeCostController@remove')->name('backend.category.type_cost.remove')
    ->middleware('permission:category-type-cost-delete');

    /* training cost */
    Route::get('/training-cost', 'Backend\TrainingCostController@index')->name('backend.category.training_cost')->middleware('permission:category-training-cost');
    Route::get('/training-cost/getdata', 'Backend\TrainingCostController@getData')->name('backend.category.training_cost.getdata')->middleware('permission:category-training-cost');
    Route::post('/training-cost/edit', 'Backend\TrainingCostController@form')->name('backend.category.training_cost.edit')->where('id', '[0-9]+')->middleware('permission:category-training-cost-edit');
    Route::post('/training-cost/save', 'Backend\TrainingCostController@save')->name('backend.category.training_cost.save')->middleware('permission:category-training-cost-create|category-training-cost-edit');
    Route::post('/training-cost/remove', 'Backend\TrainingCostController@remove')->name('backend.category.training_cost.remove')->middleware('permission:category-training-cost-delete');

    /* student cost */
    Route::get('/student-cost', 'Backend\StudentCostController@index')->name('backend.category.student_cost')->middleware('permission:category-student-cost');
    Route::get('/student-cost/getdata', 'Backend\StudentCostController@getData')->name('backend.category.student_cost.getdata')->middleware('permission:category-student-cost');
    Route::post('/student-cost/edit', 'Backend\StudentCostController@form')->name('backend.category.student_cost.edit')->where('id', '[0-9]+')->middleware('permission:category-student-cost-edit');
    Route::post('/student-cost/save', 'Backend\StudentCostController@save')->name('backend.category.student_cost.save')->middleware('permission:category-student-cost-create|category-student-cost-edit');
    Route::post('/student-cost/remove', 'Backend\StudentCostController@remove')->name('backend.category.student_cost.remove')->middleware('permission:category-training-cost-delete');
    Route::post('/student-cost/ajax_isopen_publish', 'Backend\StudentCostController@ajaxIsopenPublish')->name('backend.category.student_cost.ajax_isopen_publish');

    /* commit month */
    Route::get('/commit-month', 'Backend\CommitMonthController@index')->name('backend.category.commit_month')->middleware('permission:commit-month');
    Route::get('/commit-month/getdata', 'Backend\CommitMonthController@getData')->name('backend.category.commit_month.getdata')->middleware('permission:commit-month');
    Route::post('/commit-month/edit', 'Backend\CommitMonthController@form')->name('backend.category.commit_month.edit')->where('id', '[0-9]+')->middleware('permission:commit-month-edit');
    Route::post('/commit-month/save', 'Backend\CommitMonthController@save')->name('backend.category.commit_month.save')->middleware('permission:commit-month-edit|commit-month-create');
    Route::post('/commit-month/saveGroup', 'Backend\CommitMonthController@saveGroup')->name('backend.category.commit_month.save_group')->middleware('permission:commit-month-edit|commit-month-create');
    Route::post('/commit-group/frame/modal', 'Backend\CommitMonthController@showModalFrameCommit')->name('backend.category.commit_month.modal')->middleware('permission:commit-month');
    Route::get('/commit-group/frame/{commit_group_id}', 'Backend\CommitMonthController@getDataFrame')->name('backend.category.commit_month.getdataframe')->middleware('permission:commit-month')->where('commit_group_id','[0-9]+');
    Route::get('/commit-group/frame/edit/{id}', 'Backend\CommitMonthController@getCommitFrame')->name('backend.category.commit_month.frame.edit')->where('id','[0-9]+')->middleware('permission:commit-month');
    Route::post('/commit-group/frame/delete', 'Backend\CommitMonthController@deleteCommitFrame')->name('backend.category.commit_month.frame.delete')->middleware('permission:commit-month');
    Route::post('/commit-month/remove', 'Backend\CommitMonthController@remove')->name('backend.category.commit_month.remove')->middleware('permission:commit-month-delete');

    /* training teacher */
    Route::get('/training-teacher', 'Backend\TrainingTeacherController@index')->name('backend.category.training_teacher')->middleware('permission:category-teacher');
    Route::get('/training-teacher/getdata', 'Backend\TrainingTeacherController@getData')->name('backend.category.training_teacher.getdata')->middleware('permission:category-teacher');
    Route::get('/training-teacher/edit/{id}', 'Backend\TrainingTeacherController@form')->name('backend.category.training_teacher.edit')->where('id', '[0-9]+')->middleware('permission:category-teacher-edit');
    Route::post('/training-teacher/save', 'Backend\TrainingTeacherController@save')->name('backend.category.training_teacher.save')->middleware('permission:category-teacher-create|category-teacher-edit');
    Route::post('/training-teacher/remove', 'Backend\TrainingTeacherController@remove')->name('backend.category.training_teacher.remove')->middleware('permission:category-teacher-delete');
    Route::post('/training-teacher/ajax-get-user', 'Backend\TrainingTeacherController@ajaxGetUser')->name('backend.category.ajax_get_user')->middleware('permission:category-teacher');
    Route::post('/training-teacher/import', 'Backend\TrainingTeacherController@import')->name('backend.category.training_teacher.import')->middleware('permission:category-teacher-import');
    Route::get('/training-teacher/export', 'Backend\TrainingTeacherController@export')->name('backend.category.training_teacher.export')->middleware('permission:category-teacher-export');
    Route::get('/ajax-teacher-schedule', 'Backend\TrainingTeacherController@getDataSchedule')->name('backend.category.training_teacher.schedule');

     /*Lịch sử giảng dạy*/
    Route::get('/training-teacher/{teacher_id}/history', 'Backend\TrainingTeacherHistoryController@index')
        ->name('backend.category.training_teacher.history')
        ->where('teacher_id', '[0-9]+')
        ->middleware('permission:category-teacher');

    Route::get('/training-teacher/{teacher_id}/history-getdata', 'Backend\TrainingTeacherHistoryController@getDataHistory')
        ->name('backend.category.training_teacher.history.getdata')
        ->where('teacher_id', '[0-9]+')
        ->middleware('permission:category-teacher');

    /* training teacher certificate*/
    Route::get('/training-teacher/{teacher_id}/certificate', 'Backend\TrainingTeacherCertificateController@index')
        ->name('backend.category.training_teacher.certificate')
        ->where('teacher_id', '[0-9]+')
        ->middleware('permission:category-teacher');

    Route::get('/training-teacher/{teacher_id}/certificate/getdata', 'Backend\TrainingTeacherCertificateController@getData')
        ->name('backend.category.training_teacher.certificate.getdata')
        ->where('teacher_id', '[0-9]+')
        ->middleware('permission:category-teacher');

    Route::post('/training-teacher/{teacher_id}/certificate/edit', 'Backend\TrainingTeacherCertificateController@form')
        ->name('backend.category.training_teacher.certificate.edit')
        ->where('teacher_id', '[0-9]+')
        ->middleware('permission:category-teacher-edit');

    Route::post('/training-teacher/{teacher_id}/certificate/save', 'Backend\TrainingTeacherCertificateController@save')
        ->name('backend.category.training_teacher.certificate.save')
        ->where('teacher_id', '[0-9]+')
        ->middleware('permission:category-teacher-create|category-teacher-edit');

    Route::post('/training-teacher/{teacher_id}/certificate/remove', 'Backend\TrainingTeacherCertificateController@remove')
        ->name('backend.category.training_teacher.certificate.remove')
        ->where('teacher_id', '[0-9]+')
        ->middleware('permission:category-teacher-delete');

    Route::post('/training-teacher/{teacher_id}/certificate/show_image', 'Backend\TrainingTeacherCertificateController@showImage')
        ->name('backend.category.training_teacher.certificate.show_image')
        ->where('teacher_id', '[0-9]+')
        ->middleware('permission:category-teacher-edit');

    /* Unit Type */
    Route::get('/unit-type', 'Backend\UnitTypeController@index')->name('backend.category.unit_type')->middleware('permission:category-unit-type');
    Route::get('/unit-type/getdata', 'Backend\UnitTypeController@getData')->name('backend.category.unit_type.getdata')->middleware('permission:category-unit-type');
    Route::post('/unit-type/edit', 'Backend\UnitTypeController@form')->name('backend.category.unit_type.edit')->where('id', '[0-9]+')->middleware('permission:category-unit-type-edit');
    Route::get('/unit-type/create', 'Backend\UnitTypeController@form')->name('backend.category.unit_type.create')->middleware('permission:category-unit-type-create');
    Route::post('/unit-type/save', 'Backend\UnitTypeController@save')->name('backend.category.unit_type.save')->middleware('permission:category-unit-type-create|category-unit-type-edit');
    Route::post('/unit-type/remove', 'Backend\UnitTypeController@remove')->name('backend.category.unit_type.remove')->middleware('permission:category-unit-type-delete');

    /* Training Partner */
    Route::get('/training-partner', 'Backend\TrainingPartnerController@index')->name('backend.category.training_partner')->middleware('permission:category-partner');
    Route::get('/training-partner/getdata', 'Backend\TrainingPartnerController@getData')->name('backend.category.training_partner.getdata')->middleware('permission:category-partner');
    Route::post('/training-partner/edit', 'Backend\TrainingPartnerController@form')->name('backend.category.training_partner.edit')->where('id', '[0-9]+')->middleware('permission:category-partner-edit');
    Route::post('/training-partner/save', 'Backend\TrainingPartnerController@save')->name('backend.category.training_partner.save')->middleware('permission:category-partner-create|category-partner-edit');
    Route::post('/training-partner/remove', 'Backend\TrainingPartnerController@remove')->name('backend.category.training_partner.remove')->middleware('permission:category-partner-delete');
    Route::get('/export-training-partner', 'Backend\TrainingPartnerController@exportTrainingPartner')->name('backend.training_partner_export')->middleware('permission:category-partner-export');
    Route::get('/training-partner-cost/{id}', 'Backend\TrainingPartnerController@trainingPartnerCost')->name('backend.training_partner_cost')->middleware('permission:category-partner');
    Route::get('/training-partner-cost-getdata/{id}', 'Backend\TrainingPartnerController@trainingPartnerCostGetData')->name('backend.training_partner_cost_getdata')->middleware('permission:category-partner');
    Route::get('/training-partner-cost-detail/{id}/{courseId}', 'Backend\TrainingPartnerController@trainingPartnerCostDetail')->name('backend.training_partner_cost_detail')->middleware('permission:category-partner');

    /* Training Form */
    Route::get('/training-form', 'Backend\TrainingFormController@index')->name('backend.category.training_form')->middleware('permission:category-training-form');
    Route::get('/training-form/getdata', 'Backend\TrainingFormController@getData')->name('backend.category.training_form.getdata')->middleware('permission:category-training-form');
    Route::post('/training-form/edit', 'Backend\TrainingFormController@form')->name('backend.category.training_form.edit')->where('id', '[0-9]+')->middleware('permission:category-training-form-edit');
    Route::post('/training-form/save', 'Backend\TrainingFormController@save')->name('backend.category.training_form.save')->middleware('permission:category-training-form-edit|category-training-form-create');
    Route::post('/training-form/remove', 'Backend\TrainingFormController@remove')->name('backend.category.training_form.remove')->middleware('permission:category-training-form-delete');
    Route::post('/training-form/ajax_isopen_publish', 'Backend\TrainingFormController@ajaxIsopenPublish')->name('backend.category.training_form.ajax_isopen_publish');

    /* Teacher Type */
    Route::get('/teacher-type', 'Backend\TeacherTypeController@index')->name('backend.category.teacher_type')->middleware('permission:category-teacher-type');
    Route::get('/teacher-type/getdata', 'Backend\TeacherTypeController@getData')->name('backend.category.teacher_type.getdata')->middleware('permission:category-teacher-type');
    Route::post('/teacher-type/edit', 'Backend\TeacherTypeController@form')->name('backend.category.teacher_type.edit')->where('id', '[0-9]+')->middleware('permission:category-teacher-type-edit');
    Route::post('/teacher-type/save', 'Backend\TeacherTypeController@save')->name('backend.category.teacher_type.save')->middleware('permission:category-teacher-type-create|category-teacher-type-edit');
    Route::post('/teacher-type/remove', 'Backend\TeacherTypeController@remove')->name('backend.category.teacher_type.remove')->middleware('permission:category-teacher-type-delete');
    Route::post('/teacher-type/ajax_isopen_publish', 'Backend\TeacherTypeController@ajaxIsopenPublish')->name('backend.category.teacher_type.ajax_isopen_publish');

    /* Cost Lessons */
    Route::get('/cost-lessons', 'Backend\CostLessonsController@index')->name('backend.category.cost_lessons');
    Route::get('/cost-lessons/getdata', 'Backend\CostLessonsController@getData')->name('backend.category.cost_lessons.getdata');
    Route::get('/cost-lessons/edit/{id}', 'Backend\CostLessonsController@form')->name('backend.category.cost_lessons.edit')->where('id', '[0-9]+');
    Route::get('/cost-lessons/create', 'Backend\CostLessonsController@form')->name('backend.category.cost_lessons.create');
    Route::post('/cost-lessons/save', 'Backend\CostLessonsController@save')->name('backend.category.cost_lessons.save');
    Route::post('/cost-lessons/remove', 'Backend\CostLessonsController@remove')->name('backend.category.cost_lessons.remove');

    /* Cert */
    Route::get('/cert', 'Backend\CertController@index')->name('backend.category.cert')
    ->middleware('permission:category-cert');

    Route::get('/cert/getdata', 'Backend\CertController@getData')->name('backend.category.cert.getdata')
    ->middleware('permission:category-cert');

    Route::post('/cert/edit', 'Backend\CertController@form')->name('backend.category.cert.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:category-cert-edit');

    Route::post('/cert/save', 'Backend\CertController@save')->name('backend.category.cert.save')
    ->middleware('permission:category-cert-create');

    Route::post('/cert/remove', 'Backend\CertController@remove')->name('backend.category.cert.remove')
    ->middleware('permission:category-cert-delete');
});

/* Chứng chỉ Chương trình đào tạo */
Route::group(['prefix' => '/admin-cp/subject-type', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\SubjectTypeController@index')->name('backend.category.subject_type')->middleware('permission:category-subject-type');
    Route::get('/getdata', 'Backend\SubjectTypeController@getData')->name('backend.category.subject_type.getdata')->middleware('permission:category-subject-type');
    Route::get('/edit', 'Backend\SubjectTypeController@form')->name('backend.category.subject_type.edit')->where('id', '[0-9]+')->middleware('permission:category-subject-type-edit');
    Route::get('/create', 'Backend\SubjectTypeController@form')->name('backend.category.subject_type.create')->middleware('permission:category-subject-type-create');
    Route::post('/save', 'Backend\SubjectTypeController@save')->name('backend.category.subject_type.save')->middleware('permission:category-subject-type-edit|category-subject-type-create');
    Route::post('/remove', 'Backend\SubjectTypeController@remove')->name('backend.category.subject_type.remove')->middleware('permission:category-subject-type-delete');
    Route::get('/export', 'Backend\SubjectTypeController@export')->name('backend.category.subject_type.export')->middleware('permission:category-subject-type-export');
    Route::post('/import', 'Backend\SubjectTypeController@import')->name('backend.category.subject_type.import')->middleware('permission:category-subject-type-export');
    Route::post('/ajax_isopen_publish', 'Backend\SubjectTypeController@ajaxIsopenPublish')->name('backend.category.subject_type.ajax_isopen_publish');
    Route::get('/push_data_object/{id}', 'Backend\SubjectTypeController@pushDataObject')->name('backend.category.subject_type.push_data_object')->where('id', '[0-9]+');
    Route::get('/get-user-result', 'Backend\SubjectTypeController@getUserResult')->name('module.subject-type.get_user_result')->where('subject_type_id', '[0-9]+');

     Route::post('/edit/{id}/save-object', 'Backend\SubjectTypeController@saveObject')->name('module.subject-type.save_object')->where('id', '[0-9]+');
    Route::get('/edit/{id}/get-object', 'Backend\SubjectTypeController@getObject')->name('module.subject-type.get_object')->where('id', '[0-9]+');
    Route::get('/edit/{id}/get-user-object', 'Backend\SubjectTypeController@getUserObject')->name('module.subject-type.get_user_object')->where('id', '[0-9]+');
    // Route::post('/edit/{id}/remove-object', 'Backend\SubjectTypeController@removeObject')->name('module.subject-type.remove_object')->where('id', '[0-9]+');
    Route::post('/edit/{id}/import-object', 'Backend\SubjectTypeController@importObject')->name('module.subject-type.import_object')->where('id', '[0-9]+');
    Route::post('/edit/{id}/check-unit-child', 'Backend\SubjectTypeController@getChild')->name('module.subject-type.get_child')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-tree-child', 'Backend\SubjectTypeController@getTreeChild')
        ->name('module.subject-type.get_tree_child')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/permission'], function() {

    Route::get('/', 'Backend\PermissionController@index')->name('backend.permission');

    Route::get('/list-permisstion', 'Backend\PermissionController@listPermisstion')->name('backend.permission.list_permisstion');

    Route::get('/getdata', 'Backend\PermissionController@getDataPermission')->name('backend.permission.list_permisstion.getdata');

    Route::get('/detail/{permission_id}', 'Backend\PermissionController@detail')->name('backend.permission.detail')->where('permission_id', '[0-9]+');

    Route::get('/detail/{permission_id}/getdata', 'Backend\PermissionController@getDataPermissionUser')->name('backend.permission.detail.getdata')->where('permission_id', '[0-9]+');

    Route::get('/detail/{permission_id}/edit/{user_id}/{unit_id}', 'Backend\PermissionController@formUser')->name('backend.permission.detail.edit')->where('permission_id', '[0-9]+')->where('user_id', '[0-9]+')->where('unit_id', '[0-9]+');

    Route::get('/detail/{permission_id}/create', 'Backend\PermissionController@formUser')->name('backend.permission.detail.create')->where('permission_id', '[0-9]+');

    Route::post('/detail/{permission_id}/save', 'Backend\PermissionController@save')->name('backend.permission.detail.save')->where('permission_id', '[0-9]+');

    Route::post('/detail/{permission_id}/remove', 'Backend\PermissionController@remove')->name('backend.permission.detail.remove')->where('permission_id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/permission/group'], function() {

    Route::get('/', 'Backend\PermissionGroupController@index')->name('backend.permission_group');

    Route::get('/getdata', 'Backend\PermissionGroupController@getData')->name('backend.permission_group.getdata');

    Route::post('/save', 'Backend\PermissionGroupController@save')->name('backend.permission_group.save');

    Route::post('/remove', 'Backend\PermissionGroupController@remove')->name('backend.permission_group.remove');

    Route::post('/get-json', 'Backend\PermissionGroupController@getJson')->name('backend.permission_group.getjson');
});

Route::group(['prefix'=>'/admin-cp/evaluationform','middleware'=>'auth'],function(){
    Route::get('/', 'Backend\EvaluationFormController@index')->name('backend.evaluationform.manager');
});

Route::group(['prefix' => '/admin-cp/permission/unit'], function() {
    Route::get('/', 'Backend\UnitPermissionController@index')->name('backend.unit_permission');

    Route::get('/getdata', 'Backend\UnitPermissionController@getData')->name('backend.unit_permission.getdata');

    Route::post('/remove', 'Backend\UnitPermissionController@remove')->name('backend.unit_permission.remove');

    Route::get('/create', 'Backend\UnitPermissionController@form')->name('backend.unit_permission.create');

    Route::get('/edit/{id}', 'Backend\UnitPermissionController@form')->name('backend.unit_permission.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\UnitPermissionController@save')->name('backend.unit_permission.save');
});

Route::group(['prefix' => '/admin-cp/feedback'], function() {
    Route::get('/', 'Backend\FeedbackController@index')->name('backend.feedback');

    Route::get('/getdata', 'Backend\FeedbackController@getData')->name('backend.feedback.getdata');

    Route::post('/remove', 'Backend\FeedbackController@remove')->name('backend.feedback.remove');

    Route::get('/create', 'Backend\FeedbackController@form')->name('backend.feedback.create');

    Route::get('/edit/{id}', 'Backend\FeedbackController@form')->name('backend.feedback.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\FeedbackController@save')->name('backend.feedback.save');
});

Route::group(['prefix' => '/admin-cp/mail-template'], function() {
    Route::get('/', 'Backend\MailTemplateController@index')->name('backend.mailtemplate')->middleware('permission:mail-template');

    Route::get('/getdata', 'Backend\MailTemplateController@getData')->name('backend.mailtemplate.getdata')->middleware('permission:mail-template');

    Route::get('/edit/{id}', 'Backend\MailTemplateController@form')->name('backend.mailtemplate.edit')->where('id', '[0-9]+')->middleware('permission:mail-template-edit');

    Route::post('/save', 'Backend\MailTemplateController@save')->name('backend.mailtemplate.save')->middleware('permission:mail-template-edit');

    Route::post('/update_time_send', 'Backend\MailTemplateController@updateTimeSend')->name('backend.mailtemplate.update_time_send')->middleware('permission:mail-template-edit');
});

Route::group(['prefix' => '/admin-cp/mail-signature'], function() {
    Route::get('/', 'Backend\MailSignatureController@index')->name('backend.mail_signature')->middleware('permission:mail-template');

    Route::get('/getdata', 'Backend\MailSignatureController@getData')->name('backend.mail_signature.getdata')->middleware('permission:mail-template');

    Route::get('/create', 'Backend\MailSignatureController@form')->name('backend.mail_signature.create')->middleware('permission:mail-template');

    Route::get('/edit/{id}', 'Backend\MailSignatureController@form')->name('backend.mail_signature.edit')->where('id', '[0-9]+')->middleware('permission:mail-template-edit');

    Route::post('/save', 'Backend\MailSignatureController@save')->name('backend.mail_signature.save')->middleware('permission:mail-template-edit');

    Route::post('/remove', 'Backend\MailSignatureController@remove')->name('backend.mail_signature.remove')->middleware('permission:mail-template-edit');
});

Route::group(['prefix' => '/admin-cp/mail-history'], function() {
    Route::get('/', 'Backend\MailHistoryController@index')->name('backend.mailhistory')->middleware('permission:mail-template-history');

    Route::get('/getdata', 'Backend\MailHistoryController@getData')->name('backend.mailhistory.getdata')->middleware('permission:mail-template-history');
});

Route::group(['prefix' => '/admin-cp/donate-points'], function() {
    Route::get('/', 'Backend\DonatePointsController@index')->name('backend.donate_points')->middleware('permission:donate-point');

    Route::get('/getdata', 'Backend\DonatePointsController@getData')->name('backend.donate_points.getdata')->middleware('permission:donate-point');

    Route::post('/remove', 'Backend\DonatePointsController@remove')->name('backend.donate_points.remove')->middleware('permission:donate-point-delete');

    Route::post('/edit', 'Backend\DonatePointsController@form')->name('backend.donate_points.edit')->where('id', '[0-9]+')->middleware('permission:donate-point-edit');

    Route::post('/save', 'Backend\DonatePointsController@save')->name('backend.donate_points.save')->middleware('permission:donate-point-edit|donate-point-create');

    Route::post('/get-title-unit-ajax', 'Backend\DonatePointsController@getTitleUnit')->name('backend.donate_points.get_title_unit')->middleware('permission:donate-point');

    Route::post('/import-donate-points', 'Backend\DonatePointsController@import_donate_points')->name('backend.donate_points.import')->middleware('permission:donate-point-import');

    Route::get('/export-donate-points', 'Backend\DonatePointsController@export_donate_points')->name('backend.donate_points.export')->middleware('permission:donate-point-export');
});

Route::group(['prefix'=> '/admin-cp/app-mobile'], function () {
    Route::get('/', 'Backend\AppMobileController@index')->name('backend.app_mobile')->middleware('permission:config-app-mobile');
    Route::post('/save', 'Backend\AppMobileController@save')->name('backend.app_mobile.save')->middleware('permission:config-app-mobile-save');
});

Route::group(['prefix'=> '/admin-cp/config'], function () {
    Route::get('/', 'Backend\ConfigController@index')->name('backend.config')->middleware('permission:config');

    Route::get('/get-form', 'Backend\ConfigController@load')->name('backend.config.get-form')->middleware('permission:config');

    Route::post('/save', 'Backend\ConfigController@save')->name('backend.config.save')->middleware('permission:config-save');
});

// GHI CHÚ NGƯỜI DÙNG
Route::group(['prefix' => '/admin-cp/note'], function() {
    Route::get('/', 'Backend\NoteController@index')->name('backend.note')
    ->middleware('permission:note');

    Route::get('/getdata', 'Backend\NoteController@getData')->name('backend.note.getdata')
    ->middleware('permission:note');
});

// LỊCH SỬ TRUY CẬP
Route::group(['prefix' => '/admin-cp/login-history'], function() {
    Route::get('/', 'Backend\LoginHistoryController@index')->name('backend.login-history')
    ->middleware('permission:login-history');
});

// NGƯỜI DÙNG LIÊN HỆ
Route::group(['prefix' => '/admin-cp/user-contact'], function() {

    Route::get('/', 'Backend\UserContactOutsideController@index')->name('backend.user-contact')
    ->middleware('permission:user-contact');

    Route::get('/getdata', 'Backend\UserContactOutsideController@getData')->name('backend.user-contact.getdata')
    ->middleware('permission:user-contact');

    Route::post('/remove', 'Backend\UserContactOutsideController@remove')->name('backend.user-contact.remove')
    ->middleware('permission:user-contact-delete');
});

Route::group(['prefix' => '/'.request()->segment(1).'/check-select-unit'], function() {
    Route::post('/show-child-unit-manager', 'Controller@showChildUnitManager')->name('backend.show_child_unit_manager');
    Route::post('/', 'Controller@checkSelectUnit')->name('backend.check_select_unit');
    Route::post('/save', 'Controller@saveSelectUnit')->name('backend.save_select_unit');
    Route::post('/save-role', 'Controller@saveSelectRole')->name('backend.save_select_role');
});

Route::group(['prefix' => '/admin-cp/approve'], function() {
    Route::post('/', 'Controller@approve')->name('backend.approve.model');
    Route::post('/modal-note-approved', 'Controller@showModalNoteApproved')->name('backend.show.modal_note_approved');
    Route::post('/modal-step-approved', 'Controller@showModalStepApproved')->name('backend.show.modal_step_approved');
    Route::get('/get-approved-step', 'Controller@getApprovedStep')->name('backend.get_approved_step'); //where('model_id','[0-9]+');

    Route::post('/modal-show-permission-approve', 'Controller@showModalPermissionApprove')->name('backend.show.modal_permission_approve');
    Route::get('/get-permission-approve', 'Controller@getPermissionApprove')->name('backend.get_permission_approve'); //where('model_id','[0-9]+');
});

Route::group(['prefix' => '/admin-cp/approve-teacher-register'], function() {
    Route::get('/', 'Backend\ApproveTeacherRegisterController@index')->name('backend.approve_teacher_register');
    Route::get('/getdata', 'Backend\ApproveTeacherRegisterController@getData')->name('backend.approve_teacher_register.getdata');
    Route::post('/approve', 'Backend\ApproveTeacherRegisterController@approve')->name('backend.approve_teacher_register.approve');
    Route::post('/remove', 'Backend\ApproveTeacherRegisterController@remove')->name('backend.approve_teacher_register.remove');
    Route::post('/save-note', 'Backend\ApproveTeacherRegisterController@saveNote')->name('backend.approve_teacher_register.save_note');
});

Route::group(['prefix' => '/admin-cp/languages'], function() {
    Route::get('/', 'Backend\LanguagesController@index')->name('backend.languages')
    ->middleware('permission:languages');

    Route::get('/{id}/getdata', 'Backend\LanguagesController@getData')->name('backend.languages.getdata')
    ->where('id', '[0-9]+')
    ->middleware('permission:languages');

    Route::post('/remove', 'Backend\LanguagesController@remove')->name('backend.languages.remove')
    ->middleware('permission:languages');

    Route::get('/{id}/create', 'Backend\LanguagesController@form')->name('backend.languages.create')
    ->where('id', '[0-9]+')
    ->middleware('permission:languages-create');

    Route::get('/{idg}/edit/{id}', 'Backend\LanguagesController@form')->name('backend.languages.edit')
    ->where('id', '[0-9]+')
    ->middleware('permission:languages-create');

    Route::get('/{id}', 'Backend\LanguagesController@index')->name('backend.languages.group')
    ->where('id', '[0-9]+')
    ->middleware('permission:languages');

    Route::post('/{id}/save', 'Backend\LanguagesController@save')->name('backend.languages.save')
    ->where('id', '[0-9]+')
    ->middleware('permission:languages');

    Route::get('/synchronize', 'Backend\LanguagesController@synchronize')->name('backend.languages.synchronize')
    ->middleware('permission:languages');

    Route::get('/syncdb2file', 'Backend\LanguagesController@syncDB2File')->name('backend.languages.syncdb2file')
        ->middleware('permission:languages');

    Route::get('/export', 'Backend\LanguagesController@export')->name('backend.languages.export')
    ->middleware('permission:languages');

    Route::get('/export_file', 'Backend\LanguagesController@export_file')->name('backend.languages.export_file')
    ->middleware('permission:languages');

    Route::post('/languages/import', 'Backend\LanguagesController@import_languages')->name('backend.languages.import')
    ->middleware('permission:languages');

    Route::post('/get_modal', 'Backend\LanguagesController@showModal')->name('backend.languages.get_modal')
    ->middleware('permission:languages');

    Route::post('/save_group', 'Backend\LanguagesController@saveGroup')->name('backend.languages.save_group')
    ->middleware('permission:languages');

    Route::post('/create-new', 'Backend\LanguagesController@createNew')->name('backend.languages.create_new')
    ->middleware('permission:languages');
    Route::post('/git-push', 'Backend\LanguagesController@gitPush')->name('backend.languages.git_push')
        ->middleware('permission:languages');
});

Route::group(['prefix' => '/admin-cp/interaction_history_clear'], function() {
    Route::get('/', 'Backend\InteractionHistoryClearController@index')->name('backend.interaction_history_clear')
    ->middleware('permission:interaction-history-clear');

    Route::get('/getdata', 'Backend\InteractionHistoryClearController@getData')->name('backend.interaction_history_clear.getdata')
    ->middleware('permission:interaction-history-clear');

    Route::post('/save', 'Backend\InteractionHistoryClearController@save')->name('backend.interaction_history_clear.save')
    ->middleware('permission:interaction-history-clear-create');
});

// THIẾT LẬP MENU
Route::group(['prefix' => '/admin-cp/menu-setting'], function() {
    Route::get('/', 'Backend\MenuSettingController@index')->name('backend.menu_setting')
    ->middleware('permission:menu-setting');

    Route::post('/edit', 'Backend\MenuSettingController@form')->name('backend.menu_setting.edit')
    ->middleware('permission:menu-setting');

    Route::get('/getdata', 'Backend\MenuSettingController@getData')->name('backend.menu_setting.getdata')
    ->middleware('permission:menu-setting');

    Route::post('/save', 'Backend\MenuSettingController@save')->name('backend.menu_setting.save')
    ->middleware('permission:menu-setting-save');

    Route::post('/remove', 'Backend\LanguagesController@remove')->name('backend.menu_setting.remove')
    ->middleware('permission:menu-setting-delete');
});

// LỊCH ĐÀO TẠO
Route::group(['prefix' => '/admin-cp/training-calendar'], function() {
    Route::get('/', 'Backend\TrainingCalendar@index')->name('backend.training_calendar')
    ->middleware('permission:training-calendar');

    Route::get('/ajax-calendar', 'Backend\TrainingCalendar@getData')->name('backend.training_calendar.getdata');
});
