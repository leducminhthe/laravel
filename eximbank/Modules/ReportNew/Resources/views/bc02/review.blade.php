@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC02">
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" data-formatter="index_formatter">#</th>
                <th data-field="quiz_name">{{trans('lareport.exame_name')}}</th>
                <th data-field="type_name">{{trans('lareport.department_in_charge')}}</th>
                <th data-field="quiz_template">{{trans('lareport.exam_title')}}</th>
                <th data-field="full_name">{{trans('laprofile.full_name')}}</th>
                <th data-field="user_code">{{trans('laprofile.employee_code')}}</th>
                <th data-field="area_name">{{trans('laprofile.area')}}</th>
                <th data-field="unit_name">{{trans('lareport.unit_direct')}}</th>
                <th data-field="unit_parent_name">{{trans('lareport.unit_management')}}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="email">Email</th>
                <th data-field="limit_time" data-align="center">{{ trans('backend.timer') }} (Phút)</th>
                <th data-field="start_date" data-align="center">{{trans("backend.time_start")}}</th>
                <th data-field="execution_time" data-align="center">{{trans("backend.execution_time")}}</th>
                <th data-field="sumgrades" data-align="center" data-width="5%">{{ trans('backend.score') }}</th>
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
