@extends('layouts.backend')

@section('page_title', trans('lamenu.calendar_teacher'))

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
    <script src="{{ asset('vendor/fullcalendar/main.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/fullcalendar/locales-all.js') }}" type="text/javascript"></script>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.teacher_permission'),
                'url' => route('backend.category.training_teacher.list_permission')
            ],
            [
                'name' => trans('lamenu.calendar_teacher'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum', $breadcum)
@endsection

@section('content')
    @php
        $get_color_button = \App\Models\SettingColor::where('name','color_button')->first();
        $color_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#c8cdd3' : $get_color_button->text;
        $color_hover_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#c8cdd3' : $get_color_button->hover_text;
        $background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->background;
        $hover_background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->hover_background;
        $get_lighter_background_color = luminance($get_color_button->background, 0.6);
        $get_lighter_background_hover_color = luminance($get_color_button->hover_background, 0.6);
    @endphp
    <style>
        button {
            border: none;
            margin-left: 3px !important;
            color: {{ $color_text_button . ' !important' }};
            background: linear-gradient(to right, {{ $background_button }}, {{ $get_lighter_background_color }});
        }
        button:hover {
            border-radius: 5px;
            color: {{ $color_hover_text_button . ' !important' }};
            background: linear-gradient(to right, {{ $hover_background_button }}, {{ $get_lighter_background_hover_color }});
        }
    </style>
    <div role="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
    @php
        $user_id = profile()->user_id;
        $lang = session()->get('locale_'.$user_id);
    @endphp
    <script type="text/javascript">
        var user_id = '{{ $model->id }}';
        var calendarEl = document.getElementById('calendar');
        document.addEventListener('DOMContentLoaded', function() {
            $.ajax({
                url: '{{ route('backend.category.training_teacher.schedule') }}?user_id=' + user_id,
                success: function (res) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: '{{$lang}}',
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        eventDidMount: function(info) {
                            $(info.el).tooltip({
                                title: info.event.extendedProps.description,
                            });
                        },
                        events: res,
                    });
                    calendar.render();
                }
            });
        })
    </script>
@endsection
