<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC07">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('laprofile.user')}} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control load-all-user" id="user_id" data-placeholder="{{trans('laprofile.user')}}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="user_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_from') }}</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="from_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_to') }}</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="to_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('laprofile.area')}}</label>
                </div>
                <div class="col-md-8 type">
                    <select name="area" id="area" class="form-control load-area-all" data-placeholder="-- {{trans('laprofile.area')}} --"></select>
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" data-formatter="index_formatter">#</th>
                <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th data-field="user_code">{{trans('laprofile.employee_code')}}</th>
                <th data-field="fullname">{{trans('laprofile.full_name')}}</th>
                <th data-field="email">Email</th>
                <th data-field="phone">{{trans('laprofile.phone')}}</th>
                <th data-field="area">{{trans('laprofile.area')}}</th>
                <th data-field="unit_name_1">{{trans('lareport.unit_direct')}}</th>
                <th data-field="unit_name_2">{{trans('lareport.unit_management')}}</th>
                <th data-field="position_name">{{trans('laprofile.position')}}</th>
                <th data-field="title_name">{{trans('laprofile.title')}}</th>
                <th data-field="training_unit">{{trans('lareport.training_unit')}}</th>
                <th data-field="process_type">{{trans('lareport.training_type')}}</th>
                <th data-field="course_time">{{trans('lareport.course_duration')}}</th>
                <th data-field="attendance">{{trans('lareport.course_duration_total')}}</th>
                <th data-field="start_date">{{trans('lareport.from_date')}}</th>
                <th data-field="end_date">{{trans('lareport.to_date')}}</th>
                <th data-field="time_schedule">{{trans('lareport.time')}}</th>
                <th data-field="course_cost">{{trans('lareport.cost')}}</th>
                <th data-field="entrance_quiz">{{trans('lareport.entrance_exam')}}</th>
                <th data-field="score">{{trans('lareport.score')}}</th>
                <th data-field="result">{{trans('lareport.result')}}</th>
                <th data-field="note">{{trans('lareport.note')}}</th>
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
        /*form.validate({
            ignore: [],
            rules : {
                unit_id: {required : true},
                subject_id: {required : true},
            },
            messages : {
                unit_id: {required : "Chọn Đơn vị"},
                subject_id: {required : "Chọn chuyên đề"},
            },
            errorPlacement: function (error, element) {
                var name = $(element).attr("name");
                error.appendTo($(element).parent());
            },
        });*/
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

        $('#user_id').on('change', function () {
            var user_id = $(this).select2('val');

           $('input[name=user_id]').val(user_id);
        });
    });
</script>
