@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
    <script src="{{ asset('vendor/fullcalendar/main.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/fullcalendar/locales-all.js') }}" type="text/javascript"></script>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lacategory.teacher'),
                'url' => ''
            ],
            [
                'name' => trans('lacategory.list_teacher'),
                'url' => route('backend.category.training_teacher')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
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
        #calendar button {
            border: none;
            margin-left: 3px !important;
            color: {{ $color_text_button . ' !important' }};
            background: linear-gradient(to right, {{ $background_button }}, {{ $get_lighter_background_color }});
        }
        #calendar button:hover {
            border-radius: 5px;
            color: {{ $color_hover_text_button . ' !important' }};
            background: linear-gradient(to right, {{ $hover_background_button }}, {{ $get_lighter_background_hover_color }});
        }
    </style>
<div role="main">
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item">
                <a href="#object" class="nav-link active" data-toggle="tab">{{ trans('lamenu.calendar_teacher') }}</a>
            </li>
            <li class="nav-item">
                <a href="#base" class="nav-link " role="tab" data-toggle="tab">
                    {{ trans('latraining.info') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="#certificate" class="nav-link " role="tab" data-toggle="tab">
                    {{ trans('laprofile.certificates') }}
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane ">
                @include('backend.category.training_teacher.info')
            </div>
            <div id="object" class="tab-pane active">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
            <div id="certificate" class="tab-pane ">
                @include('backend.category.training_teacher.certificate')
            </div>
        </div>
    </div>
</div>
<script>
    var user_id = '{{ $model->id }}';
    var calendarEl = document.getElementById('calendar');
    document.addEventListener('DOMContentLoaded', function() {
        $.ajax({
            url: '{{ route('backend.category.training_teacher.schedule') }}?user_id=' + user_id,
            success: function (res) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'vi',
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
@stop
