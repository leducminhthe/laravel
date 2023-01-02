const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');
mix.mergeManifest();

mix.styles([
    'resources/styles/vendor/unicons-2.0.1/css/unicons.css',
    'resources/styles/css/vertical-responsive-menu.min.css',
    'resources/styles/css/style.css',
    'resources/styles/css/custom.css',
    'resources/styles/css/instructor-dashboard.css',
    'resources/styles/css/instructor-responsive.css',
    'resources/styles/vendor/fontawesome-free/css/all.min.css',
    'resources/styles/vendor/OwlCarousel/assets/owl.carousel.min.css',
    'resources/styles/vendor/OwlCarousel/assets/owl.theme.default.min.css',
    'resources/styles/vendor/bootstrap/css/bootstrap.min.css',
    'resources/styles/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
    'resources/styles/vendor/bootstrap-table/bootstrap-table.min.css',
    'resources/styles/vendor/select2/select2.min.css',
    'resources/styles/vendor/fullcalendar/main.css',
    'resources/mobile/vendor/swiper/css/swiper.min.css',
    // 'resources/styles/vendor/semantic/semantic.min.css',
    // 'resources/styles/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
    // 'resources/styles/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
    // 'resources/styles/vendor/bxslider/jquery.bxslider.css',
    // 'resources/styles/vendor/notiflix/notiflix-2.3.2.min.css',
    // 'resources/styles/css/frontend/forum.css',
    // 'resources/styles/file-manager/css/dropzone.css',
    // 'resources/styles/css/material-design-iconic-font.min.css',
    // 'resources/styles/css/chat.css',
    // 'resources/styles/css/jquery-steps.css',
    // 'resources/styles/css/media.css',
    'public/css/night_mode.css'
], 'public/css/theme.css').version();
mix.minify('public/css/theme.css');

mix.styles([
    'resources/styles/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
    'resources/styles/vendor/unicons-2.0.1/css/unicons.css',
    'resources/styles/css/vertical-responsive-menu.min.css',
    'resources/styles/css/style.css',
    'resources/styles/css/custom.css',
    'resources/styles/vendor/bootstrap/css/bootstrap.min.css',
    'resources/styles/vendor/fontawesome-free/css/all.min.css',
    // 'resources/styles/vendor/OwlCarousel/assets/owl.carousel.min.css',
    // 'resources/styles/vendor/OwlCarousel/assets/owl.theme.default.min.css',
    // 'resources/styles/vendor/semantic/semantic.min.css',
    // 'resources/styles/vendor/bxslider/jquery.bxslider.css',
    // 'resources/styles/css/frontend/forum.css',
    // 'resources/styles/vendor/fullcalendar/main.css',
    // 'resources/styles/file-manager/css/dropzone.css',
    // 'resources/styles/css/material-design-iconic-font.min.css',
    // 'resources/styles/vendor/select2/select2.min.css',
    // 'resources/styles/css/chat.css',
    'public/css/night_mode.css'
], 'public/css/reactjs.css').version();
mix.minify('public/css/reactjs.css');

mix.babel([
    'resources/styles/js/jquery-3.5.1.min.js',
    'resources/styles/vendor/jquery-ui/jquery-ui.min.js',
    'resources/styles/js/moment.min.js',
    'resources/styles/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
    'resources/styles/vendor/sweetalert2/sweetalert2.js',
    'resources/styles/vendor/select2/select2.min.js',
    'resources/styles/js/load-ajax.js',
    'resources/styles/js/load-select2.js',
    'resources/styles/file-manager/js/dropzone.js',
    // 'resources/styles/js/lazyload.min.js',
    // 'resources/styles/vendor/charts/Chart.min.js',
    // 'resources/styles/vendor/fullcalendar/main.js',
    // 'resources/styles/js/custom.js',
], 'public/js/reactjs.js').version();
mix.minify('public/js/reactjs.js');

