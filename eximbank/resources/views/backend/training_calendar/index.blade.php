@extends('layouts.backend')

@section('page_title', trans('lamenu.training_calendar'))

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
    <script src="{{ asset('vendor/fullcalendar/main.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/fullcalendar/locales-all.js') }}" type="text/javascript"></script>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_calendar'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <style>
        .i_text{
            font-style: italic;
        }
        .b_tex{
            font-weight: bold;
        }
    </style>
    <div role="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-12">
                    <button type="button" class="btn" id="all_course_calendar" data-type="1"> Tất cả khóa học</button>
                    <button type="button" class="btn" id="online_course_calendar" data-type="2"> @lang('app.online_course_calendar')</button>
                    <button type="button" class="btn" id="offline_course_calendar" data-type="3"> @lang('app.offline_course_calendar')</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var type = 1;
        var calendarEl = document.getElementById('calendar');
        $.ajax({
            url: '{{ route('backend.training_calendar.getdata') }}',
            success: function (res) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'vi',
                    initialView: 'dayGridMonth',
                    resourceRender: function(info) {
                        info.el.querySelector('.fc-cell-text').innerHTML = '<button>test</button>'
                    },
                    eventDidMount: function(info) {
                        $(info.el).tooltip({
                            title: info.event.extendedProps.description,
                        });
                    },
                    events: res,
                    editable: true,
                    selectable: true,
                    businessHours: true,
                });
                calendar.render();
            }
        });

        $('#all_course_calendar').on('click', function () {
            type = $(this).data('type');

            $.ajax({
                url: '{{ route('backend.training_calendar.getdata') }}?type=' + type,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: 'vi',
                        initialView: 'dayGridMonth',
                        events: res,
                        eventDidMount: function(info) {
                            $(info.el).tooltip({
                                title: info.event.extendedProps.description
                            });
                        },
                    });
                    calendar.render();
                }
            });
        });

        $('#online_course_calendar').on('click', function () {
            type = $(this).data('type');

            $.ajax({
                url: '{{ route('backend.training_calendar.getdata') }}?type=' + type,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: 'vi',
                        initialView: 'dayGridMonth',
                        events: res,
                        eventDidMount: function(info) {
                            $(info.el).tooltip({
                                title: info.event.extendedProps.description
                            });
                        },
                    });
                    calendar.render();
                }
            });
        });

        $('#offline_course_calendar').on('click', function () {
            type = $(this).data('type');

            $.ajax({
                url: '{{ route('backend.training_calendar.getdata') }}?type=' + type,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: 'vi',
                        initialView: 'dayGridMonth',
                        events: res,
                        eventDidMount: function(info) {
                            $(info.el).tooltip({
                                title: info.event.extendedProps.description,
                            });
                        },
                    });
                    calendar.render();
                }
            });
        });

    </script>
@endsection
