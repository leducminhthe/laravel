@extends('layouts.backend')

@section('page_title', trans('latraining.training_result'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.offline_course'),
                'url' => route('module.offline.management')
            ],
            [
                'name' => $page_title,
                'url' => route('module.offline.edit', ['id' => $course->id])
            ],
            /*[
                'name' => trans('latraining.classroom'),
                'url' => route('module.offline.class', ['id' => $course->id]),
                'drop-menu'=>$classArray
            ],*/
            [
                'name' => trans('latraining.training_result_class').': '.$class->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection
<style>
    .th-second{
        height: 40px;
    }
    table {
        font-size: 14px;
    }
</style>
@section('content')
    <div role="main" id="result" class="form_offline_course">
        @if(isset($errors))

            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach

        @endif
        @include('offline::backend.includes.navgroup')
        <br>
        <div class="row">
            <div class="col-md-6">
                @include('offline::backend.result.filter')
            </div>
            <div class="col-md-6 text-right act-btns">
                @if($course->lock_course == 0)
                    @canany(['offline-course-result-create'])
                        <div class="btn-group">
                            {{-- <a class="btn" href="{{ download_template('mau_import_ket_qua_dao_tao_theo_username.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a> --}}
                            <button class="btn" id="import-result" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                            <a class="btn" href="{{ route('module.offline.result.export_result', ['id' => $course->id, 'class_id' => $class->id]) }}">
                                <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                            </a>
                        </div>
                    @endcanany
                @endif
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
            <tr>
                <th rowspan="2" data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                <th rowspan="2" data-field="code">{{trans('latraining.employee_code')}}</th>
                <th rowspan="2" data-field="name" data-formatter="name_formatter">{{ trans('latraining.fullname') }}</th>
                <th rowspan="2" data-field="email">{{ trans('latraining.email') }}</th>
                <th rowspan="2" data-field="percent" data-align="center" data-formatter="percent_formatter" data-width="5%">{{ trans('latraining.join') }}</th>
                @if ($activities_online)
                    <th rowspan="2" data-width="15%" data-align="center" data-formatter="check_finish_elearning_formatter">Hoàn thành Elearning</th>
                @endif
                <th colspan="3" data-width="15%" data-align="center">{{ trans('latraining.test_score') }}</th>
                <th rowspan="2" data-field="survey_course" data-width="3%" data-formatter="survey_course_formatter" data-align="center">
                    {{ trans('latraining.assessments') }} <br> {{ trans('latraining.course') }}
                </th>
                <th rowspan="2" data-field="result" data-formatter="result_formatter" data-align="center">{{ trans('latraining.result') }}</th>
                <th rowspan="2" data-field="note" data-formatter="note_formatter">{{ trans('latraining.note') }}</th>
            </tr>
            <tr>
                <th data-field="score_1" class="th-second" data-align="center">{{ trans('latraining.score_1') }}</th>
                <th data-field="score_2" data-formatter="score_formatter" class="th-second" data-align="center">{{ trans('latraining.score_2') }}</th>
                <th data-field="score" class="th-second" data-align="center">{{ trans('latraining.last_score') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.result.import_result', ['id' => $course->id, 'class_id' => $class->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $course->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.import_training_result') }}</h5>
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
            return (index+1)
        }

        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }

        function check_finish_elearning_formatter(value, row, index) {
            if(row.complete_elearning) {
                return '<i class="fas fa-check text-success"></i>';
            } else {
                return '<i class="fas fa-times text-danger"></i>';
            }

        }

        function result_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case -1: return '<span class="text-muted"> {{ trans("latraining.incomplete") }} </span>';
                case 0: return '<span class="text-danger"> {{ trans("latraining.not_complete") }} </span>';
                case 1: return '<span class="text-success"> {{ trans("latraining.finish") }} </span>';
            }
        }

        function percent_formatter(value, row, index) {
            return '<input name="percent" type="text" class="form-control" value="'+ row.percent +'" disabled>';
        }

        function survey_course_formatter(value, row, index) {
            return '<input name="survey_course" type="checkbox" disabled class="check-item" value="" '+ (row.rating_send == 1 ? "checked": "") +'>';
        }

        function score_formatter(value, row, index) {
            return '<input type="text" name="score" {{ (\App\Models\Permission::isUnitManager() || userCan(['offline-course-result-create'])) && $course->lock_course == 0 ? '' : 'disabled' }} data-id="'+ row.id +'" value="'+ row.score_2 +'" class="form-control is-number change-score">';
        }

        function note_formatter(value, row, index) {
            return '<textarea type="text" name="note" {{ (\App\Models\Permission::isUnitManager() || userCan(['offline-course-result-create'])) && $course->lock_course == 0 ? '' : 'disabled' }} data-id="'+ row.id +'" class="form-control change-note">' + (row.note ? row.note : "") + '</textarea>';
        }

        $('#import-result').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.get_result', ['id' => $course->id, 'class_id' => $class->id]) }}',
        });

        var ajax_save_score = "{{ route('module.offline.save_score', ['id' => $course->id]) }}";
        var ajax_result_save_note = "{{ route('module.offline.result.save_note', ['id' => $course->id]) }}";
    </script>

    <script src="{{ asset('styles/module/offline/js/result.js') }}"></script>
@endsection
