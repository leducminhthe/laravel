@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
    $checkTeacher = request()->get('teacher');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="check_teacher" value="{{ $checkTeacher }}">
    <input type="hidden" name="report" value="BC26">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.date_from') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="from_date" class="form-control datepicker-date" value="{{ $firstDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.date_to') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="to_date" class="form-control datepicker-date" value="{{ $lastDateFormat }}">
                </div>
            </div>
            @if ($checkTeacher != 1)
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{ trans('lareport.teacher') }}</label>
                    </div>
                    <div class="col-md-9">
                        <select name="user_id" class="form-control load-user" data-placeholder="{{ trans('lareport.teacher') }}">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            @endif
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
                <th data-formatter="course_formatter">{{ trans('lamenu.course') }}</th>
                {{--  <th data-field="class_name" data-formatter="class_name_formatter">{{ trans('latraining.classroom') }}</th>  --}}
                <th data-field="user_code">{{ trans('latraining.employee_code') }}</th>
                <th data-field="fullname">{{ trans('lareport.lecture_name') }}</th>
                <th data-field="traning_location">{{ trans('latraining.training_location') }}</th>
                <th data-field="unit_name_1">{{ trans('lareport.unit_direct') }}</th>
                <th data-field="unit_name_2">{{ trans('latraining.unit_manager') }}</th>
                <th data-field="account_number">{{ trans('lacategory.account_number') }}</th>
                <th data-field="num_hour">{{ trans('latraining.teaching_time') }}</th>
                <th data-field="cost">{{ trans('lareport.total_fee') }}</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

    function course_formatter(value, row, index) {
        return '<p class="mb-0">'+ row.course_name +' ('+ row.course_code +')' +'</p><p class="mb-0">'+ row.start_date +' <i class="fa fa-arrow-right"></i> '+ row.end_date +'</p>'
          ;
    }

    function class_name_formatter(value, row, index) {
        return '<p class="mb-0">'+ row.class_name +'</p><p class="mb-0">'+ row.schedule_start_time +' <i class="fa fa-arrow-right"></i> '+ row.schedule_end_time +'</p>'
          ;
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
