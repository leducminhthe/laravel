@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC01">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('backend.date_from')}} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="from_date" class="form-control datepicker-date" value="{{ $firstDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('backend.date_to')}} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="to_date" class="form-control datepicker-date" value="{{ $lastDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.quiz') }}</label>
                </div>
                <div class="col-md-6 type">
                    <select name="quiz_id" class="form-control load-quizs" data-placeholder="-- {{ trans('backend.quiz') }} --">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.quiz_type') }}</label>
                </div>
                <div class="col-md-6 type">
                    <select name="quiz_type" id="quiz_type" class="form-control load-quiz-type" data-placeholder="-- {{ trans('backend.quiz_type') }} --">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4"></div>
                <div class="col-md-6">
                    <button type="submit" id="btnSearch" class="btn">{{trans('labutton.view_report')}}</button>
                    <button id="btnExport" class="btn" name="btnExport">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> {{ trans('labutton.export_excel') }}
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>
<br>
<div class="table-responsive">
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th rowspan="2" data-formatter="index_formatter" data-align="center">{{ trans('latraining.stt') }}</th>
                <th colspan="4" data-align="center">{{trans('lareport.exam_information')}}</th>
                <th colspan="3" data-align="center">{{ trans('backend.time') }}</th>
                <th colspan="3" data-align="center">{{trans('lareport.number_candidate')}}</th>
                <th rowspan="2" data-align="center" data-field="score_average">{{trans('lareport.medium_score')}}</th>
                <th colspan="6" data-align="center">{{trans('lareport.candidates_in_score_frame')}}</th>
            </tr>
            <tr class="tbl-heading">
                <th data-align="center" data-field="quiz_name" class="text-nowrap">{{trans('lareport.exame_name')}}</th>
                <th data-align="center" data-field="type_name" class="text-nowrap">{{trans('lareport.type_exam')}}</th>
                <th data-align="center" data-field="quiz_template" class="text-nowrap">{{trans('lareport.exam_title')}}</th>
                <th data-align="center" data-field="num_question">{{trans('lareport.num_question')}}</th>
                <th data-align="center" data-field="limit_time">{{trans('lareport.duration')}}</th>
                <th data-align="center" data-field="start_date">{{trans('lareport.start_time')}}</th>
                <th data-align="center" data-field="end_date">{{trans('lareport.end_time')}}</th>
                <th data-align="center" data-field="num_register">{{trans('lareport.num_candidate_register')}}</th>
                <th data-align="center" data-field="num_doquiz">{{trans('lareport.number_candidates_exam_actual')}}</th>
                <th data-align="center" data-field="num_absent">{{trans('lareport.do_not_participate_exam')}}</th>
                <th data-align="center" data-field="score_03">[0đ - 3đ)</th>
                <th data-align="center" data-field="score_35">[3đ - 5đ)</th>
                <th data-align="center" data-field="score_57">[5đ - 7đ)</th>
                <th data-align="center" data-field="score_78">[7đ - 8đ)</th>
                <th data-align="center" data-field="score_89">[8đ - 9đ)</th>
                <th data-align="center" data-field="score_910">[9đ - 10đ]</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

</script>
<script src="{{asset('styles/module/report/js/bc44.js')}}" type="text/javascript"></script>