mix.babel([
    'resources/styles/js/jquery-3.5.1.min.js',
    'resources/styles/vendor/jquery-ui/jquery-ui.min.js',
    'resources/styles/vendor/bootstrap/js/popper.min.js',
    'resources/styles/js/moment.min.js',
    'resources/styles/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
    'resources/styles/vendor/bootstrap-table/bootstrap-table.min.js',
    'resources/styles/vendor/bootstrap-table/bootstrap-table-vi-VN.js',
    'resources/styles/vendor/sweetalert2/sweetalert2.js',
    'resources/styles/vendor/select2/select2.min.js',
    'resources/styles/module/quiz/js/tether.min.js',
    'resources/styles/js/LoadBootstrapTable.js',
    'resources/styles/js/load-ajax.js',
    'resources/styles/js/form-ajax.js',
    'resources/styles/vendor/charts/Chart.min.js',
    'resources/styles/vendor/fullcalendar/main.js',
    'resources/styles/js/load-select2.js',
    'resources/styles/js/custom.js',
    'resources/mobile/vendor/swiper/js/swiper.min.js',
    // 'resources/styles/vendor/bxslider/jquery.bxslider.min.js',
    // 'resources/styles/vendor/jscroll/jquery.jscroll.min.js',
    // 'resources/styles/module/quiz/js/clock.js',
    // 'resources/styles/js/frontend/form-ajax.js',
    // 'resources/styles/vendor/bootstrap-datetimepicker/js/load-datetimepicker.js',
    // 'resources/styles/vendor/bootstrap-timepicker/js/bootstrap-timepicker.js',
    // 'resources/styles/js/lazyload.min.js',
    // 'resources/styles/file-manager/js/dropzone.js',
], 'public/js/theme.js').version();
mix.minify('public/js/theme.js');

// mix.combine([
//     'resources/styles/vendor/notiflix/notiflix-2.3.2.min.js',
// ], 'public/js/jquery-theme.js');

mix.babel([
    'resources/styles/vendor/bootstrap/js/bootstrap.bundle.min.js',
    'resources/styles/vendor/OwlCarousel/owl.carousel.min.js',
    'resources/styles/js/frontendJs.js',
    'resources/styles/js/LeftMenuFrontend.js',
    'resources/styles/js/MenuBottomFrontend.js',
    // 'resources/styles/vendor/semantic/semantic.min.js',
    // 'resources/styles/js/vertical-responsive-menu.min.js',
], 'public/js/theme2.js').version();
mix.minify('public/js/theme2.js');

mix.styles([
    'resources/styles/vendor/unicons-2.0.1/css/unicons.css',
    'resources/styles/css/vertical-responsive-menu.min.css',
    'resources/styles/css/style.css',
    // 'resources/styles/css/responsive.css',
    'resources/styles/css/custom.css',
    'resources/styles/vendor/fontawesome-free/css/all.min.css',
    'resources/styles/vendor/OwlCarousel/assets/owl.carousel.min.css',
    'resources/styles/vendor/OwlCarousel/assets/owl.theme.default.min.css',
    'resources/styles/vendor/bootstrap/css/bootstrap.min.css',
    //'resources/styles/vendor/semantic/semantic.min.css',
    'resources/styles/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
    // 'resources/styles/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
    'resources/styles/vendor/bootstrap-table/bootstrap-table.min.css',
    'resources/styles/vendor/select2/select2.min.css',
    // 'resources/styles/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
    'resources/styles/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
    'resources/styles/css/backend/category/css/category.css',
    'public/css/night_mode.css'
], 'public/css/backend.css').version();
mix.minify('public/css/backend.css');

mix.combine([
    //'resources/styles/js/jquery-3.3.1.min.js',
    'resources/styles/js/jquery-3.5.1.min.js',
    'resources/styles/vendor/jquery-ui/jquery-ui.min.js',
    'resources/styles/vendor/bootstrap/js/popper.min.js',
    // 'resources/styles/vendor/bootstrap/js/bootstrap.min.js',
    // 'resources/styles/vendor/bootstrap-timepicker/js/bootstrap-timepicker.js',
    // 'resources/styles/vendor/bxslider/jquery.bxslider.min.js',
    'resources/styles/vendor/sweetalert2/sweetalert2.js',
    'resources/styles/vendor/select2/select2.min.js',
    // 'resources/styles/vendor/jscroll/jquery.jscroll.min.js',
    // 'resources/styles/module/quiz/js/clock.js',
    'resources/styles/module/quiz/js/tether.min.js',
    'resources/styles/vendor/bootstrap-table/bootstrap-table.min.js',
    'resources/styles/vendor/bootstrap-table/bootstrap-table-vi-VN.js',
    'resources/styles/vendor/jquery-validate/jquery.validate.min.js',
    'resources/styles/js/moment.min.js',
    'resources/styles/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
    'resources/styles/js/BootstrapTable.js',
    'resources/styles/js/LoadBootstrapTable.js',
    //'resources/styles/vendor/bootstrap-datetimepicker/js/load-datetimepicker.js',
    'resources/styles/js/load-ajax.js',
    'resources/styles/js/form-ajax.js',
    // 'resources/styles/js/customs-frontend.js',
    // 'resources/styles/js/config.js',
], 'public/js/backend.js').version();
mix.minify('public/js/backend.js');

