@extends('layouts.backend')

@section('page_title', trans('latraining.history_teaching'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.teacher_permission'),
                'url' => route('backend.category.training_teacher.list_permission')
            ],
            [
                'name' => trans('latraining.history_teaching'),
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
                <table class="tDefault table table-hover bootstrap-table">
                    <thead>
                        <tr>
                            <th data-field="course">{{ trans('latraining.course') }}</th>
                            <th data-field="class_name">{{ trans('latraining.class_name') }}</th>
                            <th data-field="schedule" data-align="center" data-width="10%">{{ trans('latraining.session') }}</th>
                            <th data-field="teacher_main" data-align="center" data-width="5%">{{ trans('latraining.main_lecturer') }}</th>
                            <th data-field="tutors" data-align="center" data-width="5%">{{ trans('latraining.tutors') }}</th>
                            <th data-field="num_hour" data-align="center" data-width="5%">{{ trans('latraining.hour') }}</th>
                            <th data-field="num_star" data-align="center" data-width="5%">{{ trans('latraining.num_star',['num'=>'Số']) }}</th>
                            <th data-field="num_student" data-align="center" data-width="5%">{{ trans('latraining.student') }}</th>
                            <th data-field="cost" data-align="center" data-width="10%">{{ trans('latraining.cost') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: "{{ route('backend.category.training_teacher.history.getdata', ['teacher_id' => $training_teacher->id ?? 0]) }}",
        });
    </script>
@endsection