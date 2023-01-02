@extends('layouts.backend')

@section('page_title', trans('lamenu.data_old_quiz'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.data_old_quiz'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-6">
                @include('quiz::backend.data_old.filter')
            </div>
            <div class="col-md-6 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn" href="javascript:void(0)" id="export-result">
                            <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                        </a>
                        <button class="btn" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                        </button>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-width="1%" data-checkbox="true"></th>
                    <th data-field="user_code" data-align="center">{{ trans('laprofile.employee_code') }}</th>
                    <th data-field="user_name" data-formatter="name_formatter" data-align="center">
                        {{ trans('laprofile.full_name') }}
                    </th>
                    <th data-field="title" data-width="15%">{{ trans('laprofile.title') }}</th>
                    <th data-field="area" data-width="15%">{{ trans('laprofile.subordinates') }}</th>
                    <th data-field="unit" data-width="7%" data-align="center">{{ trans('laprofile.unit') }}</th>
                    <th data-field="department" data-align="center">{{ trans('lacategory.department') }}</th>
                    <th data-field="phone" data-align="center">{{ trans('laprofile.phone') }}</th>
                    <th data-field="email" data-align="center" >{{ trans('laprofile.email') }}</th>
                    <th data-field="quiz_code" data-align="center">{{ trans('latraining.quiz_code') }}</th>
                    <th data-field="quiz_name" data-align="center" data-width="10%">{{ trans('latraining.quiz_name') }}</th>
                    <th data-field="start_date" data-align="center">{{ trans('lareport.start_time') }}</th>
                    <th data-field="end_date" data-align="center">{{ trans('laother.end_time') }}</th>
                    <th data-field="score_essay" data-align="center">{{ trans('latraining.multiple_choice_test_scores') }}</th>
                    <th data-field="score_multiple_choice" data-align="center">{{ trans('latraining.essay_test_score') }}</th>
                    <th data-field="result" data-align="center">{{ trans('latraining.result') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.quiz.data_old_quiz.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('lamenu.data_old_quiz') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.user_name +'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.get_data_old_quiz') }}',
            remove_url: '{{ route('module.quiz.data_old_quiz.remove') }}'
        });

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        $('#export-result').on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.quiz.data_old_quiz.export') }}?'+form_search;
        })
    </script>
@endsection
