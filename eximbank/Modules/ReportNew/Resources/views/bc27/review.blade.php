@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC27">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('latraining.training_type') }}</label>
                </div>
                <div class="col-md-9">
                    <select class="form-control select2" name="course_type" data-placeholder="{{ trans('latraining.training_type') }}">
                        <option value=""></option>
                        <option value="1">{{ trans("latraining.online") }}</option>
                        <option value="2">{{ trans("latraining.offline") }}</option>
                    </select>
                </div>
            </div>
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
                <th data-formatter="course_formatter">{{ trans('latraining.subject_name') }}</th>
                <th data-formatter="course_date_formatter">{{ trans('latraining.training_time') }}</th>
                <th data-field="course_time">{{ trans('lareport.duration') }} ({{ trans('latraining.session') }})</th>
                <th data-field="num_user" data-align="center">{{ trans('lasuggest_plan.number_student') }}</th>
                @foreach($training_cost as $cost)
                    <th data-field="cost{{ $cost->id }}" data-align="center">{{ $cost->name }}</th>
                @endforeach
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

    function course_formatter(value, row, index) {
        return row.name +' ('+ row.code +')' ;
    }

    function course_date_formatter(value, row, index) {
        return row.start_date + (row.end_date ? (' <i class="fa fa-arrow-right"></i> '+ row.end_date) : '');
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
