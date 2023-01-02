@extends('layouts.backend')

@section('page_title', trans('lamenu.dashboard'))
@section('header')
    <script src="{{asset('styles/vendor/chart/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/js/google_chart.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/vendor/jqueryplugin/jquery.knob.min.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{ asset('styles/vendor/ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/module/dashboard/css/dashboard.css') }}">
    <script src="{{asset('styles/vendor/chart/plugin/chartjs-plugin-labels.min.js')}}" type="text/javascript"></script>
    <style>
        .select2-container--default .select2-selection--single,
        .select2-selection .select2-selection--single{
            padding: 2px 12px;
        }

        .small-box .inner{
            background-size: contain;
        }
        .wrapped_chart_course canvas {
            max-height: 400px;
            margin: auto;
        }
        .course_name_chart {
            color: #17a2b8 !important;
        }

        .studying {
            background: #f05555b4;
            color: white;
        }
        .not_learned {
            background: #ebdf0393;
            color: white;
        }
        .completed {
            background: #74d30886;
            color: white;
        }
        .uncomplete {
            background: #63aaf188;
            color: white;
        }

        #bootstraptableOnline .bootstrap-table .fixed-table-container .fixed-table-body,
        #bootstraptableOffline .bootstrap-table .fixed-table-container .fixed-table-body,
        #bootstraptableQuiz .bootstrap-table .fixed-table-container .fixed-table-body
        {
            max-height: 400px !important;
        }
        .bootstrap-table .fixed-table-container .table thead th .th-inner {
            padding: 0.6rem !important;
        }
        .table > thead > tr > th {
            font-weight: unset;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col pr-0 col-xs-4">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/elearning_dashboard_detail.png')}}) no-repeat center;">
                    <div class="w-50 text-center">
                        <div class="mb-2">@lang('lacategory.onl_course')</div>
                        <h3>{{ $total_online }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col pr-0 col-xs-4">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/offline_dashboard_detail.png')}}) no-repeat center;">
                    <div class="w-50 text-center">
                        <div class="mb-2">@lang('ladashboard.offline')</div>
                        <h3>{{ $total_offline }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col pr-0 col-xs-4">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/quiz_part_dashboard_detail.png')}}) no-repeat center;">
                    <div class="w-50 text-center">
                        <div class="mb-2">@lang('ladashboard.quiz')</div>
                        <h3>{{ $total_quiz }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 h5">
            Dữ liệu được cập nhật mới nhất hàng ngày.
            Dữ liệu hôm nay sẽ được cập nhật vào sáng mai.
            Bấm nút <b>"Cập nhật"</b> để cập nhật dữ liệu mới nhất hiện tại.
            <button type="button" id="sync_data_dashboard_unit" class="btn"><i class="fa fa-sync"></i></button>
        </div>
        <div class="col-12 mb-3 wrapped_chart_course">
            <div class="shadow-sm mb-3 row mt-2">
                <div class="col-6">
                    <div class="card card-default analysis_card p-0" data-scroll-height="400">
                        <div class="card-header row m-0 p-0 d_flex_align">
                            <div class="col-7 p-1">
                                Trình trạng nhân viên - {{ trans('lacategory.onl_course') }} <span id="total_model_1">({{ $courseOnlineTotal }})</span>
                            </div>
                            <div class="col-5 p-1">
                                <div class="btn-group float-right">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButtonOnline" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonOnline">
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
                                    <h5 class="course_name course_name_chart mb-0 ml-1">{{ $courseOnlineNameData }}</h5>
                                    <h5 class="mb-0 course_name_1 course_name_chart"></h5>
                                </div>
                                <div class="col-12 p-3">
                                    <canvas id="chart_course_online" style="height:100px"></canvas>
                                    <div class="draw_canvas_1">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <form name="frm" action="" id="form-search-online" method="post" autocomplete="off">
                        <input type="hidden" name="model_id" id="model_id_1" value="">
                        <input type="hidden" name="year_course" id="model_year_course_1" value="">
                        <input type="hidden" name="month_course" id="model_month_course_1" value="">
                        <input type="hidden" name="status_course" id="model_status_course_1" value="">
                        <input type="hidden" name="filter_name" id="model_filter_name_1" value="">
                    </form>
                    <div id="bootstraptableOnline">
                        <table id="bootstraptable_1" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{ route('module.dashboard_unit.data_user_online') }}" >
                            <thead>
                                <tr class="tbl-heading">
                                    <th data-formatter="info_formatter">Kết quả học viên</th>
                                    <th data-formatter="status_formatter" data-align="center" data-width="5%">{{ trans('app.status') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="shadow-sm mb-3 row mt-2">
                <div class="col-6">
                    <div class="card card-default analysis_card p-0" data-scroll-height="400">
                        <div class="card-header row m-0 p-0 d_flex_align">
                            <div class="col-7 p-1">
                                Trình trạng nhân viên - {{ trans('lacategory.off_course') }} <span id="total_model_2">({{ $courseOfflineTotal }})</span>
                            </div>
                            <div class="col-5 p-1">
                                <div class="btn-group float-right">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButtonOffline" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonOffline">
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
                                    <h5 class="course_name course_name_chart mb-0 ml-1">{{ $courseOfflineNameData }}</h5>
                                    <h5 class="mb-0 course_name_2 course_name_chart"></h5>
                                </div>
                                <div class="col-12 p-3">
                                    <canvas id="chart_course_offline" style="height:100px"></canvas>
                                    <div class="draw_canvas_2">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <form name="frm" action="" id="form-search-offline" method="post" autocomplete="off">
                        <input type="hidden" name="model_id" id="model_id_2" value="">
                        <input type="hidden" name="year_course" id="model_year_course_2" value="">
                        <input type="hidden" name="month_course" id="model_month_course_2" value="">
                        <input type="hidden" name="status_course" id="model_status_course_2" value="">
                        <input type="hidden" name="filter_name" id="model_filter_name_2" value="">
                    </form>
                    <div id="bootstraptableOffline">
                        <table id="bootstraptable_2" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{ route('module.dashboard_unit.data_user_offline') }}" >
                            <thead>
                                <tr class="tbl-heading">
                                    <th data-formatter="info_formatter">Kết quả học viên</th>
                                    <th data-formatter="status_formatter" data-align="center" data-width="5%">{{ trans('app.status') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="shadow-sm mb-3 row mt-2">
                <div class="col-6">
                    <div class="card card-default analysis_card p-0" data-scroll-height="400">
                        <div class="card-header row m-0 p-0 d_flex_align">
                            <div class="col-7 p-1">
                                Trình trạng nhân viên - {{ trans('lacategory.quiz') }} <span id="total_model_3">({{ $quizTotal }})</span>
                            </div>
                            <div class="col-5 p-1">
                                <div class="btn-group float-right">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButtonQuiz" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonQuiz">
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
                                    <h5 class="course_name course_name_chart mb-0 ml-1">{{ $quizNameData }}</h5>
                                    <h5 class="mb-0 course_name_3 course_name_chart"></h5>
                                </div>
                                <div class="col-12 p-3">
                                    <canvas id="chart_quiz" style="height:100px"></canvas>
                                    <div class="draw_canvas_3">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <form name="frm" action="" id="form-search-quiz" method="post" autocomplete="off">
                        <input type="hidden" name="model_id" id="model_id_3" value="">
                        <input type="hidden" name="year_course" id="model_year_course_3" value="">
                        <input type="hidden" name="month_course" id="model_month_course_3" value="">
                        <input type="hidden" name="status_course" id="model_status_course_3" value="">
                        <input type="hidden" name="filter_name" id="model_filter_name_3" value="">
                    </form>
                    <div id="bootstraptableQuiz">
                        <table id="bootstraptable_3" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{ route('module.dashboard_unit.data_user_quiz') }}" >
                            <thead>
                                <tr class="tbl-heading">
                                    <th data-formatter="info_formatter">Kết quả học viên</th>
                                    <th data-formatter="status_formatter" data-align="center" data-width="5%">{{ trans('app.status') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tuỳ chọn khoá online -->
    <div class="modal fade" id="modalOnline" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tuỳ chọn</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.course') }}</div>
                        <div class="col-9">
                            <select id="course_1" class="load-courses-unit" data-type="1" data-placeholder="{{ trans('lacategory.onl_course') }}" onchange="selectCourse(1)">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.year') }}</div>
                        <div class="col-9">
                            <select id="year_course_1" class="form-control select2" data-placeholder="{{ trans('app.year') }}" onchange="selectCourse(1)">
                                <option value=""></option>
                                @for ($year = date('Y'); $year <= (date('Y') + 1); $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.month') }}</div>
                        <div class="col-9">
                            <select id="month_course_1" class="form-control select2" data-placeholder="{{ trans('app.month') }}" onchange="selectCourse(1)">
                                <option value=""></option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ $month }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.status') }}</div>
                        <div class="col-9">
                            <select id="status_course_1" class="form-control select2" data-placeholder="{{ trans('app.status') }}" onchange="selectCourse(1)">
                                <option value=""></option>
                                <option value="studying">{{ trans("laother.studying") }}</option>
                                <option value="unlearned">{{ trans("ladashboard.not_learned") }}</option>
                                <option value="completed">{{ trans("latraining.completed") }}</option>
                                <option value="uncompleted">{{ trans("ladashboard.uncomplete") }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tuỳ chọn khoá offline -->
    <div class="modal fade" id="modalOffline" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tuỳ chọn</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.course') }}</div>
                        <div class="col-9">
                            <select id="course_2" class="load-courses-unit" data-type="2" data-placeholder="{{ trans('lacategory.off_course') }}" onchange="selectCourse(2)">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.year') }}</div>
                        <div class="col-9">
                            <select id="year_course_2" class="form-control select2" data-placeholder="{{ trans('app.year') }}" onchange="selectCourse(2)">
                                <option value=""></option>
                                @for ($year = date('Y'); $year <= (date('Y') + 1); $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.month') }}</div>
                        <div class="col-9">
                            <select id="month_course_2" class="form-control select2" data-placeholder="{{ trans('app.month') }}" onchange="selectCourse(2)">
                                <option value=""></option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ $month }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.status') }}</div>
                        <div class="col-9">
                            <select id="status_course_2" class="form-control select2" data-placeholder="{{ trans('app.status') }}" onchange="selectCourse(2)">
                                <option value=""></option>
                                <option value="studying">{{ trans("laother.studying") }}</option>
                                <option value="unlearned">{{ trans("ladashboard.not_learned") }}</option>
                                <option value="completed">{{ trans("latraining.completed") }}</option>
                                <option value="uncompleted">{{ trans("ladashboard.uncomplete") }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tuỳ chọn kỳ thi -->
    <div class="modal fade" id="modalQuiz" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tuỳ chọn</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-3">{{ trans('lamenu.quiz') }}</div>
                        <div class="col-9">
                            <select id="course_3" class="load-quizs-unit" data-type="3" data-placeholder="{{ trans('lacategory.quiz') }}" onchange="selectCourse(3)">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.year') }}</div>
                        <div class="col-9">
                            <select id="year_course_3" class="form-control select2" data-placeholder="{{ trans('app.year') }}" onchange="selectCourse(3)">
                                <option value=""></option>
                                @for ($year = date('Y'); $year <= (date('Y') + 1); $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.month') }}</div>
                        <div class="col-9">
                            <select id="month_course_3" class="form-control select2" data-placeholder="{{ trans('app.month') }}" onchange="selectCourse(3)">
                                <option value=""></option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ $month }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.status') }}</div>
                        <div class="col-9">
                            <select id="status_course_3" class="form-control select2" data-placeholder="{{ trans('app.status') }}" onchange="selectCourse(3)">
                                <option value=""></option>
                                <option value="unlearned">Chưa thi</option>
                                <option value="completed">{{ trans("latraining.completed") }}</option>
                                <option value="uncompleted">{{ trans("ladashboard.uncomplete") }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="element_data"
        data-user_unit = '{{ session()->get('user_unit') }}'
        data-url_choose_unit_modal = '{{ route('choose_unit_modal') }}'
        data-url_load_unit_modal = '{{ route('load_unit_modal') }}'
        data-data_course_online = {{ implode(',', $dataCourseOnline) }}
        data-data_course_offline = {{ implode(',', $dataCourseOffline) }}
        data-data_quiz = {{ implode(',', $dataQuiz) }}
    ></div>
    <script src="{{ mix('js/loadModalChooseUnit.js') }}" type="text/javascript"></script>
    <script>
        function info_formatter(value, row, index) {
            return (row.index + 1) +'./ '+ row.full_name + ' (' + row.code + ') <br> <span class="text-muted">' + row.unit_name + '<br>' + row.model_info + '</span>';
        }
        function status_formatter(value, row, index){
            return '<div style="height: 30px; justify-content: center;" class="w-100 d-flex align-items-center '+row.bg_color+'">'+ row.percent +'</div>';
        }

        var table_1 = new LoadBootstrapTable({
            table: '#bootstraptable_1',
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable_1').data('url'),
            form_search: '#form-search-online',
            paginationParts: ['pageInfoShort']
        });

        var table_2 = new LoadBootstrapTable({
            table: '#bootstraptable_2',
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable_2').data('url'),
            form_search: '#form-search-offline',
        });

        var table_3 = new LoadBootstrapTable({
            table: '#bootstraptable_3',
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable_3').data('url'),
            form_search: '#form-search-quiz',
        });

        function showListReport() {
            $('#modal-list-report').modal();
        }

        var get_data_data_course_online = $('.element_data').attr('data-data_course_online');
        var get_data_data_course_online_array = get_data_data_course_online.split(",");

        var get_data_data_course_offline = $('.element_data').attr('data-data_course_offline');
        var get_data_data_course_offline_array = get_data_data_course_offline.split(",");

        var get_data_data_quiz = $('.element_data').attr('data-data_quiz');
        var get_data_data_quiz_array = get_data_data_quiz.split(",");

        var colorLabel = "{{ (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 1 : 0 }}" == 1 ? '#dee2e6' : '#333';
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
                    fontSize: 17,
                },
                display: true,
                position: 'bottom',

            },
            showTooltips: true,
            plugins: {
                labels: {
                    render: 'value',
                    fontColor: '#fff',
                    fontSize: 20,
                }
            },
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
                    fontSize: 17
                },
                display: true,
                position: 'bottom',
            },
            showTooltips: true,
            plugins: {
                labels: {
                    render: 'value',
                    fontColor: '#fff',
                    fontSize: 20,
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
                    fontSize: 17
                },
                display: true,
                position: 'bottom',
            },
            showTooltips: true,
            plugins: {
                labels: {
                    render: 'value',
                    fontColor: '#fff',
                    fontSize: 20,
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
            } else if(type == 2) {
                $('#chart_course_offline').hide();
            } else {
                $('#chart_quiz').hide();
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
                url: "{{ route('module.dashboard_unit.search_course') }}",
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
                        $('#total_model_'+type).html('('+ result.total +')')
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
                                    fontSize: 17
                                },
                                display: true,
                                position: 'bottom',
                            },
                            showTooltips: true,
                            plugins: {
                                labels: {
                                    render: 'value',
                                    fontColor: '#fff',
                                    fontSize: 20,
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

        $('#sync_data_dashboard_unit').on('click', function(){
            var icon_old = $(this).html();
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                type: "POST",
                url: "{{ route('themes.mobile.frontend.dashboard_unit.sync_data') }}",
                data: {},
                success: function (result) {
                    $('#sync_data_dashboard_unit').html(icon_old);

                    Swal.fire({
                        'title': '',
                        'html': result.message,
                        'type': result.status,
                    });
                    return false;
                }
            });
        });
    </script>
@endsection

