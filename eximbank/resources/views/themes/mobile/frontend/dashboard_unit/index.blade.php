{{--  @extends('themes.mobile.layouts.app')

@section('page_title', trans('app.dashboard'))
@section('header')
    <script src="{{asset('styles/vendor/chart/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/vendor/chart/plugin/chartjs-plugin-labels.min.js')}}" type="text/javascript"></script>
@endsection
@section('content')  --}}
    <div class="container wrapped_info">
        <div class="row">
            <div class="col-12 p">
                Dữ liệu được cập nhật mới nhất hàng ngày. <br>
                Dữ liệu hôm nay sẽ được cập nhật vào sáng mai. <br>
                Bấm nút <b>"Cập nhật"</b> để cập nhật dữ liệu mới nhất hiện tại.
            </div>
            <div class="col-12">
                <button type="button" id="sync_data_dashboard_unit" class="btn bg-template"><i class="material-icons">sync</i></button>
            </div>
        </div>
        {{--  Biểu đồ tròn khoá online  --}}
        <div class="mt-2">
            <div class="card shadow overflow-hidden" data-scroll-height="400">
                <div class="card-header row m-0 d_flex_align p-0">
                    <div class="col-10 p-1">
                        <h6 class="font-weight-bold">{{ trans('lamenu.online_course') }}</h6>
                    </div>
                    <div class="col-2 p-1">
                        <div class="btn-group float-right">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle bg-template" type="button" id="dropdownMenuButtonOnline" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonOnline">
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(1, 'model_new')">
                                        Khoá học mới nhất
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(1, 'week_now')">
                                        Tuần hiện tại
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(1, 'month_now')">
                                        Tháng hiện tại
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(1, 'year_now')">
                                        Năm hiện tại
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#modalOnline">
                                        Tuỳ chọn
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 wrapped_course_name_1">
                            <h6 class="course_name course_name_chart mb-0 ml-1">{{ $courseOnlineNameData }}</h6>
                            <h6 class="mb-0 course_name_1 course_name_chart"></h6>
                        </div>
                        <div class="col-12 p-3">
                            <canvas id="chart_course_online" style="height:100px"></canvas>
                            <div class="draw_canvas_1">

                            </div>
                        </div>
                        <div class="col-12">
                            <form name="frm" action="" id="form-search-online" method="post" autocomplete="off">
                                <input type="hidden" name="model_id" id="model_id_1" value="">
                                <input type="hidden" name="year_course" id="model_year_course_1" value="">
                                <input type="hidden" name="month_course" id="model_month_course_1" value="">
                                <input type="hidden" name="status_course" id="model_status_course_1" value="">
                                <input type="hidden" name="filter_name" id="model_filter_name_1" value="">
                                <input type="hidden" name="data_model" id="data_model_1" value="{{ implode(',', $dataCourseOnline) }}">
                            </form>
                            <div id="user_course_1" class="float-right text-info">
                                Xem chi tiết <i class="fa fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--  Biểu đồ tròn khoá offline  --}}
        <div class="mt-2">
            <div class="card shadow overflow-hidden" data-scroll-height="400">
                <div class="card-header row m-0 d_flex_align p-0">
                    <div class="col-10 p-1">
                        <h6 class="font-weight-bold">{{ trans('lamenu.offline_course') }}</h6>
                    </div>
                    <div class="col-2 p-1">
                        <div class="btn-group float-right">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle bg-template" type="button" id="dropdownMenuButtonOffline" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonOffline">
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(2, 'model_new')">
                                        Khoá học mới nhất
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(2, 'week_now')">
                                        Tuần hiện tại
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(2, 'month_now')">
                                        Tháng hiện tại
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(2, 'year_now')">
                                        Năm hiện tại
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#modalOffline">
                                        Tuỳ chọn
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 wrapped_course_name_2">
                            <h6 class="course_name course_name_chart mb-0 ml-1">{{ $courseOfflineNameData }}</h6>
                            <h6 class="mb-0 course_name_2 course_name_chart"></h6>
                        </div>
                        <div class="col-12 p-3">
                            <canvas id="chart_course_offline" style="height:100px"></canvas>
                            <div class="draw_canvas_2">

                            </div>
                        </div>
                        <div class="col-12">
                            <form name="frm" action="" id="form-search-offline" method="post" autocomplete="off">
                                <input type="hidden" name="model_id" id="model_id_2" value="">
                                <input type="hidden" name="year_course" id="model_year_course_2" value="">
                                <input type="hidden" name="month_course" id="model_month_course_2" value="">
                                <input type="hidden" name="status_course" id="model_status_course_2" value="">
                                <input type="hidden" name="filter_name" id="model_filter_name_2" value="">
                                <input type="hidden" name="data_model" id="data_model_2" value="{{ implode(',', $dataCourseOffline) }}">
                            </form>
                            <div id="user_course_2" class="float-right text-info">
                                Xem chi tiết <i class="fa fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--  Biểu đồ tròn kỳ thi  --}}
        <div class="mt-2">
            <div class="card shadow overflow-hidden" data-scroll-height="400">
                <div class="card-header row m-0 d_flex_align p-0">
                    <div class="col-10 p-1">
                        <h6 class="font-weight-bold">{{ trans('lacategory.quiz') }}</h6>
                    </div>
                    <div class="col-2 p-1">
                        <div class="btn-group float-right">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle bg-template" type="button" id="dropdownMenuButtonQuiz" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonQuiz">
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(3, 'model_new')">
                                        Kỳ thi mới nhất
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(3, 'week_now')">
                                        Tuần hiện tại
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(3, 'month_now')">
                                        Tháng hiện tại
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="selectCourse(3, 'year_now')">
                                        Năm hiện tại
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#modalQuiz">
                                        Tuỳ chọn
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 wrapped_course_name_3">
                            <h6 class="course_name course_name_chart mb-0 ml-1">{{ $quizNameData }}</h6>
                            <h6 class="mb-0 course_name_3 course_name_chart"></h6>
                        </div>
                        <div class="col-12 p-3">
                            <canvas id="chart_quiz" style="height:100px"></canvas>
                            <div class="draw_canvas_3">

                            </div>
                        </div>
                        <div class="col-12">
                            <form name="frm" action="" id="form-search-quiz" method="post" autocomplete="off">
                                <input type="hidden" name="model_id" id="model_id_3" value="">
                                <input type="hidden" name="year_course" id="model_year_course_3" value="">
                                <input type="hidden" name="month_course" id="model_month_course_3" value="">
                                <input type="hidden" name="status_course" id="model_status_course_3" value="">
                                <input type="hidden" name="filter_name" id="model_filter_name_3" value="">
                                <input type="hidden" name="data_model" id="data_model_3" value="{{ implode(',', $dataQuiz) }}">
                            </form>
                            <div id="user_course_3" class="float-right text-info">
                                Xem chi tiết <i class="fa fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="element_data"
        data-data_course_online = {{ implode(',', $dataCourseOnline) }}
        data-data_course_offline = {{ implode(',', $dataCourseOffline) }}
        data-data_quiz = {{ implode(',', $dataQuiz) }}
    ></div>
{{--  @endsection

@section('footer')
    <script type="text/javascript">
        var swiper = new Swiper('.unit-manager-slide', {
            slidesPerView: 2,
            spaceBetween: 0,
        });

        function info_formatter(value, row, index) {
            return (index + 1) +'./ '+ row.full_name + '<br>' + row.code + '<br>' + row.unit_name + '<br>' + row.model_info;
        }
        function status_formatter(value, row, index){
            return '<span class="p-2 '+row.bg_color+'">'+ row.percent +'</span>';
        }

        var table_1 = new LoadBootstrapTable({
            table: '#bootstraptable_1',
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable_1').data('url'),
            form_search: '#form-search-online',
        });
        $('#user_course_1').on('click',function (e) {
            e.preventDefault();

            if($('#bootstraptableOnline').hasClass('show')) {
                $('#bootstraptableOnline').removeClass('show');
                $('#bootstraptableOnline').addClass('hide');
                $('#user_course_1').html('<i class="fa fa-arrow-down"></i>');
            } else {
                $('#bootstraptableOnline').removeClass('hide');
                $('#bootstraptableOnline').addClass('show');
                $('#user_course_1').html('<i class="fa fa-arrow-up"></i>');
            }
        });

        var table_2 = new LoadBootstrapTable({
            table: '#bootstraptable_2',
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable_2').data('url'),
            form_search: '#form-search-offline',
        });

        $('#user_course_2').on('click',function (e) {
            e.preventDefault();

            if($('#bootstraptableOffline').hasClass('show')) {
                $('#bootstraptableOffline').removeClass('show');
                $('#bootstraptableOffline').addClass('hide');
                $('#user_course_2').html('<i class="fa fa-arrow-down"></i>');
            } else {
                $('#bootstraptableOffline').removeClass('hide');
                $('#bootstraptableOffline').addClass('show');
                $('#user_course_2').html('<i class="fa fa-arrow-up"></i>');
            }
        });

        var table_3 = new LoadBootstrapTable({
            table: '#bootstraptable_3',
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable_3').data('url'),
            form_search: '#form-search-quiz',
        });

        $('#user_course_3').on('click',function (e) {
            e.preventDefault();

            if($('#bootstraptableQuiz').hasClass('show')) {
                $('#bootstraptableQuiz').removeClass('show');
                $('#bootstraptableQuiz').addClass('hide');
                $('#user_course_3').html('<i class="fa fa-arrow-down"></i>');
            } else {
                $('#bootstraptableQuiz').removeClass('hide');
                $('#bootstraptableQuiz').addClass('show');
                $('#user_course_3').html('<i class="fa fa-arrow-up"></i>');
            }
        });

        var colorLabel = '#333';

        var get_data_data_course_online = $('.element_data').attr('data-data_course_online');
        var get_data_data_course_online_array = get_data_data_course_online.split(",");

        var get_data_data_course_offline = $('.element_data').attr('data-data_course_offline');
        var get_data_data_course_offline_array = get_data_data_course_offline.split(",");

        var get_data_data_quiz = $('.element_data').attr('data-data_quiz');
        var get_data_data_quiz_array = get_data_data_quiz.split(",");

        var chart_course_online = document.getElementById("chart_course_online").getContext('2d');
        var data_chart_course_online = {
            labels: ['{{ trans("laother.studying") }}', '{{ trans("ladashboard.not_learned") }}', '{{ trans("latraining.completed") }}', '{{ trans("ladashboard.uncomplete") }}'],
            datasets: [{
                backgroundColor: [
                    '#f05555b4',
                    "#ebdf0393",
                    "#74d30886",
                    "#63aaf188",
                ],
                fill: false,
                data: get_data_data_course_online_array,
            }]
        };
        var options_chart_course_online = {
            legend: {
                labels: {
                    fontColor: colorLabel,
                    fontSize: 11,
                },
                display: true,
                position: 'bottom',

            },
            showTooltips: true,
            plugins: {
                labels: {
                    render: (args) => {
                        return args.value
                    }
                }
            }
        };
        var chartCourseOnline = new Chart(chart_course_online, {
            type: 'pie',
            data: data_chart_course_online,
            options: options_chart_course_online
        });

        var chart_course_offline = document.getElementById("chart_course_offline").getContext('2d');
        var data_chart_course_offline = {
            labels: ['{{ trans("laother.studying") }}', '{{ trans("ladashboard.not_learned") }}', '{{ trans("latraining.completed") }}', '{{ trans("ladashboard.uncomplete") }}'],
            datasets: [{
                backgroundColor: [
                    '#f05555b4',
                    "#ebdf0393",
                    "#74d30886",
                    "#63aaf188",
                ],
                fill: false,
                data: get_data_data_course_offline_array,
            }]
        };
        var options_chart_course_offline = {
            responsive: true,
            legend: {
                labels: {
                    fontColor: colorLabel,
                    fontSize: 11
                },
                display: true,
                position: 'bottom',
            },
            showTooltips: true,
            plugins: {
                labels: {
                    render: (args) => {
                        return args.value
                    }
                }
            }
        };
        var chartCourseOffline = new Chart(chart_course_offline, {
            type: 'pie',
            data: data_chart_course_offline,
            options: options_chart_course_offline
        });

        var chart_quiz = document.getElementById("chart_quiz").getContext('2d');
        var data_chart_quiz = {
            labels: ['Chưa thi', '{{ trans("latraining.completed") }}', '{{ trans("ladashboard.uncomplete") }}'],
            datasets: [{
                backgroundColor: [
                    "#ebdf0393",
                    "#74d30886",
                    "#63aaf188",
                ],
                fill: false,
                data: get_data_data_quiz_array,
            }]
        };
        var options_chart_quiz = {
            responsive: true,
            legend: {
                labels: {
                    fontColor: colorLabel,
                    fontSize: 11
                },
                display: true,
                position: 'bottom',
            },
            showTooltips: true,
            plugins: {
                labels: {
                    render: (args) => {
                        return args.value
                    }
                }
            }
        };
        var chartQuiz = new Chart(chart_quiz, {
            type: 'pie',
            data: data_chart_quiz,
            options: options_chart_quiz
        });

        function selectCourse(type, filter_name=null) {
            if(type == 1) {
                $('#chart_course_online').hide();
                $('#bootstraptableOnline').removeClass('show');
                $('#bootstraptableOnline').addClass('hide');
                $('#user_course_1').html('<i class="fa fa-arrow-down"></i>');
            } else if(type == 2) {
                $('#chart_course_offline').hide();
                $('#bootstraptableOffline').removeClass('show');
                $('#bootstraptableOffline').addClass('hide');
                $('#user_course_2').html('<i class="fa fa-arrow-down"></i>');
            } else {
                $('#chart_quiz').hide();
                $('#bootstraptableQuiz').removeClass('show');
                $('#bootstraptableQuiz').addClass('hide');
                $('#user_course_3').html('<i class="fa fa-arrow-down"></i>');
            }

            $('.wrapped_course_name_'+ type).find('.course_name').hide();
            $('.draw_canvas_'+ type).html('');
            $('.draw_canvas_'+ type).append('<canvas id="chart_course_'+ type +'" style="height:100px"></canvas>');

            if(filter_name){
                var model_id = null;
                var year_course = null;
                var month_course = null;
                var status_course = null;
            }else{
                var model_id = $('#course_'+type).select2('val');
                var year_course = $('#year_course_'+type).select2('val');
                var month_course = $('#month_course_'+type).select2('val');
                var status_course = $('#status_course_'+type).select2('val');
            }

            $('#model_id_'+type).val(model_id);
            $('#model_year_course_'+type).val(year_course);
            $('#model_month_course_'+type).val(month_course);
            $('#model_status_course_'+type).val(status_course);
            $('#model_filter_name_'+type).val(filter_name);

            $.ajax({
                type: "POST",
                url: "{{ route('themes.mobile.frontend.dashboard_unit.search_course') }}",
                data: {
                    type: type,
                    model_id: model_id,
                    year_course: year_course,
                    month_course: month_course,
                    status_course: status_course,
                    filter_name: filter_name,
                },
                success: function (result) {
                    if (result.checkSearch == 0) {
                        if(type == 1) {
                            $('#chart_course_online').show();
                        } else if(type == 2) {
                            $('#chart_course_offline').show();
                        } else {
                            $('#chart_quiz').show();
                        }

                        $('.wrapped_course_name_'+ type).find('.course_name').show();
                        $('.draw_canvas_'+ type).html('');
                        $('.course_name_'+ type).html('');
                    } else {
                        $('.course_name_'+ type).html(result.courseName);
                        var chart_course_search = document.getElementById("chart_course_"+ type);

                        if(type == 3){
                            var data_chart_course_search = {
                                labels: [
                                    'Chưa thi',
                                    '{{ trans("latraining.completed") }}',
                                    '{{ trans("ladashboard.uncomplete") }}'
                                ],
                                datasets: [{
                                    backgroundColor: [
                                        "#ebdf0393",
                                        "#74d30886",
                                        "#63aaf188",
                                    ],
                                    fill: false,
                                    data: result.data,
                                }]
                            };
                        }else{
                            var data_chart_course_search = {
                                labels: [
                                    '{{ trans("laother.studying") }}',
                                    '{{ trans("ladashboard.not_learned") }}',
                                    '{{ trans("latraining.completed") }}',
                                    '{{ trans("ladashboard.uncomplete") }}'
                                ],
                                datasets: [{
                                    backgroundColor: [
                                        '#f05555b4',
                                        "#ebdf0393",
                                        "#74d30886",
                                        "#63aaf188",
                                    ],
                                    fill: false,
                                    data: result.data,
                                }]
                            };
                        }

                        var options_chart_course_search = {
                            responsive: true,
                            legend: {
                                labels: {
                                    fontColor: colorLabel,
                                    fontSize: 11
                                },
                                display: true,
                                position: 'bottom',
                            },
                            showTooltips: true,
                            plugins: {
                                labels: {
                                    render: (args) => {
                                        return args.value
                                    }
                                }
                            }
                        };
                        var chartCourseNewBySearch = new Chart(chart_course_search, {
                            type: 'pie',
                            data: data_chart_course_search,
                            options: options_chart_course_search
                        })
                    }

                    if(type == 1) {
                        table_1.refresh();
                    } else if(type == 2) {
                        table_2.refresh();
                    } else {
                        table_3.refresh();
                    }
                }
            });
        }
    </script>
@endsection  --}}
