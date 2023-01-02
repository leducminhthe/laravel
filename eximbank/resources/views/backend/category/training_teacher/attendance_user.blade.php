@extends('layouts.backend')

@section('page_title', trans('lamenu.attendance'))
@section('header')
    <script language="javascript" src="{{ asset('styles/module/qrcode/js/vue.min.js') }}"></script>
    <script language="javascript" src="{{ asset('styles/module/qrcode/js/vue-qrcode-reader.browser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/qrcode/css/vue-qrcode-reader.css') }}">
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
@endsection('header')

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.attendance'),
                'url' => route('backend.category.training_teacher.list_course')
            ],
            [
                'name' => $course->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <style>
        .left-slash{
            position: relative;
            width: 100%;
            height: 0;
            border-top: 2px solid red;
            transform: rotate(-30deg);
            top: 80px;
        }
        .right-slash{
            position: relative;
            width: 100%;
            height: 0;
            border-top: 2px solid red;
            transform: rotate(30deg);
            top: 80px;
        }
    </style>
    <div role="main" id="page_attendance_user_by_teacher">
        {{--  <h3>{{trans('latraining.attendance')}} : {{$offlineCourseClass->name}}</h3>  --}}
        <div class="wrapped_zoom float-right">
            <div id="zoom_out">
                <button type="button" class="btn"  onclick='exitFullScreen();'>
                    <h5><i class="fa fa-search-minus" style="font-size:16px"></i></h5>
                </button>
            </div>
            <div id="zoom_in">
                <button type="button" class="btn"  onclick='launchFullScreen();'>
                    <h5><i class="fa fa-search-plus" style="font-size:16px"></i></h5>
                </button>
            </div>
        </div>
        <div class="mb-2" id="info_scan_qr">
            <h5>{{ trans('laother.note_system_confirm') }}:</h5>
            @php
                if(session('info_attendance')){
                    $text_color = '';
                    if(session('info_attendance.status')=='error'){
                        $text_color = 'text-danger';
                    }
                    elseif(session('info_attendance.status')=='success'){

                        $text_color = 'text-success';
                    }
                }
            @endphp
            @if (session('info_attendance.time_attendance'))
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="font-weight-bold text-info">
                            {{ trans('laother.note_attendance_qr') }} <br>
                            ({{ trans('laother.at') }} {{ session('info_attendance.time_attendance') }}) - {{ trans('lasetting.code') }} KH: {{ $course->code }}
                        </h2>
                    </div>
                </div>
            @endif
            <div class="row mt-2">
                <div class="col-3 text-center info-avatar">
                    @if(session('info_attendance.status')=='error')
                        <div class="left-slash"></div>
                        <div class="right-slash"></div>
                    @endif
                    @if (session('info_attendance.profile'))
                        <img src="{{ image_user(session('info_attendance.profile')->avatar) }}" alt="" class="rounded-circle">
                    @endif
                </div>
                <div class="col-5">
                    <p>{{ trans('laother.full_name') }}:
                        <span class="h1 font-weight-bold {{ $text_color }}">
                            @if (session('info_attendance.profile'))
                                {{ session('info_attendance.profile')->full_name }}
                            @endif
                        </span>
                    </p>
                    <p>{{ trans('laother.msnv') }}:
                        <span class="h3 font-weight-bold {{ $text_color }}">
                            @if (session('info_attendance.profile'))
                                {{ session('info_attendance.profile')->code }}
                            @endif
                        </span>
                    </p>
                    <p>{{ trans('latraining.title') }}:
                        <span class="h3 font-weight-bold {{ $text_color }}">
                            @if (session('info_attendance.profile'))
                                {{ session('info_attendance.profile')->title_name }}
                            @endif
                        </span>
                    </p>
                    <p>{{ trans('laother.identification') }}:
                        <span class="h3 font-weight-bold {{ $text_color }}">
                            @if (session('info_attendance.profile'))
                                {{ session('info_attendance.profile')->identity_card }}
                            @endif
                        </span>
                    </p>
                </div>
                <div class="col-4 d-flex align-items-center">
                    @if (session('info_attendance.status')=='error')
                        <div class="alert alert-danger text-center" role="alert">
                            <h2>{{ session('info_attendance.error')  }}</h2>
                        </div>
                    @endif

                    @if (session('info_attendance.status')=='success')
                        <div class="alert alert-success text-center" role="alert">
                            <h2>{{ session('info_attendance.success') }}</h2>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-4">
                    {{ trans('latraining.total_user') }}: {{ $num_register }} <i class="fa fa-user"></i>
                </div>
                <div class="col-4">
                    {{ trans('laother.present') }}: {{ session('info_attendance.total_attendance') ? session('info_attendance.total_attendance') : $total_attendance }} <i class="fa fa-user"></i>
                </div>
                <div class="col-4">
                    {{ trans('latraining.absent') }}: {{ $num_register - (session('info_attendance.total_attendance') ? session('info_attendance.total_attendance') : $total_attendance) }} <i class="fa fa-user"></i>
                </div>
            </div>

            @php
                \Session::forget('info_attendance')
            @endphp

        </div>
        <div class="row mb-2">
            <div class="col-6 d-flex align-items-center">
                {{ trans('laother.attendance_person') }}: {{ $profile_teacher->full_name .' ('. $profile_teacher->code .')' }}
            </div>
            <div class="col-6">
                <select name="schedules_id" id="schedules_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.choose_session') }} --">
                    <option value=""></option>
                    @if(count($schedules) != 0)
                        @foreach($schedules as $key => $item)
                            <option value="{{ $item->id }}" {{ $item->id == $schedule ? "selected" : "" }}>
                                {{ trans('latraining.session') .' '. ($key + 1) .' ('. get_date($item->start_time, 'H:i') .' -> '. get_date($item->end_time, 'H:i') .' - '. get_date($item->lesson_date, 'd/m/Y') . ')' }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        @if ($schedule>0)
            <div class="row pb-2">
                <div class="col-md-12 pull-right text-right">
                    <a href="javascript:void(0)" id="get_modal_qrcode">Lấy mã điểm danh qr code</a>
                </div>
            </div>
        @endif
        <div class="row mb-2">
            <div class="col-4"></div>
            <div class="col-8 pb-2">
                @if ($schedule>0)
                    <a href="javascript:void(0);" class="mr-1" id="qrcode-device" >
                        <img src="{{asset('images/qr-code.svg')}}" width="30px" class="image_night_mode"/> @lang('laother.scan_device')
                    </a>
                    <a href="#" data-toggle="modal" data-target="#modal-qrcode">
                        <img src="{{asset('images/qr-code.svg')}}" width="30px" class="image_night_mode"/> @lang('laother.scan_attendance_code')
                    </a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-right">
                <a href="javascript:void(0);" id="show_table_user" class="btn show">
                    <i class="fa fa-eye"></i> {{ trans('laother.list_student') }}
                </a>
                <a href="javascript:void(0);" id="show_guide_scan" class="btn show">
                    <i class="fa fa-eye"></i> {{ trans('laother.intruction_scan_qr') }}
                </a>
            </div>
        </div>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
            <tr>
                <th data-formatter="index_formatter" data-width="4%" data-align="center">STT</th>
                <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('latraining.employee_name') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="time_attendance" data-align="center" data-width="5%">{{ trans('laother.attendance_time') }}</th>
                <th data-field="percent" data-align="center" data-width="5%">% <br> {{ trans('latraining.join') }}</th>
                <th data-field="discipline" data-align="center" data-width="15%">{{ trans('latraining.violation') }}</th>
                <th data-field="absent" data-align="center" data-width="15%">{{ trans('latraining.vacation_type') }}</th>
                <th data-field="absent_reason" data-align="center" data-width="15%">{{ trans('latraining.reason_absence') }}</th>
            </tr>
            </thead>
        </table>

        <div class="row mt-2" id="guide_scan">
            <div class="col-2">
                <img src="{{ asset('images/mobile-scans-qr-code.webp') }}" alt="" class="w-100">
            </div>
            <div class="col-10">
                <p class="h2">{{ trans('laother.qr_attendance_guide') }}</h5>
                <p class="h5">
                    "{{ trans('laother.give_code') }} <span class="h3">{{ trans('laother.your_qr_code') }}</span> ({{ trans('laother.note_at_info_online') }}) {{ trans('laother.teacher_class_conduct') }} <span class="h4">{{ trans('laother.attendance_qr_code') }}</span> {{ trans('laother.confirm_attendance') }}"
                </p>
            </div>
        </div>
    </div>
    @if ($schedule>0)
        <div class="modal fade" id="modal-qrcode-device" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelDevice" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabelDevice">@lang('laother.scan_device')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            {{ trans('labutton.close') }}
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <svg class="w-100" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512">
                                    <g><g><path d="m473.622 261.785c-65.151-4.749-130.301-4.749-195.452 0-18.287 1.333-33.011 15.66-34.671 33.92-4.943 54.382-4.943 108.763 0 163.145 1.66 18.26 16.384 32.587 34.671 33.92 65.151 4.749 130.302 4.749 195.452 0 18.286-1.333 33.011-15.66 34.671-33.92 4.943-54.382 4.943-108.763 0-163.145-1.66-18.26-16.385-32.588-34.671-33.92z" fill="#ededed"/><g><g fill="#365e7d"><path d="m289.66 455.271c-4.268 0-7.726-3.459-7.726-7.726v-140.535c0-4.267 3.459-7.726 7.726-7.726 4.268 0 7.726 3.459 7.726 7.726v140.535c0 4.267-3.458 7.726-7.726 7.726z"/><path d="m318.405 431.66c-4.268 0-7.726-3.459-7.726-7.726v-116.924c0-4.267 3.459-7.726 7.726-7.726s7.726 3.459 7.726 7.726v116.924c.001 4.267-3.458 7.726-7.726 7.726z"/><path d="m347.15 431.66c-4.268 0-7.726-3.459-7.726-7.726v-116.924c0-4.267 3.459-7.726 7.726-7.726s7.726 3.459 7.726 7.726v116.924c0 4.267-3.458 7.726-7.726 7.726z"/><path d="m375.895 431.66c-4.268 0-7.726-3.459-7.726-7.726v-116.924c0-4.267 3.459-7.726 7.726-7.726 4.268 0 7.726 3.459 7.726 7.726v116.924c.001 4.267-3.458 7.726-7.726 7.726z"/><g><path d="m318.405 455.271c-4.268 0-7.726-3.459-7.726-7.726v-.536c0-4.267 3.459-7.726 7.726-7.726s7.726 3.459 7.726 7.726v.536c.001 4.267-3.458 7.726-7.726 7.726z"/><path d="m347.15 455.271c-4.268 0-7.726-3.459-7.726-7.726v-.536c0-4.267 3.459-7.726 7.726-7.726s7.726 3.459 7.726 7.726v.536c0 4.267-3.458 7.726-7.726 7.726z"/><path d="m375.895 455.271c-4.268 0-7.726-3.459-7.726-7.726v-.536c0-4.267 3.459-7.726 7.726-7.726 4.268 0 7.726 3.459 7.726 7.726v.536c.001 4.267-3.458 7.726-7.726 7.726z"/></g><g><path d="m433.386 455.271c-4.268 0-7.726-3.459-7.726-7.726v-.536c0-4.267 3.459-7.726 7.726-7.726s7.726 3.459 7.726 7.726v.536c.001 4.267-3.458 7.726-7.726 7.726z"/><path d="m462.132 455.271c-4.268 0-7.726-3.459-7.726-7.726v-.536c0-4.267 3.459-7.726 7.726-7.726 4.268 0 7.726 3.459 7.726 7.726v.536c0 4.267-3.459 7.726-7.726 7.726z"/></g><path d="m404.641 455.271c-4.268 0-7.726-3.459-7.726-7.726v-140.535c0-4.267 3.459-7.726 7.726-7.726s7.726 3.459 7.726 7.726v140.535c0 4.267-3.459 7.726-7.726 7.726z"/><path d="m433.386 431.66c-4.268 0-7.726-3.459-7.726-7.726v-116.924c0-4.267 3.459-7.726 7.726-7.726s7.726 3.459 7.726 7.726v116.924c.001 4.267-3.458 7.726-7.726 7.726z"/><path d="m462.132 431.66c-4.268 0-7.726-3.459-7.726-7.726v-116.924c0-4.267 3.459-7.726 7.726-7.726 4.268 0 7.726 3.459 7.726 7.726v116.924c0 4.267-3.459 7.726-7.726 7.726z"/></g></g></g><g><path d="m246.711 170.986-28.81 76.889-10.266 27.393c-5.029 13.391-18.503 21.67-32.722 20.076l-63.736-7.14-.062-49.279-.083-67.937h135.679z" fill="#db5c6e"/><path d="m246.711 170.986-28.81 76.889c-31.718-1.283-69.562-3.86-106.786-8.951l-.083-67.937h135.679z" fill="#d64553"/><path d="m171.115 235.405c-42.149 64.988-48.669 143.233-48.669 143.233l9.531 9.52c6.644 6.654 10.183 15.543 10.183 24.598 0 4.357-.817 8.745-2.504 12.946-6.064 15.108-21.804 23.967-37.875 21.369-32.774-5.299-56.554-18.71-68.972-27.248-6.178-4.243-9.862-11.27-9.759-18.762 1.159-78.855 14.25-143.781 23.905-181.377 5.785-22.549 10.338-35.268 10.338-35.268l141.805 16.092c-10.742 10.744-20.004 22.583-27.983 34.897z" fill="#407093"/><path d="m199.097 200.51c-10.742 10.742-20.003 22.581-27.982 34.895-39.5-2.504-84.154-7.161-124.161-15.719 5.785-22.549 10.338-35.268 10.338-35.268z" fill="#365e7d"/><path d="m199.091 200.512c-3.943 3.943-7.689 8.031-11.239 12.242l-110.862-12.583s-32.391 90.518-34.243 216.655c-.052 3.798.869 7.482 2.608 10.742-4.978-2.763-9.179-5.433-12.542-7.741-6.178-4.243-9.862-11.27-9.759-18.762 1.852-126.137 34.243-216.645 34.243-216.645z" fill="#365e7d"/><path d="m362.063 70.947c-1.652 27.981-6.021 69.314-17.419 109.429-4.394 15.464-18.503 26.129-34.578 26.032-43.762-.265-133.75-3.16-200.94-20.945-12.851-3.402-22.712-13.719-25.595-26.696-3.658-16.465-8.279-41.538-10.384-70.44-1.294-17.77 10.774-33.74 28.203-37.439 44.41-9.427 133.554-24.402 227.704-17.634 19.463 1.398 34.16 18.214 33.009 37.693z" fill="#db5c6e"/><path d="m345.931 175.694c-.414 1.563-.848 3.125-1.294 4.678-4.387 15.471-18.503 26.13-34.574 26.037-43.764-.269-133.754-3.156-200.946-20.945-12.842-3.405-22.704-13.722-25.592-26.699-3.653-16.465-8.279-41.539-10.38-70.442-.797-10.907 3.446-21.142 10.876-28.272-.393 2.566-.507 5.206-.311 7.886 2.111 28.903 6.726 53.978 10.39 70.442 2.877 12.977 12.739 23.295 25.592 26.689 67.192 17.789 157.182 20.687 200.935 20.945 9.73.061 18.733-3.82 25.304-10.319z" fill="#d64553"/><path d="m325.997 64.8c-1.864 31.562-6.791 78.183-19.648 123.433-4.956 17.443-20.87 29.473-39.003 29.363-49.362-.299-150.866-3.564-226.654-23.626-14.495-3.837-25.618-15.474-28.87-30.112-4.126-18.572-9.339-46.854-11.713-79.454-1.46-20.044 12.153-38.058 31.812-42.231 50.093-10.633 150.644-27.524 256.843-19.891 21.953 1.579 38.53 20.546 37.233 42.518z" fill="#407093"/><path d="m102.484 206.287c-21.411-3.167-42.439-7.192-61.791-12.315-14.498-3.839-25.623-15.471-28.872-30.114-4.129-18.565-9.345-46.847-11.714-79.455-1.459-20.034 12.149-38.051 31.811-42.221 12.791-2.711 28.862-5.837 47.479-8.858-5.236 1.035-10.11 2.038-14.612 2.99-21.162 4.492-35.816 23.874-34.243 45.451 2.556 35.091 8.165 65.536 12.615 85.53 3.498 15.75 15.471 28.282 31.066 32.411 9.117 2.411 18.575 4.594 28.261 6.581z" fill="#365e7d"/><path d="m262.707 132.289h-198.126c-7.286 0-13.192-5.906-13.192-13.192 0-7.286 5.906-13.192 13.192-13.192h198.126c7.286 0 13.192 5.906 13.192 13.192 0 7.286-5.906 13.192-13.192 13.192z" fill="#ededed"/></g><g fill="#cc7964"><path d="m413.217 118.735v2.14c0 7.549 5.845 13.808 13.376 14.325l23.165 1.589c8.297.569 15.341-6.008 15.341-14.325v-5.318c0-8.317-7.044-14.894-15.341-14.325l-23.165 1.589c-7.531.517-13.376 6.776-13.376 14.325z"/><path d="m395.341 187.589-1.07 1.854c-3.774 6.537-1.842 14.881 4.422 19.094l19.267 12.959c6.901 4.642 16.29 2.467 20.449-4.735l2.659-4.606c4.158-7.203 1.347-16.421-6.123-20.077l-20.856-10.206c-6.782-3.319-14.973-.82-18.748 5.717z"/><path d="m394.271 50.168 1.07 1.854c3.774 6.537 11.966 9.036 18.747 5.718l20.856-10.206c7.47-3.656 10.282-12.874 6.123-20.077l-2.659-4.606c-4.158-7.203-13.547-9.377-20.449-4.735l-19.267 12.959c-6.263 4.212-8.195 12.555-4.421 19.093z"/></g></g>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@lang('laother.scan_attendance_code')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="app">
                            <qrcode-stream @init="onInit" @decode="onDecode" :paused="paused">
                                <div v-if="decodedContent !== null" class="decoded-content"></div>
                            </qrcode-stream>

                            <div class="error">
                                @{{ errorMessage }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <input type="text" id="scanner" placeholder="scanner" value="" autofocus class="form-control" style="opacity: 0;">
    <div class="modal fade" id="modal-qrcode-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.attendance_code_qr_code') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        @if ($qrcode_attendance)
                            <div id="qrcode" >
                                {!! QrCode::size(300)->generate($qrcode_attendance); !!}
                                <p>{{ trans('latraining.scan_code_attendance') }}</p>
                            </div>
                        @endif
                        <a href="javascript:void(0)" id="print_qrcode">{{ trans('latraining.print_qr_code') }}</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index){
            return (index + 1);
        }
        function name_formatter(value, row, index) {
            return row.lastname +' '+ row.firstname +' ('+ row.code +')';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.training_teacher.attendance_user.getdata', [$course->id, $class_id]) }}?schedule={{ $schedule }}',
        });

        Vue.use(VueQrcodeReader);
        new Vue({
            el: '#app',

            data () {
                return {
                    paused: false,
                    decodedContent: null,
                    errorMessage: ''
                }
            },

            methods: {
                async onDecode (content) {
                    this.camera = false;
                    var param = JSON.parse(content);
                    var url = '{{ route('backend.category.training_teacher.attendance_user.qrcode_process') }}?schedule={{$schedule}}&course={{$course->id}}&class_id={{$class_id}}&user='+param.user_id+'&type=teacher_attendance';
                    window.location.href = url;

                    this.decodedContent = content;
                },

                onInit (promise) {
                    promise.then(() => {
                        console.log('Successfully initilized! Ready for scanning now!')
                    })
                        .catch(error => {
                            if (error.name === 'NotAllowedError') {
                                this.errorMessage = 'Hey! I need access to your camera'
                            } else if (error.name === 'NotFoundError') {
                                this.errorMessage = 'Do you even have a camera on your device?'
                            } else if (error.name === 'NotSupportedError') {
                                this.errorMessage = 'Seems like this page is served in non-secure context (HTTPS, localhost or file://)'
                            } else if (error.name === 'NotReadableError') {
                                this.errorMessage = 'Couldn\'t access your camera. Is it already in use?'
                            } else if (error.name === 'OverconstrainedError') {
                                this.errorMessage = 'Constraints don\'t match any installed camera. Did you asked for the front camera although there is none?'
                            } else {
                                this.errorMessage = 'UNKNOWN ERROR: ' + error.message
                            }
                        })
                }
            }
        })

        var ajax_save_absent = "{{ route('module.offline.save_absent', [$course->id]) }}?schedule={{ $schedule }}";
        var ajax_save_discipline = "{{ route('module.offline.save_discipline', [$course->id]) }}?schedule={{ $schedule }}";
        var ajax_save_absent_reason= "{{ route('module.offline.save_absent_reason', [$course->id]) }}?schedule={{ $schedule }}";

        $('.bootstrap-table').hide();
        $('#show_table_user').on('click', function(){
            if($(this).hasClass('show')){
                $(this).removeClass('show').addClass('hide');
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                $('.bootstrap-table').show();
            }else{
                $(this).removeClass('hide').addClass('show');
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                $('.bootstrap-table').hide();
            }
        });

        $('#guide_scan').hide();
        $('#show_guide_scan').on('click', function(){
            if($(this).hasClass('show')){
                $(this).removeClass('show').addClass('hide');
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                $('#guide_scan').show();
            }else{
                $(this).removeClass('hide').addClass('show');
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                $('#guide_scan').hide();
            }
        });

        if ('{{ session()->exists('zoom_screen') && session()->get('zoom_screen') == 1 }}') {
            $('.menu_left_backend ').hide();
            $('.wrraped_header ').hide();
            $('.breadcrumb').hide();
            $('.search_button_vertical').hide();
            $('.body_content').css('left', '0px');
            $('.wrapper_backend').css('margin-left', '0px');
            $('.header').css('height', '0');
            $('.body_content').removeClass('pt-5');

            $('#zoom_out').show();
            $('#zoom_in').hide();
        }else{
            $('#zoom_out').hide();
        }

        function launchFullScreen() {
            $('.menu_left_backend ').hide();
            $('.wrraped_header ').hide();
            $('.breadcrumb').hide();
            $('.search_button_vertical').hide();
            $('.body_content').css('left', '0px');
            $('.wrapper_backend').css('margin-left', '0px');
            $('.header').css('height', '0');
            $('.body_content').removeClass('pt-5');

            $('#zoom_out').show();
            $('#zoom_in').hide();

            zoom_screen(1);
        }

        function exitFullScreen() {
            $('.menu_left_backend ').show();
            $('.wrraped_header ').show();
            $('.breadcrumb').show();
            $('.search_button_vertical').show();
            $('.body_content').css('left', '220px');
            $('.wrapper_backend').css('margin-left', '230px');
            $('.header').css('height', '50px');
            $('.body_content').addClass('pt-5');

            $('#zoom_out').hide();
            $('#zoom_in').show();

            zoom_screen(0);
        }

        function zoom_screen(status) {
            $.ajax({
                url: "{{ route('backend.zoom_screen') }}",
                type: 'post',
                data: {
                    status: status,
                }
            }).done(function(data) {
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        if('{{ $schedule }}'){
            let input = $("#scanner");
            input.on("keypress", function (e) {
                if (e.keyCode == 13) {
                    var param = JSON.parse($(this).val());

                    var url = '{{ route('backend.category.training_teacher.attendance_user.qrcode_process') }}?schedule={{$schedule}}&course={{$course->id}}&class_id={{$class_id}}&user='+param.user_id+'&type=teacher_attendance';
                    window.location.href = url;
                }
            });
            window.addEventListener("keypress", function (e) {
                if (e.target.tagName !== "INPUT") {
                    input.focus();
                }
            });

            $('#qrcode-device').on('click', function(){
                $('#modal-qrcode-device').modal();

                input.focus();
            });

            document.addEventListener("visibilitychange", onchange);
            function onchange () {
                if (document.hidden) {
                    $('#modal-qrcode-device').modal('hide');
                }
            }
            document.addEventListener("mouseleave", function (e) {
                setTimeout(() => {
                    $('#modal-qrcode-device').modal('hide');
                }, 1000);
            });
        }
        $('#get_modal_qrcode').on('click',function () {
            $("#modal-qrcode-image").modal();
        })
    </script>
    <script src="{{ asset('styles/module/offline/js/attendance.js') }}"></script>
@endsection
