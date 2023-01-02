@php
    $month = date('m');
    $year = date('Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC13">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label for="month">{{ trans('ladashboard.month') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <input name="month" id="month" type="text" class="form-control" value="{{ $month }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label for="year">{{ trans('lanote.year') }}</label>
                </div>
                <div class="col-md-8">
                    <input name="year" id="year" type="text" class="form-control" value="{{ $year }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label for="area_id">{{ trans('lacategory.area') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <select id="area_id" class="load-area-all" data-placeholder="{{ trans('lacategory.area') }}" data-level="3" multiple></select>
                    <input type="hidden" name="area_id" value="">
                </div>
            </div>
            {{-- @for($i = 1; $i <= 5; $i++)
                <div class="form-group row">
                    <div class="col-md-4 control-label">
                        <label for="unit_id_{{ $i }}">{{ data_locale($level_name($i)->name, $level_name($i)->name_en) }}</label>
                    </div>
                    <div class="col-md-8">
                        <select name="unit_id" id="unit_id_{{ $i }}" class="load-unit" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. data_locale($level_name($i)->name, $level_name($i)->name_en) }} --" data-level="{{ $i }}" data-parent="{{ empty($unit[$i-1]->id) ? '' : $unit[$i-1]->id }}" data-loadchild="unit_id_{{ $i+1 }}">
                        </select>
                    </div>
                </div>
            @endfor --}}
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label for="choose_unit">{{ trans('latraining.choose_unit') }}</label>
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
                <th data-field="area_name">{{ trans('lacategory.area') }}</th>
                <th data-field="unit_name_1">{{ trans('lamenu.branch') }}</th>
                <th data-field="unit_name_2">{{ trans('lasetting.company') }}</th>
                <th data-field="unit_type">{{ trans('lareport.unit_type') }}</th>
                <th data-field="avg_user_by_year">{{ trans('lareport.average_inyear_total') }}</th>
                <th data-field="actual_number_participants">{{ trans('lareport.num_reality_join_user') }}</th>
                <th data-field="hits_actual_participation">{{ trans('lareport.num_reality_join') }}</th>
                @foreach($traing_cost as $cost)
                    <th data-field="traing_cost{{ $cost->id }}"> {{ $cost->name }}</th>
                @endforeach
                @foreach($student_cost as $student)
                    <th data-field="student_cost{{ $student->id }}"> {{ $student->name }}</th>
                @endforeach
                <th data-field="total_cost">{{ trans('lareport.total_cost') }}</th>
                <th data-field="avg_cost_user">{{ trans('lareport.average_student_cost') }}</th>
                <th data-field="avg_cost_actual_number_participants">{{ trans('lareport.average_reality_student_cost') }}</th>
                <th data-field="avg_cost_hits_actual_participation">{{ trans('lareport.average_peruser_cost') }}</th>
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
                month: {required : true},
                area_id: {required : true},
            },
            messages : {
                month: {required : "Chọn tháng"},
                area_id: {required : "Chọn khu vực"},
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

        $('#month').datetimepicker({
           format: 'MM',
        });

        $('#year').datetimepicker({
            format: 'YYYY'
        });

        $('#area_id').on('change', function () {
            var area_id = $(this).select2('val');

            $('input[name=area_id]').val(area_id);
        });
    });
</script>
