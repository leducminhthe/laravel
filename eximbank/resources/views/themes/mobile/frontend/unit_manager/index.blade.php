@extends('themes.mobile.layouts.app')

@section('page_title', 'Quản lý đơn vị')

@section('header')
    <script src="{{asset('styles/vendor/chart/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/vendor/chart/plugin/chartjs-plugin-labels.min.js')}}" type="text/javascript"></script>
@endsection

@section('content')
    <style>
        #user-unit-top {
            font-size: 12px
        }
        #user-unit-top option{
            font-size: 10px
        }
        .wrapped_select_manager {
            position: fixed;
            bottom: 0px;
            background: #dee2e6;
            z-index: 10;
            padding: 10px;
        }
        .wrapped_select_manager .select2-selection__clear {
            display: none !important
        }
        .wrapped_select_manager .select2-container {
            margin-bottom: 5px;
        }
    </style>
    @php
        $tab_2 = Request::segment(2);
    @endphp
    <div class="container wrraped_unit_manager mb-2">
        <div class="row">
            <div class="col-12 px-0">
                <div class="swiper-container unit-manager-slide">
                    <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.dashboard_unit') }}', 0)" class="swiper-slide nav-link {{ ($tab_2 == 'dasboard-unit-mobile' ? 'active' : '') }}">
                            @lang('app.dashboard')
                        </a>
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile_unit') }}', 0)" class="swiper-slide nav-link {{ ($tab_2 == 'profile-unit-mobile' ? 'active' : '') }}">
                            @lang('lamenu.user')
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 px-0">
                <div class="unit_manager_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        @switch($tab_2)
                            @case('dasboard-unit-mobile')
                                @include('themes.mobile.frontend.dashboard_unit.index')
                                @break
                            @case('profile-unit-mobile')
                                @include('themes.mobile.frontend.profile_unit.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 wrapped_select_manager">
                <select class="form-control select2" name="user-unit" id="user-unit-top" data-minimum-results-for-search="Infinity" data-allowClear="false" role="button" data-url="{{ route('backend.save_select_unit')}}">
                    @foreach($userUnits as $index =>$item)
                        {{$selected = $item->id == session('user_unit') ? 'selected' : ''}}
                        <option value="{{$item->id}}" {{$selected}}>{{$item->name}}  - {{$item->code}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    @if ($tab_2 == 'dasboard-unit-mobile')
        <!-- Modal Tuỳ chọn khoá online -->
        <div class="modal fade" id="modalOnline" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Tuỳ chọn</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-12">
                                <select id="course_1" class="load-courses-unit" data-type="1" data-placeholder="{{ trans('lacategory.onl_course') }}">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <select id="year_course_1" class="form-control select2" data-placeholder="{{ trans('app.year') }}">
                                    <option value=""></option>
                                    @for ($year = date('Y'); $year <= (date('Y') + 1); $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <select id="month_course_1" class="form-control select2" data-placeholder="{{ trans('app.month') }}">
                                    <option value=""></option>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <option value="{{ $month }}">{{ $month }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <select id="status_course_1" class="form-control select2" data-placeholder="{{ trans('app.status') }}">
                                    <option value=""></option>
                                    <option value="studying">{{ trans("laother.studying") }}</option>
                                    <option value="unlearned">{{ trans("ladashboard.not_learned") }}</option>
                                    <option value="completed">{{ trans("latraining.completed") }}</option>
                                    <option value="uncompleted">{{ trans("ladashboard.uncomplete") }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default w-100 p-2" data-dismiss="modal" onclick="selectCourse(1)">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tuỳ chọn khoá offline -->
        <div class="modal fade" id="modalOffline" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Tuỳ chọn</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-3">{{ trans('app.course') }}</div>
                            <div class="col-9">
                                <select id="course_2" class="load-courses-unit" data-type="2" data-placeholder="{{ trans('lacategory.off_course') }}">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3">{{ trans('app.year') }}</div>
                            <div class="col-9">
                                <select id="year_course_2" class="form-control select2" data-placeholder="{{ trans('app.year') }}">
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
                                <select id="month_course_2" class="form-control select2" data-placeholder="{{ trans('app.month') }}">
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
                                <select id="status_course_2" class="form-control select2" data-placeholder="{{ trans('app.status') }}">
                                    <option value=""></option>
                                    <option value="studying">{{ trans("laother.studying") }}</option>
                                    <option value="unlearned">{{ trans("ladashboard.not_learned") }}</option>
                                    <option value="completed">{{ trans("latraining.completed") }}</option>
                                    <option value="uncompleted">{{ trans("ladashboard.uncomplete") }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default w-100 p-2" data-dismiss="modal" onclick="selectCourse(2)">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tuỳ chọn kỳ thi -->
        <div class="modal fade" id="modalQuiz" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Tuỳ chọn</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-3">{{ trans('lamenu.quiz') }}</div>
                            <div class="col-9">
                                <select id="course_3" class="load-quizs-unit" data-type="3" data-placeholder="{{ trans('lacategory.quiz') }}">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3">{{ trans('app.year') }}</div>
                            <div class="col-9">
                                <select id="year_course_3" class="form-control select2" data-placeholder="{{ trans('app.year') }}">
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
                                <select id="month_course_3" class="form-control select2" data-placeholder="{{ trans('app.month') }}">
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
                                <select id="status_course_3" class="form-control select2" data-placeholder="{{ trans('app.status') }}">
                                    <option value=""></option>
                                    <option value="unlearned">Chưa thi</option>
                                    <option value="completed">{{ trans("latraining.completed") }}</option>
                                    <option value="uncompleted">{{ trans("ladashboard.uncomplete") }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default w-100 p-2" data-dismiss="modal" onclick="selectCourse(3)">OK</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($tab_2 == 'profile-unit-mobile')
        <!-- Modal Tuỳ chọn Nhân viên đơn vị-->
        <div class="modal fade" id="modalFilterProfileUnit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="get" action="{{ route('themes.mobile.frontend.profile_unit') }}" id="form-search-profile-unit" class="input-group form-search border-0">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel">Tìm kiếm</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="search" class="form-control mb-1" placeholder="Mã/Tên nhân viên" value="{{ request()->get('search') }}">
                            <select name="unit" id="unit" class="load-unit form-control" data-level={{ $unit->level }} data-placeholder="Chọn đơn vị">
                                <option value=""></option>
                                @if (isset($request_unit))
                                    <option value="{{ $request_unit->id }}" selected> {{ $request_unit->code .' - '. $request_unit->name }}</option>
                                @endif
                            </select>
                            <div class="wrapped_search_title mt-1">
                                <select name="title_id" id="title_id" class="load-title form-control"  data-placeholder="Chọn chức danh">
                                    <option value=""></option>
                                    @if (isset($request_title_id))
                                        <option value="{{ $request_title_id->id }}" selected> {{ $request_title_id->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default w-100 p-2">OK</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
@section('footer')
    <script type="text/javascript">
        var swiper = new Swiper('.unit-manager-slide', {
            slidesPerView: 2,
            spaceBetween: 0,
        });
        if ('{{ $tab_2 }}' == 'dasboard-unit-mobile'){
            $("#user_course_1").on('click', function () {
                let form_search_online = $("#form-search-online").serialize();
                window.location = '{{ route('themes.mobile.frontend.dashboard_unit.user_online') }}?'+form_search_online;
            });
            $("#user_course_2").on('click', function () {
                let form_search_offline = $("#form-search-offline").serialize();
                window.location = '{{ route('themes.mobile.frontend.dashboard_unit.user_offline') }}?'+form_search_offline;
            });
            $("#user_course_3").on('click', function () {
                let form_search_quiz = $("#form-search-quiz").serialize();
                window.location = '{{ route('themes.mobile.frontend.dashboard_unit.user_quiz') }}?'+form_search_quiz;
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
                            $('#data_model_'+type).val(result.data_model);
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
        }
    </script>
@endsection
