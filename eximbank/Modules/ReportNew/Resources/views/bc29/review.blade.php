@php
    $year = date('Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC29">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('lanote.year') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-6 type">
                    <select name="year" class="form-control select2" data-placeholder="{{ trans('lanote.year') }}">
                        <option value=""></option>
                        @for($i = 2020; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3"></div>
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
                <th rowspan="2" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
                <th rowspan="2" data-field="subject_code">{{ trans('lacourse.course_code') }}</th>
                <th rowspan="2" data-field="subject_name">{{ trans('lacourse.course_name') }}</th>
                <th rowspan="2" data-field="training_plan_code">{{ trans('lareport.plan_code') }}</th>
                <th rowspan="2" data-field="training_plan_name">{{ trans('lareport.plan_name') }}</th>
                <th rowspan="2" data-field="course_action_1" data-align="center">{{ trans('lareport.plan') }}</th>
                <th rowspan="2" data-field="course_action_2" data-align="center">{{ trans('lareport.incurred') }}</th>
                <th colspan="4" data-align="center">{{ trans('latraining.quarter1') }}</th>
                <th colspan="8" data-align="center">{{ trans('latraining.quarter2') }}</th>
                <th colspan="8" data-align="center">{{ trans('latraining.quarter3') }}</th>
                <th colspan="8" data-align="center">{{ trans('latraining.quarter4') }}</th>
                <th colspan="3" data-align="center">{{ trans('lanote.year') }}</th>
            </tr>
            <tr class="tbl-heading">
                <th data-field="plan_precious_1" data-align="center">{{ trans('lareport.plan') }}</th>
                <th data-field="perform_precious_1" data-align="center">{{ trans('lareport.reality') }}</th>
                <th data-field="percent_precious_1" data-align="center">{{ trans('lareport.ratio') }} (%)</th>
                <th data-field="student_precious_1" data-align="center">{{ trans('lareport.num_user') }}</th>

                <th data-field="plan_precious_2" data-align="center">{{ trans('lareport.plan') }}</th>
                <th data-field="perform_precious_2" data-align="center">{{ trans('lareport.reality') }}</th>
                <th data-field="percent_precious_2" data-align="center">{{ trans('lareport.ratio') }} (%)</th>
                <th data-field="plan_accumulated_precious_2" data-align="center">{{ trans('lareport.cumulative_plan') }}</th>
                <th data-field="perform_accumulated_precious_2" data-align="center">{{ trans('lareport.cumulative_reality') }}</th>
                <th data-field="percent_accumulated_precious_2" data-align="center">{{ trans('lareport.ratio') }} (%)</th>
                <th data-field="student_precious_2" data-align="center">{{ trans('lareport.num_user') }}</th>
                <th data-field="student_accumulated_precious_2" data-align="center">{{ trans('lareport.cumulative_num_user') }}</th>

                <th data-field="plan_precious_3" data-align="center">{{ trans('lareport.plan') }}</th>
                <th data-field="perform_precious_3" data-align="center">{{ trans('lareport.reality') }}</th>
                <th data-field="percent_precious_3" data-align="center">{{ trans('lareport.ratio') }} (%)</th>
                <th data-field="plan_accumulated_precious_3" data-align="center">{{ trans('lareport.cumulative_plan') }}</th>
                <th data-field="perform_accumulated_precious_3" data-align="center">{{ trans('lareport.cumulative_reality') }}</th>
                <th data-field="percent_accumulated_precious_3" data-align="center">{{ trans('lareport.ratio') }} (%)</th>
                <th data-field="student_precious_3" data-align="center">{{ trans('lareport.num_user') }}</th>
                <th data-field="student_accumulated_precious_3" data-align="center">{{ trans('lareport.cumulative_num_user') }}</th>

                <th data-field="plan_precious_4" data-align="center">{{ trans('lareport.plan') }}</th>
                <th data-field="perform_precious_4" data-align="center">{{ trans('lareport.reality') }}</th>
                <th data-field="percent_precious_4" data-align="center">{{ trans('lareport.ratio') }} (%)</th>
                <th data-field="plan_accumulated_precious_4" data-align="center">{{ trans('lareport.cumulative_plan') }}</th>
                <th data-field="perform_accumulated_precious_4" data-align="center">{{ trans('lareport.cumulative_reality') }}</th>
                <th data-field="percent_accumulated_precious_4" data-align="center">{{ trans('lareport.ratio') }} (%)</th>
                <th data-field="student_precious_4" data-align="center">{{ trans('lareport.num_user') }}</th>
                <th data-field="student_accumulated_precious_4" data-align="center">{{ trans('lareport.cumulative_num_user') }}</th>

                <th data-field="plan_year" data-align="center">{{ trans('lareport.plan') }}</th>
                <th data-field="perform_year" data-align="center">{{ trans('lareport.reality') }}</th>
                <th data-field="percent_year" data-align="center">{{ trans('lareport.ratio') }} (%)</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    $(document).ready(function () {
        var table = new BootstrapTable({
            url: $('#bootstraptable').data('url'),
        });
        var form = $('#form-search');
        form.validate({
            ignore: [],
            rules : {
                year : {required : true},
            },
            messages : {
                year : {required : "Chọn năm"},
            },
            errorPlacement: function (error, element) {
                var name = $(element).attr("name");
                error.appendTo($(element).parent());
            },
        });
        $('#btnSearch').on('click',function (e) {
            e.preventDefault();
            if(form.valid())
                table.submit();

        });
        $("select").on("select2:close", function (e) {
            $(this).valid();
        });
        $('#btnExport').on('click',function (e) {
            e.preventDefault();
            if(form.valid())
                $(this).closest('form').submit();
            return false
        });
    });
</script>