mix.combine([
    // 'resources/styles/js/vertical-responsive-menu.min.js',
    'resources/styles/vendor/bootstrap/js/bootstrap.bundle.min.js',
    'resources/styles/vendor/OwlCarousel/owl.carousel.min.js',
    // 'resources/styles/vendor/semantic/semantic.min.js',
    // 'resources/styles/js/custom-backend.js',
    // 'resources/styles/js/night-mode.js',
    'resources/styles/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    'resources/styles/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.vi.min.js',
    'resources/styles/vendor/bootstrap-datepicker/js/load-datepicker.js',
    'resources/styles/js/load-select2-backend.js',
    'resources/styles/js/common.js',
    'resources/styles/js/inputmask.js',
], 'public/js/backend2.js').version();
mix.minify('public/js/backend2.js');

mix.styles([
    'resources/styles/vendor/bootstrap/css/bootstrap.min.css',
    'resources/styles/vendor/fontawesom/css/font-awesome.min.css',
    'resources/styles/file-manager/css/jquery-ui.min.css',
    'resources/styles/file-manager/css/cropper.min.css',
    'resources/styles/file-manager/css/lfm.css',
    'resources/styles/file-manager/css/mfb.css',
    'resources/styles/file-manager/css/dropzone.css',
], 'public/css/lfm.css').version();
mix.minify('public/css/lfm.css');

mix.combine([
    //'resources/styles/js/jquery-3.3.1.min.js',
    'resources/styles/js/jquery-3.5.1.min.js',
    'resources/styles/file-manager/js/popper.min.js',
    'resources/styles/vendor/bootstrap/js/bootstrap.min.js',
    'resources/styles/file-manager/js/bootbox.min.js',
    'resources/styles/file-manager/js/jquery-ui.min.js',
    'resources/styles/file-manager/js/cropper.min.js',
    'resources/styles/file-manager/js/jquery.form.min.js',
    'resources/styles/file-manager/js/dropzone.js',
    'resources/styles/file-manager/js/script.js',
], 'public/js/lfm.js').version();
mix.minify('public/js/lfm.js');

/**login**/
mix.combine([
    'resources/styles/vendor/bootstrap/js/bootstrap.bundle.min.js',
    'resources/styles/vendor/OwlCarousel/owl.carousel.min.js',
    // 'resources/styles/vendor/semantic/semantic.min.js',
    // 'resources/styles/js/night-mode.js',
], 'public/js/theme3.js');
mix.minify('public/js/theme3.js');
mix.js('resources/js/app.js', 'public/js');
mix.minify('public/js/app.js');
/*****/

mix.sass('resources/styles/css/night_mode.scss', 'public/css/night_mode.css');
mix.sass('resources/styles/css/theme_dark.scss', 'public/css/theme_dark.css');
mix.sass('resources/styles/css/topic_situation.scss', 'public/css/topic_situation.css');
mix.sass('resources/styles/css/news.scss', 'public/css/news.css');

mix.js('resources/js/appReact.js', 'public/js/appReact.js').react()
  .sass('resources/sass/appReact.scss', 'public/css/appReact.css');
mix.minify('public/js/appReact.js');

mix.babel('resources/styles/js/loadModalChooseUnit.js', 'public/js/loadModalChooseUnit.js').minify('public/js/loadModalChooseUnit.js');
mix.babel('resources/styles/js/allCourse.js', 'public/js/allCourse.js').minify('public/js/allCourse.js');
mix.babel('resources/styles/js/rating_level.js', 'public/js/rating_level.js');
mix.babel('resources/styles/js/dashboardFrontend.js', 'public/js/dashboardFrontend.js');

/*Mobile*/
mix.combine([
    'resources/mobile/vendor/materializeicon/material-icons.css',
    'resources/mobile/vendor/fontawesome-free/css/all.min.css',
    'resources/mobile/vendor/bootstrap-4.4.1/css/bootstrap.min.css',
    'resources/mobile/vendor/swiper/css/swiper.min.css',
    'resources/mobile/vendor/OwlCarousel/assets/owl.carousel.min.css',
    'resources/mobile/vendor/OwlCarousel/assets/owl.theme.default.min.css',
    'resources/mobile/vendor/select2/css/select2.min.css',
    'resources/mobile/vendor/fullcalendar/main.css',
    'resources/mobile/vendor/bootstrap-table/bootstrap-table.min.css',
    'resources/mobile/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
    // 'resources/mobile/vendor/emojionearea/css/emojionearea.min.css',
    'resources/mobile/css/style.css',
    'resources/mobile/css/custom.css',
    'resources/mobile/css/dropzone.css',
    'public/css/theme_dark.css',
], 'public/themes/mobile/css/app_mobile_header.css').version();
mix.minify('public/themes/mobile/css/app_mobile_header.css');

