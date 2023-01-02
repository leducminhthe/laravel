<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC30">
    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('latraining.training_type') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control select2" name="course_type" id="course_type" data-placeholder="{{ trans('latraining.training_type') }}" multiple>
                        <option value=""></option>
                        <option value="1">{{ trans('latraining.online') }}</option>
                        <option value="2">{{ trans('latraining.offline') }}</option>
                    </select>
                    <input type="hidden" name="course_type" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('lareport.subject_training') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control load-subject" id="subject_id" data-course_type="" data-placeholder="{{ trans('lareport.subject_training') }}" multiple>
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
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" id="btnSearch" class="btn">{{ trans('labutton.view_report') }}</button>
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
                <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th data-field="start_date" data-align="center">{{ trans('lareport.start_time') }}</th>
                <th data-field="end_date" data-align="center">{{ trans('lareport.end_time') }}</th>
                <th data-field="quality_course" data-align="center">{{ trans('lareport.course_general_quantity') }} (%)</th>
                <th data-field="program_content" data-align="center">{{ trans('lareport.program_content') }} (%)</th>
                <th data-field="teacher" data-align="center">{{ trans('lareport.teacher') }} (%)</th>
                <th data-field="organization" data-align="center">{{ trans('lacategory.organize') }} (%)</th>
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
                course_type: {required : true},
            },
            messages : {
                course_type: {required : "Chọn hình thức đào tạo"},
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

        $('#subject_id').on('change', function () {
            var subject_id = $(this).select2('val');

           $('input[name=subject_id]').val(subject_id);
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

        $('#course_type').on('change', function () {
            var course_type = $(this).select2('val');
            $('input[name=course_type]').val(course_type);

            $("#subject_id").empty();
            $('#subject_id').data('course_type', course_type);
            $('#subject_id').trigger('change');
        });
    });
</script>
