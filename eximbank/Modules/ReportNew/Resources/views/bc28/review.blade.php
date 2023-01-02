@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC28">
    <div class="row">
        <div class="col-md-2">
        </div>
        <div class="col-md-8">
            <div class="form-group row">
                <div class="col-md-2 control-label">
                    <label>{{ trans('backend.quiz') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8 type">
                    <select name="quiz_id" id="quiz_id" class="form-control load-quizs" data-placeholder="-- {{ trans('backend.quiz') }} --">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2 control-label">
                    <label>{{ trans('latraining.part') }} </label>
                </div>
                <div class="col-md-8">
                    <select class="form-control load-part-quiz-online" id="quiz_part" data-quiz_id="" data-placeholder="-- {{ trans('latraining.part') }} --" multiple>
                    </select>
                    <input type="hidden" name="quiz_part" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 text-center">
                    <h5 style="color: red">Lưu ý: Báo cáo này không hổ trợ kỳ thi tính điểm trung bình</h5>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-7">
                    <button type="submit" id="btnSearch" class="btn">{{trans('labutton.view_report')}}</button>
                    <button id="btnExport" class="btn" name="btnExport">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> {{ trans('labutton.export_excel') }}
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>
<br>
<div class="table-responsive">
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th rowspan="2" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
                <th rowspan="2" data-field="quiz_name">{{ trans('latraining.quiz_name') }}</th>
                <th rowspan="2" data-field="type_name">{{ trans('lareport.department_in_charge') }}</th>
                <th rowspan="2" data-field="user_code">{{ trans('latraining.employee_code') }}</th>
                <th rowspan="2" data-field="full_name">{{ trans('latraining.fullname') }}</th>
                <th rowspan="2" data-field="title_name">{{ trans('latraining.title') }}</th>
                <th rowspan="2" data-field="unit_name">{{ trans('lareport.unit_direct') }}</th>
                <th rowspan="2" data-field="unit_parent_name">{{ trans('latraining.unit_manager') }}</th>
                <th rowspan="2" data-field="email">Email</th>
                <th rowspan="2" data-field="part_name">{{ trans('latraining.name_part') }}</th>
                <th rowspan="2" data-field="status" data-align="center">{{ trans('lareport.status') }}</th>
                <th rowspan="2" data-field="count_attempt" data-align="center">Số lần thi</th>
                <th rowspan="2" data-field="start_date" data-align="center">{{ trans('lareport.started_at') }}</th>
                <th rowspan="2" data-field="end_date" data-align="center">{{ trans('lareport.end_time') }}</th>
                <th rowspan="2" data-field="limit_time" data-align="center">Thời gian làm bài (Phút)</th>
                <th rowspan="2" data-field="execution_time" data-align="center">{{ trans('lareport.time_done') }}</th>
                <th rowspan="2" data-field="score" data-align="center">{{ trans('latraining.score') }}</th>
                <th colspan="2" data-align="center">{{ trans('lareport.num_question') }}</th>
                <th colspan="2" data-align="center">{{ trans('lareport.ratio') }}</th>
            </tr>
            <tr class="tbl-heading">
                <th data-field="num_true" data-align="center">{{ trans('latraining.true') }}</th>
                <th data-field="num_false" data-align="center">{{ trans('latraining.false') }}</th>
                <th data-field="percent_true" data-align="center">% {{ trans('latraining.true') }}</th>
                <th data-field="percent_false" data-align="center">% {{ trans('latraining.false') }}</th>
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
                from_date : {required : true},
                to_date : {required : true},
                quiz_id : {required : true},
            },
            messages : {
                from_date : {required : "Chọn thời gian bắt đầu"},
                to_date : {required : "Chọn thời gian kết thúc"},
                quiz_id : {required : "Chọn kì thi"},
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

        $('#quiz_id').on('change', function() {
            var quizId = $(this).val()
            $("#quiz_part").empty();
            $('#quiz_part').attr('data-quiz_id', quizId);
            $('#quiz_part').trigger('change');
        })

        $('#quiz_part').on('select2:select', function () {
            var quizPart = $(this).select2('val');
            $('input[name=quiz_part]').val(quizPart);
        });
    });
</script>
