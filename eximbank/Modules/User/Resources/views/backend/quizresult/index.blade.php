@extends('layouts.backend')

@section('page_title', trans('laprofile.quiz_result'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.user'),
                'url' => route('module.backend.user')
            ],
            [
                'name' => $full_name,
                'url' => route('module.backend.user.edit',['id'=>$user_id])
            ],
            [
                'name' => trans('laprofile.quiz_result'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        @include('user::backend.layout.menu')
        <div class="table-responsive">
            <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap">
                <thead>
                <tr class="tbl-heading">
                    <th data-width="40px" data-formatter="index_formatter">#</th>
                    <th data-field="code" data-width="5%">{{ trans('laprofile.quiz_code') }}</th>
                    <th data-width="20%" data-field="name">{{ trans('laprofile.quiz') }}</th>
                    <th data-width="20%" data-field="part_name">{{ trans('latraining.part') }}</th>
                    <th data-width="20%" data-field="count_attempt" data-align="center">Số lần thi</th>
                    <th data-width="20%" data-field="pass_score" data-align="center">{{ trans('latraining.pass_score') }}</th>
                    <th data-width="20%" data-field="name" data-formatter="type_formatter" data-align="center">Loại kỳ thi</th>
                    <th data-field="start_date" data-width="180px" data-align="center">{{ trans('laprofile.start_date') }}</th>
                    <th data-field="end_date" data-width="180px" data-align="center">{{ trans('laprofile.end_date') }}</th>
                    <th data-field="limit_time" data-width="150px" data-align="center">{{ trans('laprofile.exam_time_minutes') }}</th>
                    <th data-align="center" data-width="80px"  data-field="grade">{{ trans('laprofile.score') }}</th>
                    <th data-align="center" data-width="160px" data-field="result" data-formatter="result_formatter">{{ trans('laprofile.result') }}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }
        function type_formatter(value, row, index) {
            if(row.quiz_type == 1) {
                return 'Online';
            } else if (row.quiz_type == 2) {
                return 'Offline';
            } else {
                return 'Thi độc lập';
            }
            
        }
        function result_formatter(value, row, index) {
            return value == 1 ? '{{trans("backend.finish")}}' : '{{ trans("backend.incomplete") }}';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user.quizresult.getdata',['user_id'=>$user_id]) }}',
        });

    </script>

@endsection
