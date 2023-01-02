@extends('layouts.backend')

@section('page_title', trans('lacareer_path.roadmap'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.learning_manager'),
                'url' => route('module.training_by_title.result')
            ],
            [
                'name' => trans('lamenu.learning_path_result'). ": ". $full_name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="table-responsive">
            <table class="tDefault table table-hover table-bordered bootstrap-table">
                <thead>
                <tr class="tbl-heading">
                    <th data-field="index" data-formatter="index_formatter">#</th>
                    <th data-field="subject_code">{{ trans('backend.subject_code') }}</th>
                    <th data-field="subject_name">{{ trans('latraining.subject_name') }}</th>
                    <th data-field="course_code">{{ trans('latraining.course_code') }}</th>
                    <th data-field="course_name">{{ trans('latraining.course_name') }}</th>
                    <th data-field="start_date" data-align="center">{{ trans('latraining.start_date') }}</th>
                    <th data-field="end_date" data-align="center">{{ trans('latraining.end_date') }}</th>
                    <th data-field="course_type" data-align="center">{{ trans('app.training_form') }}</th>
                    <th data-field="score" data-align="center">{{ trans('backend.score') }}</th>
                    <th data-field="result" data-align="center">{{ trans('backend.result') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_by_title.result.getdata_detail',['user_id'=>$user_id]) }}',
        });
    </script>

@endsection
