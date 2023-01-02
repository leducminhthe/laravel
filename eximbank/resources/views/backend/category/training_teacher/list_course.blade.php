@extends('layouts.backend')

@section('page_title', trans('latraining.attendance'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.teacher_permission'),
                'url' => route('backend.category.training_teacher.list_permission')
            ],
            [
                'name' => trans('latraining.attendance'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum', $breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text"
                        name="search"
                        value=""
                        class="form-control mr-1"
                        autocomplete="off"
                        placeholder="{{ trans('latraining.enter_code_name_course') }}"
                    >
                    <input name="start_date"
                        type="text"
                        class="datepicker form-control mr-1"
                        placeholder="{{ trans('latraining.start_date') }}"
                        autocomplete="off"
                    >
                    <input name="end_date"
                        type="text"
                        class="datepicker form-control mr-1"
                        placeholder="{{ trans('latraining.end_date') }}"
                        autocomplete="off"
                    >
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
        </div>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-formatter="index_formatter" data-width="5%" data-align="center">{{trans('latraining.stt')}}</th>
                    <th data-field="course_name">{{trans('lacategory.course')}}</th>
                    <th data-field="course_date" data-align="center" data-width="15%">{{trans('latraining.time')}}</th>
                    <th data-field="num_schedule" data-align="center" data-width="5%">{{trans('latraining.num_session')}}</th>
                    <th data-field="num_register" data-align="center" data-width="5%">{{trans('latraining.total_user')}}</th>
                    <th data-formatter="attendance_formatter" data-align="center" data-width="5%">{{trans('latraining.attendance')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index){
            return (index + 1);
        }
        function attendance_formatter(value, row, index){
            return '<a href="'+ row.attendance_url +'"><img src="{{ asset('images/qr-code.svg') }}" width="20px" class="image_night_mode" /> ('+ row.total_attendance +')</a>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.training_teacher.list_course.getdata') }}',
        });
    </script>
@endsection
