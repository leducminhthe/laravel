@extends('layouts.backend')

@section('page_title',trans('latraining.quiz_grading'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.teacher_permission'),
                'url' => route('backend.category.training_teacher.list_permission')
            ],
            [
                'name' => trans('latraining.quiz_grading'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_exam')}}">
                    <div class="w-5">
                        <select name="status_grading" class="form-control select2" data-placeholder="{{ trans('latraining.grading') }}">
                            <option value=""></option>
                            <option value="1">{{ trans('latraining.graded') }}</option>
                            <option value="2">{{ trans('latraining.not_grade') }}</option>
                            <option value="3">{{ trans('latraining.grading_unfinished') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="name" data-formatter="name_formatter">{{trans('backend.quiz_name')}}</th>
                    <th data-field="limit_time" data-align="center" data-width="5%" data-formatter="limit_time_formatter">{{trans('backend.time_quiz')}}</th>
                    <th data-field="quantity" data-width="5%" data-align="center">{{trans('backend.number_student')}}</th>
                    <th data-field="quantity_quiz_attempts" data-width="5%" data-align="center">{{trans('backend.number_submission')}}</th>
                    <th data-field="status_grading" data-width="7%" data-align="center">{{ trans('latraining.grading') }}</th>
                    @if(\App\Models\Permission::isAdmin())
                        <th data-field="teacher_grading" data-formatter="teacher_grading_formatter" data-width="7%" data-align="center">{{ trans('latraining.teacher_marks_exam') }}</th>
                    @endif
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'"> ('+ row.code +') '+ value +'</a> <br/> <p>'+ row.note +'</p>';
        }

        function limit_time_formatter(value, row, index) {
            return row.limit_time + " {{ trans('latraining.minute') }}";
        }

        function teacher_grading_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="btn btn-link load-modal" data-url="'+row.info_url+'" title="{{ trans('latraining.info') }}"> <i class="fa fa-info-circle"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.grading.data_quiz') }}',
        });
    </script>

@endsection
