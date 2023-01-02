@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC09">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_from') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8 pl-1">
                    <input type="text" name="from_date" class="form-control datepicker-date" value="{{ $firstDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_to') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8 pl-1">
                    <input type="text" name="to_date" class="form-control datepicker-date" value="{{ $lastDateFormat }}">
                </div>
            </div>
            {{--<div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('lacategory.area') }}</label>
                </div>
                <div class="col-md-8 pl-1 type">
                    <select class="form-control load-area" data-level="3" name="training_area_id" data-placeholder="{{ trans('lacategory.area') }}">
                        <option value=""></option>
                    </select>
                </div>
            </div>--}}
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('latraining.training_form') }}</label>
                </div>
                <div class="col-md-8 pl-1 type">
                    <select class="form-control load-training-form" id="training_type_id" data-placeholder="{{ trans('latraining.training_form') }}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="training_type_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('latraining.title') }}</label>
                </div>
                <div class="col-md-8 pl-1 type">
                    <select class="form-control load-title" id="title_id" data-placeholder="{{ trans('latraining.title') }}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="title_id" value="">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-sm-4 control-label">
                    <label>{{trans('lacategory.area_unit')}}</label>
                </div>
                <div class="col-md-8 pl-1">
                    <select class="form-control load-area-all" name="area_id"  autocomplete="off" data-placeholder="{{trans('lacategory.area_choose')}}" >
                        <option value="">{{trans('lacategory.area_choose')}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('lacategory.unit_type')}}</label>
                </div>
                <div class="col-md-8 pl-1 type">
                    <select name="unit_level" id="unit_level" class="form-control load-unit-level" data-placeholder="-- {{trans('lacategory.unit_type')}} --"></select>
                </div>
            </div>
            {{-- <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="unit_id">{{ trans('lacategory.unit') }}</label>
                </div>
                <div class="col-md-8 pl-1">
                    <select name="unit_id" id="unit_id" class="load-unit-by-level" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. trans('lacategory.unit') }} --"  >
                    </select>
                </div>
            </div> --}}
            {{--@for($i = 1; $i <= 5; $i++)
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="unit_id_{{ $i }}">{{ data_locale($level_name($i)->name, $level_name($i)->name_en) }}</label>
                    </div>
                    <div class="col-md-8 pl-1">
                        <select name="unit_id" id="unit_id_{{ $i }}" class="load-unit" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. data_locale($level_name($i)->name, $level_name($i)->name_en) }} --" data-level="{{ $i }}" data-parent="{{ empty($unit[$i-1]->id) ? '' : $unit[$i-1]->id }}" data-loadchild="unit_id_{{ $i+1 }}">
                        </select>
                    </div>
                </div>
            @endfor--}}
            <div class="form-group row">
                <div class="col-sm-4 control-label">
                    <label for="choose_unit">{{ trans('latraining.choose_unit') }}</label>
                </div>
                <div class="col-md-8 pl-1">
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
                <th data-field="user_code">{{ trans('laprofile.employee_code') }}</th>
                <th data-field="fullname">{{ trans('latraining.fullname') }}</th>
                <th data-field="area_name">{{ trans('lacategory.area') }}</th>
                <th data-field="unit_name_1">{{ trans('lareport.unit_direct') }}</th>
                <th data-field="unit_name_2">{{ trans('latraining.unit_manager') }}</th>
                {{-- <th data-field="unit_code_1">Mã đơn vị cấp 1</th>
                <th data-field="unit_name_1">Đơn vị cấp 1</th>
                <th data-field="unit_code_2">Mã đơn vị cấp 2</th>
                <th data-field="unit_name_2">Đơn vị cấp 2</th>
                <th data-field="unit_code_3">Mã đơn vị cấp 3</th>
                <th data-field="unit_name_3">Đơn vị cấp 3</th> --}}
                <th data-field="unit_type">{{ trans('laother.unit_type') }}</th>
                <th data-field="position_name">{{ trans('laprofile.position') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th data-field="class_name">{{ trans('latraining.classroom') }}</th>
                <th data-field="course_time">{{ trans('lareport.duration') }}</th>
                <th data-field="start_date">{{ trans('latraining.from_date') }}</th>
                <th data-field="end_date">{{ trans('latraining.to_date') }}</th>
                <th data-field="time_schedule">{{ trans('latraining.time') }}</th>
                <th data-field="training_area_name">{{ trans('lamenu.place') }}</th>
                <th data-field="result">{{ trans('latraining.result') }}</th>
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
                to_date: {
                    required : true
                },
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

        $('#training_type_id').on('change', function () {
            var training_type_id = $(this).select2('val');

            $('input[name=training_type_id]').val(training_type_id);
        });

        $('#title_id').on('change', function () {
            var title_id = $(this).select2('val');

            $('input[name=title_id]').val(title_id);
        });
        $('select[name=unit_level]').on('change',function () {
            $('select[name=unit_id]').empty().change();
        })
    });
</script>
