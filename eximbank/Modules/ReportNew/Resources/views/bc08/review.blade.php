@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC08">
    <div class="row">
        <div class="col-md-3"></div>
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
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('laprofile.training_form')}}</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-training-form" id="training_type_id" data-placeholder="{{trans('laprofile.training_form')}}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="training_type_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('laprofile.title')}}</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-title" id="title_id" data-placeholder="{{trans('laprofile.title')}}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="title_id" value="">
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
                <th rowspan="2" data-align="center" data-formatter="index_formatter">#</th>
                <th rowspan="2" data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th rowspan="2" data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th rowspan="2" data-field="lecturer">{{trans('lareport.teacher')}}</th>
                <th rowspan="2" data-field="tuteurs">{{trans('lareport.assistant_lecturer')}}</th>
                <th rowspan="2" data-field="training_form_name">{{trans('lareport.training_type')}}</th>
                <th rowspan="2" data-field="training_type_name">{{trans('lareport.training_form')}}</th>
                <th rowspan="2" data-field="level_subject">{{trans('lareport.profession_field')}}</th>
                <th rowspan="2" data-field="training_location">{{trans('lareport.training_location')}}</th>
                <th rowspan="2" data-field="training_unit">{{trans('lareport.training_unit')}}</th>
                <th rowspan="2" data-field="title_join">{{trans('lareport.participating title')}} ({{trans('lareport.required')}})</th>
                <th rowspan="2" data-field="training_object">{{trans('lareport.group_of_participants')}}</th>
                <th rowspan="2" data-field="course_time">{{trans('lareport.duration')}}</th>
                <th rowspan="2" data-field="start_date">{{trans('lareport.from_date')}}</th>
                <th rowspan="2" data-field="end_date">{{trans('lareport.to_date')}}</th>
                <th rowspan="2" data-field="time_schedule">{{trans('lareport.time')}}</th>
                <th rowspan="2" data-field="created_by">{{ trans('laother.creator') }}</th>
                <th rowspan="2" data-field="registers">{{trans('lareport.student_lists')}}</th>
                <th colspan="3">{{trans('lareport.student_attending')}}</th>
                <th rowspan="2" data-field="students_absent">{{trans('lareport.student_absent')}}</th>
                <th rowspan="2" data-field="students_pass">{{trans('lareport.student_pass')}}</th>
                <th rowspan="2" data-field="students_fail">{{trans('lareport.student_fail')}}</th>
                @if($type_cost->count() > 0)
                    @foreach($type_cost as $type)
                        @php
                            $colspan = $count_training_cost($type->id);
                        @endphp
                        <th colspan="{{ $colspan }}">{{ $type->name }}</th>
                    @endforeach
                @endif
                <th colspan="{{ ($student_cost->count() + 1) }}">{{trans('lareport.student_cost')}}</th>
                <th rowspan="2" data-field="total_cost">{{trans('lareport.total_cost')}}</th>
                <th rowspan="2" data-field="recruits">{{trans('lareport.newly_recruited')}}</th>
                <th rowspan="2" data-field="exist">{{trans('lareport.existing_staff')}}</th>
                <th rowspan="2" data-field="plan">{{trans('lareport.plan')}}</th>
                <th rowspan="2" data-field="incurred">{{trans('lareport.incurred')}}</th>
                <th rowspan="2" data-field="monitoring_staff">{{trans('lareport.cadres_supervisor')}}</th>
                <th rowspan="2" data-field="monitoring_staff_note">{{trans('lareport.opinions_monitoring_staff')}}</th>
                <th rowspan="2" data-field="teacher_note">{{trans('lareport.lecturer_opinion')}}</th>
                <th rowspan="2" data-field="teacher_account_number">{{trans('lareport.teach_account_number')}}</th>
            </tr>
            <tr>
                <th data-field="join_100"> 100% </th>
                <th data-field="join_75"> &ge;75% </th>
                <th data-field="join_below_75"> <75% </th>
                @foreach($training_cost as $cost)
                    <th data-field="cost_{{ $cost->id }}"> {{ $cost->name }}</th>
                @endforeach
                @foreach($student_cost as $student_item)
                    <th data-field="student{{ $student_item->id }}"> {{ $student_item->name }}</th>
                @endforeach
                <th data-field="student_total">{{trans('lareport.total_student_cost')}}</th>
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
                from_date: {required : "Chọn từ ngày"},
                to_date: {required : "Chọn đến ngày"},
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
