@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC11">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_from') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="from_date" class="form-control datepicker-date" value="{{ $firstDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_to') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="to_date" class="form-control datepicker-date" value="{{ $lastDateFormat }}">
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" id="btnSearch" class="btn">{{trans('labutton.view_report')}}</button>
            <button id="btnExport" class="btn" name="btnExport">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> {{ trans('labutton.export_excel') }}
            </button>
        </div>
    </div>
</form>
<br>
<div class="table-responsive">
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
                <th data-field="user_code">{{ trans('latraining.employee_code') }}</th>
                <th data-field="fullname">{{ trans('latraining.fullname') }}</th>
                <th data-field="role_lecturer">{{ trans('lareport.teacher_role') }}</th>
                <th data-field="role_tuteurs">{{ trans('lareport.assistant_role') }}</th>
                <th data-field="account_number">{{ trans('lacategory.account_number') }}</th>
                <th data-field="area_name_unit">{{ trans('lacategory.area') }}</th>
                <th data-field="unit_name_1">{{ trans('lareport.unit_direct') }}</th>
                <th data-field="unit_name_2">{{ trans('latraining.unit_manager') }}</th>
                {{-- <th data-field="unit_code_1">Mã đơn vị cấp 1</th>
                <th data-field="unit_name_1">Đơn vị cấp 1</th>
                <th data-field="unit_code_2">Mã đơn vị cấp 2</th>
                <th data-field="unit_name_2">Đơn vị cấp 2</th>
                <th data-field="unit_code_3">Mã đơn vị cấp 3</th>
                <th data-field="unit_name_3">Đơn vị cấp 3</th> --}}
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th data-field="training_form_name">{{ trans('latraining.training_type') }}</th>
<th data-field="course_time">{{ trans('lareport.duration') }}</th>
                <th data-field="time_lecturer">{{ trans('lareport.primary_teach_duration') }} ({{ trans('latraining.hour') }})</th>
                <th data-field="time_tuteurs">{{ trans('lareport.vice_teach_duration') }} ({{ trans('latraining.hour') }})</th>
                <th data-field="start_date">{{ trans('latraining.from_date') }}</th>
                <th data-field="end_date">{{ trans('latraining.to_date') }}</th>
                <th data-field="time_schedule">{{ trans('latraining.time') }}</th>
                <th data-field="training_location_name">{{ trans('latraining.training_location') }}</th>
                {{--<th data-field="cost">Chi phí giảng dạy</th>--}}
                @foreach($training_cost as $cost)
                    <th data-field="training_cost{{ $cost->id }}">{{ $cost->name }}</th>
                @endforeach
                <th data-field="total_cost">{{ trans('lareport.total_cost') }}</th>
                <th data-field="teacher">{{ trans('latraining.result_evaluation') }} (%)</th>
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
                from_date: {required : true},
                to_date: {required : true},
            },
            messages : {
                from_date: {required : "{{trans('laother.choose_start_date')}}"},
                to_date: {required : "{{trans('laother.choose_end_date')}}"},
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
