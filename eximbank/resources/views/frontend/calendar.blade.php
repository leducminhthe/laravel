@extends('layouts.app')

@section('page_title', trans('laother.title_project'))

@section('header')
    <script src="{{ asset('vendor/fullcalendar/locales-all.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="calendar_body">
        <style>
            .i_text{
                font-style: italic;
            }
            .b_tex{
                font-weight: bold;
            }
        </style>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title">
                            <a href="/">
                                <i class="uil uil-apps"></i>
                                <span>{{ trans('lamenu.home_page') }}</span>
                            </a>
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">@lang('app.training_calendar')</span>
                        </h2>
                    </div>
                </div>
            </div>
            <p></p>
            <div class="row">
                <div class="col-md-10 col-12">
                    <button type="button" class="btn" id="my-calendar" data-type="1"> @lang('app.my_calendar')</button>
                    <button type="button" class="btn" id="online_course_calendar" data-type="2"> @lang('app.online_course_calendar')</button>
                    <button type="button" class="btn" id="offline_course_calendar" data-type="3"> @lang('app.offline_course_calendar')</button>
                </div>
                <div class="col-md-2 col-12 text-right">
                    <a href="{{ route('frontend.calendar.week') }}?type=1" class="btn"> {{ trans('laother.weekly_calendar') }} </a>
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
            url: '{{ route('frontend.calendar.getdata') }}?type=' + type,
            success: function (res) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'vi',
                    initialView: 'dayGridMonth',
                    // headerToolbar: {
                    //     left: 'prev,next today',
                    //     center: 'title',
                    //     right: 'dayGridMonth,dayGridWeek,dayGridDay'
                    // },
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

        $('#my-calendar').on('click', function () {
           type = $(this).data('type');
            $.ajax({
                url: '{{ route('frontend.calendar.getdata') }}?type=' + type,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: 'vi',
                        initialView: 'dayGridMonth',
                        /*headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay'
                        },*/
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
                url: '{{ route('frontend.calendar.getdata') }}?type=' + type,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: 'vi',
                        initialView: 'dayGridMonth',
                        /*headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay'
                        },*/
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
                url: '{{ route('frontend.calendar.getdata') }}?type=' + type,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: 'vi',
                        initialView: 'dayGridMonth',
                        /*headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay'
                        },*/
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
