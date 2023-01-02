@php
    $year = date('Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC31">
    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('lanote.year') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-9">
                    <select name="year" class="form-control select2" data-placeholder="{{ trans('lanote.year') }}">
                        <option value=""></option>
                        @for($i = 2020; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('latraining.user') }}</label>
                </div>
                <div class="col-md-9">
                    <select class="form-control load-all-user" id="user_id" data-placeholder="{{ trans('latraining.user') }}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="user_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('latraining.title') }}</label>
                </div>
                <div class="col-md-9 type">
                    <select class="form-control load-title" id="title_id" data-placeholder="{{ trans('latraining.title') }}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="title_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="choose_unit">{{ trans('latraining.choose_unit') }}</label>
                </div>
                <div class="col-md-9">
                    @include('backend.form_choose_unit')
                </div>
            </div>
        </div>
        <input type="hidden" name="show" id="show" value="0">
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
                <th data-field="full_name">{{ trans('latraining.fullname') }}</th>
                <th data-field="unit_name">{{ trans('latraining.unit') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="total_time" data-align="center">{{ trans('lareport.spend_learned_summary') }}</th>
                <th data-field="user_time_kpi" data-align="center">KPI (giờ)</th>
                <th data-field="year" data-align="center">{{ trans('lanote.year') }}</th>
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
            $('#show').val(1);
            if(form.valid())
                table.submit();
        });

        $("select").on("select2:close", function (e) {
            $(this).valid();
        });

        $('#btnExport').on('click',function (e) {
            e.preventDefault();
            $('#show').val(1);
            if(form.valid())
                $(this).closest('form').submit();
            return false
        });

        $('#user_id').on('change', function () {
            var user_id = $(this).select2('val');

           $('input[name=user_id]').val(user_id);
        });

        $('#title_id').on('change', function () {
            var title_id = $(this).select2('val');

            $('input[name=title_id]').val(title_id);
        });
    });
</script>