mix.combine([
    'resources/mobile/js/jquery-3.5.1.min.js',
    'resources/mobile/js/jquery-ui.js',
], 'public/themes/mobile/js/app_mobile_header.js').version();
mix.minify('public/themes/mobile/js/app_mobile_header.js');

mix.combine([
    'resources/mobile/js/popper.min.js',
    'resources/mobile/vendor/bootstrap-table/bootstrap-table.min.js',
    'resources/mobile/vendor/bootstrap-table/bootstrap-table-vi-VN.js',
    'resources/mobile/js/LoadBootstrapTable.js',
    'resources/mobile/js/load-ajax.js',
    'resources/mobile/js/form-ajax.js',
    'resources/mobile/js/moment.min.js',
    'resources/mobile/vendor/bootstrap-4.4.1/js/bootstrap.min.js',
    'resources/mobile/vendor/sweetalert2/sweetalert2.js',
    'resources/mobile/vendor/fullcalendar/main.js',
    'resources/mobile/vendor/select2/js/select2.min.js',
    'resources/mobile/vendor/OwlCarousel/owl.carousel.min.js',
    'resources/mobile/vendor/swiper/js/swiper.min.js',
    'resources/mobile/vendor/cookie/jquery.cookie.js',
    // 'resources/mobile/vendor/emojionearea/js/emojionearea.min.js',
    'resources/mobile/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
    'resources/mobile/vendor/bootstrap-datetimepicker/js/load-datetimepicker.js',
    'resources/mobile/js/load-select2.js',
    'resources/mobile/js/main.js',
    'resources/mobile/js/dropzone.js',
    'resources/mobile/js/footer.js',
], 'public/themes/mobile/js/app_mobile_footer.js').version();
mix.minify('public/themes/mobile/js/app_mobile_footer.js');

mix.combine([
    'resources/mobile/vendor/materializeicon/material-icons.css',
    'resources/mobile/vendor/bootstrap-4.4.1/css/bootstrap.min.css',
    'resources/mobile/vendor/swiper/css/swiper.min.css',
    'resources/mobile/css/style.css',
    'public/css/theme_dark.css',
], 'public/themes/mobile/css/search_mobile_header.css').version();
mix.minify('public/themes/mobile/css/search_mobile_header.css');

mix.combine([
    'resources/mobile/js/jquery-3.5.1.min.js',
    'resources/mobile/js/popper.min.js',
    'resources/mobile/vendor/bootstrap-4.4.1/js/bootstrap.min.js',
    'resources/mobile/vendor/swiper/js/swiper.min.js',
    'resources/mobile/vendor/cookie/jquery.cookie.js',
    'resources/mobile/js/main.js',
], 'public/themes/mobile/js/search_mobile_footer.js').version();
mix.minify('public/themes/mobile/js/search_mobile_footer.js');

mix.combine([
    'resources/mobile/vendor/materializeicon/material-icons.css',
    'resources/mobile/vendor/bootstrap-4.4.1/css/bootstrap.min.css',
    'resources/mobile/vendor/swiper/css/swiper.min.css',
    'resources/mobile/vendor/select2/css/select2.min.css',
    'resources/mobile/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
    'resources/mobile/css/style.css',
    'resources/mobile/css/custom.css',
    'public/css/theme_dark.css',
], 'public/themes/mobile/css/login_mobile_header.css').version();
mix.minify('public/themes/mobile/css/login_mobile_header.css');

mix.combine([
    'resources/mobile/js/jquery-3.5.1.min.js',
    'resources/mobile/js/popper.min.js',
    'resources/mobile/js/load-ajax.js',
    'resources/mobile/js/form-ajax.js',
    'resources/mobile/js/moment.min.js',
    'resources/mobile/vendor/bootstrap-4.4.1/js/bootstrap.min.js',
    'resources/mobile/vendor/sweetalert2/sweetalert2.js',
    'resources/mobile/vendor/swiper/js/swiper.min.js',
    'resources/mobile/vendor/cookie/jquery.cookie.js',
    'resources/mobile/vendor/jquery-validate/jquery.validate.min.js',
    'resources/mobile/vendor/select2/js/select2.min.js',
    'resources/mobile/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
    'resources/mobile/js/main.js',
], 'public/themes/mobile/js/login_mobile_footer.js').version();
mix.minify('public/themes/mobile/js/login_mobile_footer.js');

