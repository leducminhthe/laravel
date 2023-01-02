@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC06">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('lareport.subject_training')}} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control load-subject" id="subject_id" data-placeholder="{{trans('lareport.subject_training')}}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="subject_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_from') }}</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="from_date" class="form-control datepicker-date" value="{{ $firstDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_to') }}</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="to_date" class="form-control datepicker-date" value="{{ $lastDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('lareport.join') }}</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control select2" name="joined" data-placeholder="Tất cả">
                        <option value=""></option>
                        <option value="1">Đã tham gia</option>
                        <option value="2">Chưa tham gia</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label for="choose_unit">{{ trans('latraining.choose_unit') }}</label>
                </div>
                <div class="col-md-8">
                    @include('backend.form_choose_unit')
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('lareport.training_form') }}</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-training-form" id="training_type_id" data-placeholder="{{ trans('lareport.training_form') }}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="training_type_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('laprofile.title') }}</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-title" id="title_id" data-placeholder="{{ trans('laprofile.title') }}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="title_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('laprofile.area') }}</label>
                </div>
                <div class="col-md-8 type">
                    <select name="area" id="area" class="form-control load-area-all" data-placeholder="-- {{ trans('laprofile.area') }} --"></select>
                </div>
            </div>
            {{-- @for($i = 1; $i <= 5; $i++)
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="unit_id_{{ $i }}">
                            {{ data_locale($level_name($i)->name, $level_name($i)->name_en) }}
                            @if ($i == 1)
                                (<span class="text-danger">*</span>)
                            @endif
                        </label>
                    </div>
                    <div class="col-md-8">
                        <select name="unit_id" id="unit_id_{{ $i }}" class="load-unit" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. data_locale($level_name($i)->name, $level_name($i)->name_en) }} --" data-level="{{ $i }}" data-parent="{{ empty($unit[$i-1]->id) ? '' : $unit[$i-1]->id }}" data-loadchild="unit_id_{{ $i+1 }}">
                        </select>
                    </div>
                </div>
            @endfor --}}
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
                <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th data-field="user_code">{{ trans('laprofile.employee_code') }}</th>
                <th data-field="fullname">{{ trans('laprofile.full_name') }}</th>
                <th data-field="email">Email</th>
                <th data-field="phone">{{ trans('laprofile.phone') }}</th>
                <th data-field="area_name_unit">{{ trans('laprofile.area') }}</th>
                <th data-field="unit_name_1">{{ trans('lareport.unit_direct') }}</th>
                <th data-field="unit_name_2">{{ trans('lareport.unit_management') }}</th>
                {{-- <th data-field="area">Vùng</th>
                <th data-field="unit_code_1">Mã đơn vị cấp 1</th>
                <th data-field="unit_name_1">Đơn vị cấp 1</th>
                <th data-field="unit_code_2">Mã đơn vị cấp 2</th>
                <th data-field="unit_name_2">Đơn vị cấp 2</th>
                <th data-field="unit_code_3">Mã đơn vị cấp 3</th>
                <th data-field="unit_name_3">Đơn vị cấp 3</th> --}}
                <th data-field="position_name">{{ trans('laprofile.position') }}</th>
                <th data-field="title_name">{{ trans('laprofile.title') }}</th>
                <th data-field="training_unit">{{ trans('lareport.training_unit') }}</th>
                <th data-field="training_type_name">{{ trans('laprofile.training_form') }}</th>
                <th data-field="course_time">{{ trans('lareport.course_duration') }}</th>
                <th data-field="attendance">{{ trans('lareport.course_duration_total') }}</th>
                <th data-field="start_date">{{ trans('laprofile.from_date') }}</th>
                <th data-field="end_date">{{ trans('laprofile.to_date') }}</th>
                <th data-field="time_schedule">{{ trans('laprofile.time') }}</th>
                <th data-field="entrance_quiz">{{ trans('lareport.entrance_exam') }}</th>
                <th data-field="score">{{ trans('laprofile.score') }}</th>
                <th data-field="result">{{ trans('laprofile.result') }}</th>
                <th data-field="status_user">{{ trans('laprofile.status') }}</th>
                <th data-field="joined">{{ trans('lareport.join') }}/{{ trans('lareport.not_participate') }}</th>
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
                // unit_id: {required : true},
                subject_id: {required : true},
            },
            messages : {
                // unit_id: {required : "Chọn Đơn vị"},
                subject_id: {required : "Chọn chuyên đề"},
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

        $('#subject_id').on('change', function () {
            var subject_id = $(this).select2('val');

           $('input[name=subject_id]').val(subject_id);
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
