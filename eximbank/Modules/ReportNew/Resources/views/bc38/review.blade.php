<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC38">
    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="choose_unit">{{ trans('latraining.choose_unit') }}</label>
                </div>
                <div class="col-md-9">
                    @include('backend.form_choose_unit')
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('latraining.title') }}</label>
                </div>
                <div class="col-md-9 type">
                    <select class="form-control load-title" id="title_id" data-placeholder="{{ trans('latraining.title') }}">
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="title_id" value="">
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
    <input type="hidden" name="isSubmit" class="isSubmit" value="0">
</form>
<br>
<div class="table-responsive">
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" rowspan="2" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
                <th data-field="code" rowspan="2" data-width="5%">{{ trans('lasetting.employee_code') }}</th>
                <th data-field="full_name" rowspan="2" class="text-nowrap">{{ trans('lasetting.employee_name') }}</th>
                <th data-field="title_name" rowspan="2" class="text-nowrap">{{ trans('lacategory.title') }}</th>
                <th data-field="unit_name" rowspan="2" class="text-nowrap">{{ trans('lamenu.unit') }}</th>

                @foreach ($allCourse as $course)
                    <th colspan="3" data-class="text-center">
                        <p class="mb-0">({{ $course->code }}) {{ $course->name }}</p>
                        <p class="mb-0">{{ get_date($course->start_date) }} {{ $course->end_date ? '=> '. get_date($course->end_date) : '' }}</p>
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach ($allCourse as $course)
                    <th data-field="register_{{ $course->course_id }}_{{ $course->course_type }}" data-align="center">{{ trans('labutton.register') }}</th>
                    <th data-field="score_{{ $course->course_id }}_{{ $course->course_type }}" data-align="center">{{ trans('latraining.score') }}</th>
                    <th data-field="result_{{ $course->course_id }}_{{ $course->course_type }}" data-align="center">{{ trans('laprofile.result') }}</th>
                @endforeach
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
            $('.isSubmit').val(1);
            e.preventDefault();
            if(form.valid()){
                $(this).closest('form');
                table.submit();
            }
        });

        $("select").on("select2:close", function (e) {
            $(this).valid();
        });

        $('#btnExport').on('click',function (e) {
            $('.isSubmit').val(1);
            e.preventDefault();
            if(form.valid()){
                $(this).closest('form');
                $(this).closest('form').submit();
            }
            return false
        });

        $('#title_id').on('change', function () {
            var title_id = $(this).select2('val');
            $('input[name=title_id]').val(title_id);
        });
    });
</script>