mix.combine([
    'resources/mobile/vendor/materializeicon/material-icons.css',
    'resources/mobile/vendor/bootstrap-4.4.1/css/bootstrap.min.css',
    'resources/mobile/vendor/swiper/css/swiper.min.css',
    'resources/mobile/css/style.css',
    'public/css/theme_dark.css',
], 'public/themes/mobile/css/search_daily_training_mobile_header.css').version();
mix.minify('public/themes/mobile/css/search_daily_training_mobile_header.css');

mix.combine([
    'resources/mobile/js/jquery-3.5.1.min.js',
    'resources/mobile/js/popper.min.js',
    'resources/mobile/vendor/bootstrap-4.4.1/js/bootstrap.min.js',
    'resources/mobile/vendor/swiper/js/swiper.min.js',
    'resources/mobile/vendor/cookie/jquery.cookie.js',
    'resources/mobile/js/main.js',
], 'public/themes/mobile/js/search_daily_training_mobile_footer.js').version();
mix.minify('public/themes/mobile/js/search_daily_training_mobile_footer.js');

mix.combine([
    'resources/mobile/vendor/materializeicon/material-icons.css',
    'resources/mobile/vendor/bootstrap-4.4.1/css/bootstrap.min.css',
    'resources/mobile/vendor/swiper/css/swiper.min.css',
    'resources/mobile/vendor/OwlCarousel/assets/owl.carousel.min.css',
    'resources/mobile/vendor/OwlCarousel/assets/owl.theme.default.min.css',
    'resources/mobile/vendor/select2/css/select2.min.css',
    'resources/mobile/vendor/fullcalendar/main.css',
    'resources/mobile/vendor/bootstrap-table/bootstrap-table.min.css',
    'resources/mobile/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
    // 'resources/mobile/vendor/emojionearea/css/emojionearea.min.css',
    'resources/mobile/css/style.css',
    'resources/mobile/css/custom.css',
    'resources/mobile/css/dropzone.css',
    'public/css/theme_dark.css',
], 'public/themes/mobile/css/goquiz_mobile_header.css').version();
mix.minify('public/themes/mobile/css/goquiz_mobile_header.css');

mix.combine([
    'resources/mobile/js/jquery-3.5.1.min.js',
    'resources/mobile/js/jquery-ui.js',
], 'public/themes/mobile/js/goquiz_mobile_header.js').version();
mix.minify('public/themes/mobile/js/goquiz_mobile_header.js');

mix.combine([
    'resources/mobile/js/popper.min.js',
    'resources/mobile/vendor/bootstrap-table/bootstrap-table.min.js',
    'resources/mobile/vendor/bootstrap-table/bootstrap-table-vi-VN.js',
    'resources/mobile/js/LoadBootstrapTable.js',
    'resources/mobile/js/load-ajax.js',
    'resources/mobile/js/form-ajax.js',
    'resources/mobile/js/moment.min.js',
    'resources/mobile/vendor/bootstrap-4.4.1/js/bootstrap.min.js',
    'resources/mobile/vendor/sweetalert2/sweetalert2.js',
    'resources/mobile/vendor/fullcalendar/main.js',
    'resources/mobile/vendor/select2/js/select2.min.js',
    'resources/mobile/vendor/OwlCarousel/owl.carousel.min.js',
    'resources/mobile/vendor/swiper/js/swiper.min.js',
    'resources/mobile/vendor/cookie/jquery.cookie.js',
    // 'resources/mobile/vendor/emojionearea/js/emojionearea.min.js',
    'resources/mobile/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
    'resources/mobile/vendor/bootstrap-datetimepicker/js/load-datetimepicker.js',
    'resources/mobile/js/load-select2.js',
    'resources/mobile/js/main.js',
    'resources/mobile/js/dropzone.js',
], 'public/themes/mobile/js/goquiz_mobile_footer.js').version();
mix.minify('public/themes/mobile/js/goquiz_mobile_footer.js');

mix.combine([
    'public/styles/module/quiz/css/doquiz.css',
], 'public/styles/module/quiz/css/doquiz.css').version();
mix.minify('public/styles/module/quiz/css/doquiz.css');

mix.combine([
    'resources/styles/module/quiz/js/doquiz.js',
], 'public/styles/module/quiz/js/doquiz.js').version();
mix.minify('public/styles/module/quiz/js/doquiz.js');

mix.js('resources/styles/js/bootstrap-select.min.js','public/js');
mix.css('resources/styles/css/bootstrap-select.min.css','public/css');
/************************/
