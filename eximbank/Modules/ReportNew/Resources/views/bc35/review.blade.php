@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC35">
    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.date_from') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-9 type">
                    <input type="text" name="from_date" class="form-control datepicker-date" value="{{ $firstDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.date_to') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-9 type">
                    <input type="text" name="to_date" class="form-control datepicker-date" value="{{ $lastDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('latraining.status')}}</label>
                </div>
                <div class="col-md-9 type">
                    <select class="form-control select2" id="status" data-placeholder="{{trans('lareport.training_type')}}" >
                        <option value=""></option>
                        <option value="1">Chưa duyệt</option>
                        <option value="2">Đã duyệt</option>
                        <option value="3">Từ chối</option>
                        <option value="4">Đang diễn ra</option>
                        <option value="5">Chờ kiểm tra</option>
                        <option value="6">Kết thúc</option>
                    </select>
                    <input type="hidden" name="status" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('lareport.subject_training')}}</label>
                </div>
                <div class="col-md-9 type">
                    <select class="form-control load-subject" id="subject_id" data-placeholder="{{trans('lareport.subject_training')}}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="subject_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('lareport.training_type')}}</label>
                </div>
                <div class="col-md-9 type">
                    <select class="form-control select2" id="course_type" data-placeholder="{{trans('lareport.training_type')}}" >
                        <option value=""></option>
                        <option value="1">{{ trans("latraining.online") }}</option>
                        <option value="2">{{ trans("latraining.offline") }}</option>
                    </select>
                    <input type="hidden" name="course_type" value="">
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" data-formatter="index_formatter">#</th>
                <th data-field="code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="name">{{ trans('lacourse.course_name') }}</th>
                <th data-field="name_course_type">Hình thức đào tạo</th>
                <th data-field="start_date">Thời gian bắt đầu</th>
                <th data-field="end_date">Thời gian kết thúc</th>
                <th data-field="training_form_name">Loại hình đào tạo</th>
                <th data-field="status_name">{{trans('lareport.status')}}</th>
                <th data-field="total_register">SLHV</th>
                <th data-field="total_complete">SLHV đã hoàn thành</th>
                <th data-field="rate_complete">Tỷ lệ HV hoàn thành</th>
                <th data-field="actual_amount">Chi phí đào tạo</th>
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
                to_date: {required : true}
            },
            messages : {
                from_date: {required : "Chọn thời gian bắt đầu"},
                to_date: {required : "Chọn thời gian kết thúc"}
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

        $('#course_type').on('change', function () {
            var course_type = $(this).select2('val');
            $('input[name=course_type]').val(course_type);
        });

        $('#status').on('change', function () {
            var status = $(this).select2('val');
            $('input[name=status]').val(status);
        });

        $('#subject_id').on('change', function () {
            var subject_id = $(this).select2('val');
           $('input[name=subject_id]').val(subject_id);
        });
    });
</script>
