<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC18">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-4 control-label required">
                    <label>{{ trans('backend.date_from') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="from_date" value="{{date('d/m/Y')}}" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label required">
                    <label>{{ trans('backend.date_to') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="to_date" value="{{date('t/m/Y')}}" class="form-control datepicker-date">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4 control-label ">
                    <label>{{ trans('latraining.title') }} </label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-title" name="title_id" id="title_id" data-placeholder="{{ trans('latraining.title') }}">
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="title_id" value="">
                </div>
            </div>
            {{-- <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('lamenu.unit') }}</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-unit" name="unit_id" id="unit_id" data-placeholder="{{ trans('lamenu.unit') }}">
                        <option value=""></option>
                    </select>
                </div>
            </div> --}}
            <div class="form-group row">
                <div class="col-sm-4 control-label">
                    <label for="choose_unit">{{ trans('latraining.choose_unit') }}</label>
                </div>
                <div class="col-md-8">
                    @include('backend.form_choose_unit')
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('lacategory.area') }}</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-area-all" name="area_id" id="area_id" data-placeholder="{{ trans('lacategory.area') }}">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('latraining.training_form') }}</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-training-type" name="training_type_id" id="training_type_id" data-placeholder="{{ trans('latraining.training_form') }}">
                        <option value=""></option>
                    </select>
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
            <th data-field="user_code">{{ trans('latraining.employee_code') }}</th>
            <th data-field="full_name">{{ trans('latraining.fullname') }}</th>
            <th data-field="email">Email</th>
            <th data-field="phone">{{ trans('latraining.phone') }}</th>
            <th data-field="unit1_name">{{ trans('lareport.unit_direct') }}</th>
            <th data-field="unit2_name">{{ trans('latraining.unit_manager') }}</th>
            {{-- <th data-field="area">{{ trans('lamenu.area') }}</th>
            <th data-field="unit1_code">Mã đơn vị cấp 1</th>
            <th data-field="unit1_name">Đơn vị cấp 1</th>
            <th data-field="unit2_code">Mã đơn vị cấp 2</th>
            <th data-field="unit2_name">Đơn vị cấp 2</th>
            <th data-field="unit3_code">Mã đơn vị cấp 3</th>
            <th data-field="unit3_name">Đơn vị cấp 3</th> --}}
            <th data-field="position_name">{{ trans('laprofile.position') }}</th>
            <th data-field="titles_name">{{ trans('latraining.title') }}</th>
            <th data-field="training_program_name">{{ trans('laprofile.training_program_name') }}</th>
            <th data-field="subject_name">{{ trans('latraining.subject_name') }}</th>
            <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
            <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
            <th data-field="training_unit">{{ trans('lareport.training_unit') }}</th>
            <th data-field="training_type">{{ trans('latraining.training_type') }}</th>
            <th data-field="training_address">{{ trans('latraining.training_location') }}</th>
            <th data-field="course_time">{{ trans('lareport.duration') }}</th>
            <th data-field="start_date">{{ trans('latraining.from_date') }}</th>
            <th data-field="end_date">{{ trans('latraining.to_date') }}</th>
            <th data-field="time_schedule">{{ trans('latraining.time') }}</th>
            <th data-field="cost_held">{{ trans('lareport.average_fee_open') }}</th>
            <th data-field="cost_training">{{ trans('lareport.average_fee_training_department') }}</th>
            <th data-field="cost_external">{{ trans('lareport.average_fee_outside') }}</th>
            <th data-field="cost_teacher">{{ trans('lareport.average_fee_lecture') }}</th>
            <th data-field="cost_student">{{ trans('latraining.student_cost') }}</th>
            <th data-field="cost_total">{{ trans('lareport.total_cost') }}</th>
            <th data-field="time_commit">{{ trans('latraining.coimmitted_date') }}</th>
            <th data-field="time_commit_formatter">{{ trans('lareport.coimmitted_time') }}</th>
            <th data-field="time_rest">{{ trans('lareport.deadline') }}</th>
            <th data-field="cost_refund">{{ trans('latraining.reimbursement_costs') }} (VND)</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    $(document).ready(function () {

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
            if(form.valid()){
                var table = new BootstrapTable({
                    url: $('#bootstraptable').data('url'),
                });
                // table.submit();
            }

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

        $('#title_id').on('change', function () {
            var title_id = $(this).select2('val');

            $('input[name=title_id]').val(title_id);
        });
    });
</script>
