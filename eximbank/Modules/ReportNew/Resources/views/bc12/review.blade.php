@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC12">
    <div class="row">
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
            {{--<div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('lacategory.area') }}</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-area" data-level="3" name="training_area_id" data-placeholder="{{ trans('lacategory.area') }}">
                        <option value=""></option>
                    </select>
                </div>
            </div>--}}
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('latraining.training_form') }}</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-training-form" id="training_type_id" data-placeholder="{{ trans('latraining.training_form') }}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="training_type_id" value="">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('lacategory.area_unit')}}</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control load-area-all" name="area_id"  autocomplete="off" data-placeholder="{{trans('lacategory.area_choose')}}" >
                        <option value="">{{trans('lacategory.area_choose')}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('latraining.title') }}</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-title" id="title_id" data-placeholder="{{ trans('latraining.title') }}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="title_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label for="choose_unit">{{ trans('latraining.choose_unit') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                    @include('backend.form_choose_unit')
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
                <th data-width="25%" data-field="fullname">{{ trans('latraining.fullname') }}</th>
                <th data-field="email">Email</th>
                <th data-field="area_name_unit">{{ trans('lacategory.area') }}</th>
                <th data-field="phone">{{ trans('latraining.phone') }}</th>
                <th data-field="unit_name_1">{{ trans('lareport.unit_direct') }}</th>
                <th data-field="unit_name_2">{{ trans('latraining.unit_manager') }}</th>
                <th data-field="unit_type_name">{{ trans('laother.unit_type') }}</th>
                <th data-field="position_name">{{ trans('laprofile.position') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th data-field="training_unit">{{ trans('lareport.training_unit') }}</th>
                <th data-field="training_form_name">{{ trans('latraining.training_type') }}</th>
                <th data-field="course_time">{{ trans('lareport.duration') }}</th>
                <th data-field="start_date">{{ trans('latraining.from_date') }}</th>
                <th data-field="end_date">{{ trans('latraining.to_date') }}</th>
                <th data-field="time_schedule">{{ trans('latraining.time') }}</th>
                <th data-field="attendance">{{ trans('lareport.course_duration_total') }}</th>
                <th data-field="status_user">{{ trans('lareport.time') }}</th>
                <th data-field="score">{{ trans('latraining.score') }}</th>
                <th data-field="result">{{ trans('latraining.result') }}</th>
                @foreach($student_cost as $student)
                    <th data-field="student_cost{{ $student->id }}"> {{ $student->name }}</th>
                @endforeach
                <th data-field="avg_teacher_cost">{{ trans('lareport.average_fee_lecture') }}</th>
                <th data-field="avg_organizational_costs">{{ trans('lareport.average_fee_open') }}</th>
                <th data-field="avg_academy_cost">{{ trans('lareport.average_fee_academy') }}</th>
                <th data-field="total_cost">{{ trans('lareport.total_cost') }}</th>
                <th data-field="note">{{ trans('latraining.note') }}</th>
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
                unit_id: {required : true},
            },
            messages : {
                from_date: {required : "{{trans('laother.choose_start_date')}}"},
                to_date: {required : "{{trans('laother.choose_end_date')}}"},
                unit_id: {required : "{{trans('laother.choose_unit')}}"},
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

        $('#training_type_id').on('change', function () {
            var training_type_id = $(this).select2('val');

            $('input[name=training_type_id]').val(training_type_id);
        });

        $('#title_id').on('change', function () {
            var title_id = $(this).select2('val');

            $('input[name=title_id]').val(title_id);
        });
    });
</script>
