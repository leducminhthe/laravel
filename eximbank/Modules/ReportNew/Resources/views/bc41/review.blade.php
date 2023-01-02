@php
    $year = date('Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC41">
    <div class="row m-0">
        <div class="col-md-12">
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="total" data-align="center">Tổng SL</th>
                <th data-field="percent" data-align="center">Trọng số</th>
                <th data-field="score" data-align="center">Điểm chuẩn</th>
                <th data-field="num_not_rating" data-align="center">SL chưa đánh giá</th>
                <th data-field="1_30" data-align="center">1-30%</th>
                <th data-field="30_50" data-align="center">30-50%</th>
                <th data-field="50_60" data-align="center">50-60%</th>
                <th data-field="60_70" data-align="center">60-70%</th>
                <th data-field="70_80" data-align="center">70-80%</th>
                <th data-field="80_90" data-align="center">80-90%</th>
                <th data-field="90_100" data-align="center">90-100%</th>
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

        $('#title_id').on('change', function () {
            var title_id = $(this).select2('val');

            $('input[name=title_id]').val(title_id);
        });
    });
</script>
