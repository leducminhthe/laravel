@extends('layouts.backend')

@section('page_title',trans('latraining.quiz_grading'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => 'Quyền giảng viên',
                'url' => route('backend.category.training_teacher.list_permission')
            ],
            [
                'name' => trans('backend.exam_grading'),
                'url' => route('module.quiz.grading')
            ],
            [
                'name' =>  $quiz->name.' : '. trans("backend.examinee_list"),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum', $breadcum)
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_examinee')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">

                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-formatter="index_formatter" data-width="3%" data-align="center"> # </th>
                    <th data-field="code" data-formatter="code_formatter" data-width="5%">{{trans('backend.employee_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter" data-width="25%">{{trans('backend.fullname')}}</th>
                    <th data-field="email"  data-width="15%">{{trans('backend.employee_email')}}</th>
                    <th data-field="limit_time" data-align="center" data-width="10%" data-formatter="type_formatter">{{ trans('backend.examinee_type') }}</th>
                    <th data-field="status" data-align="center" data-width="5%" data-formatter="status_formatter">{{trans('latraining.status')}}</th>
                    <th data-field="grading" data-formatter="grading_formatter" data-width="5%" data-align="center">{{trans("backend.grading")}}</th>
                    <th data-field="graded" data-width="5%" data-align="center">{{ trans('latraining.graded_teacher') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function code_formatter(value, row, index) {
            @if($quiz->show_name == 1)
                if (row.type == 1) {
                    return row.user_code;
                }

                if (row.type == 2) {
                    return row.secondary_code;
                }
            @endif
        }

        function name_formatter(value, row, index) {
            @if($quiz->show_name == 1)
                if (row.type == 1) {
                    return row.lastname +' '+row.firstname;
                }

                if (row.type == 2) {
                    return row.secondary_name;
                }
            @endif
        }

        function type_formatter(value, row, index) {
            if (row.type == 1) {
                return '{{ trans("backend.internal_contestant") }}';
            }

            return '{{ trans("backend.user_secondary") }}';
        }

        function grading_formatter(value, row, index) {
            if (row.status != 0) {
                return row.attemp_list;
            }

            return '';
        }

        function status_formatter(value, row, index) {
            if (value == 1) {
                return '<span class="text-success">{{trans("backend.finish")}}</span>';
            }

            return '<span class="text-muted">{{trans("backend.no_homework")}}</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.grading.user.data_user', ['quiz_id' => $quiz->id]) }}',
        });
    </script>

@endsection
