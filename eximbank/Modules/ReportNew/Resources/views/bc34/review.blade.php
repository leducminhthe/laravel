<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC34">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('lareport.question_category')}} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control select2" name="question_category_id" data-placeholder="{{trans('lareport.question_category')}}">
                        <option value=""></option>
                        @if($question_categories)
                            @foreach($question_categories as $question_category)
                                <option value="{{ $question_category->id }}">{{ $question_category->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-field="index" data-formatter="index_formatter" rowspan="2" data-align="center" data-width="5%"> {{trans('latraining.stt')}}</th>
                <th data-field="name" rowspan="2"> {{trans('lareport.question_category')}}</th>
                <th colspan="4" data-align="center">{{trans('lareport.scoring_question')}}</th>
                <th colspan="3" data-align="center">{{trans('lareport.question_graded')}}</th>
            </tr>
            <tr class="tbl-heading">
                <th data-field="scoring_question_quantity" data-align="center" data-width="5%"> {{trans('latraining.quantity')}}</th>
                <th data-field="scoring_question_active" data-align="center" data-width="5%"> {{trans('latraining.activiti')}}</th>
                <th data-field="scoring_question_used" data-align="center" data-width="5%">{{trans('latraining.used')}}</th>
                <th data-field="scoring_question_ratio_correct" data-align="center" data-width="5%">{{trans('latraining.ratio_correct')}}</th>

                <th data-field="question_graded_quantity" data-align="center" data-width="5%"> {{trans('latraining.quantity')}}</th>
                <th data-field="question_graded_active" data-align="center" data-width="5%"> {{trans('latraining.activiti')}}</th>
                <th data-field="question_graded_used" data-align="center" data-width="5%">{{trans('latraining.used')}}</th>
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
                question_category_id: {required : true},
            },
            messages : {
                question_category_id: {required : "Chọn danh mục"},
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
