<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC16">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label for="month">{{trans('latraining.choose_form')}}</label>
                </div>
                <div class="col-md-8">
                    <select name="type" class="form-control select2" data-placeholder="{{trans('latraining.choose_form')}}">
                        <option value=""></option>
                        <option value="1">{{trans("latraining.internal")}}</option>
                        <option value="2">{{trans("latraining.outside")}}</option>
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
                <th data-field="code" data-width="15px">{{ trans('lacategory.code') }}</th>
                <th data-field="name">{{ trans('lacategory.name') }}</th>
                <th data-field="title">{{ trans('lacategory.title') }}</th>
                <th data-field="teacher_type" data-width="15px">{{ trans('latraining.teacher_type') }}</th>
                <th data-field="created_time" data-width="10px" data-align="center">{{ trans('lareport.date_became_teacher') }}</th>
                <th data-field="total_hour" data-width="5px" data-align="center">{{ trans('lareport.total_teaching_hour') }}</th>
                <th data-field="rank" data-width="5px" data-align="center">{{ trans('lacategory.rank') }}</th>
                <th data-field="num_course" data-width="5px" data-align="center">{{ trans('lareport.num_course_teacher') }}</th>
                <th data-field="partner">{{ trans('lacategory.partner') }}</th>
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
