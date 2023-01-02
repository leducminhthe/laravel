@php
    $year = date('Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC25">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('ladashboard.subject') }}</label>
                </div>
                <div class="col-md-9">
                    <select class="form-control load-subject" id="subject_id" data-course_type="" data-placeholder="{{ trans('latraining.subject_name') }}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="subject_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label required">
                    <label>{{trans('backend.month')}} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-9 type">
                    <select class="form-control " name="month" data-placeholder="{{trans('backend.choose_month')}}">
                        @for ($month=1;$month<=12;$month++)
                            <option value="{{$month}}">{{sprintf('%20d',$month)}}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label required">
                    <label>{{trans('backend.year')}} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-9 type">
                    <input type="text" name="year" id="year" class="form-control datepicker-year" value="{{ $year }}">
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <button  id="btnSearch" class="btn">{{trans('labutton.view_report')}}</button>
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
            <th data-align="center" rowspan="2" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
            <th data-field="subject_code" rowspan="2" data-width="300">{{ trans('laprofile.subject_code') }}</th>
            <th data-field="subject_name" rowspan="2" data-width="200">{{ trans('ladashboard.subject') }}</th>

            @for($i=1;$i<= 12;$i++)
                <th colspan="4">{{ trans('ladashboard.month') }} {{$i}}</th>
            @endfor
            <th colspan="4">{{ trans('lareport.cumulative_from_first_year') }}</th>
        </tr>
        <tr>
            @for ($i=1; $i<=12;$i++)
                <th data-field="class_{{$i}}"  data-width="200">{{ trans('lareport.num_class') }}</th>
                <th data-field="attend_{{$i}}"  data-width="200">{{ trans('lareport.num_join') }}</th>
                <th data-field="completed_{{$i}}"  data-width="200">{{ trans('lareport.num_finished') }}</th>
                <th data-field="uncompleted_{{$i}}"  data-width="200">{{ trans('lareport.num_unfinished') }}</th>
            @endfor
                <th data-field="sum_class"  data-width="200">{{ trans('lareport.num_class') }}</th>
                <th data-field="sum_attend"  data-width="200">{{ trans('lareport.num_join') }}</th>
                <th data-field="sum_completed"  data-width="200">{{ trans('lareport.num_finished') }}</th>
                <th data-field="sum_uncompleted"  data-width="200">{{ trans('lareport.num_unfinished') }}</th>
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
            cache: false,
        });
        var form = $('#form-search');
        form.validate({
            ignore: [],
            rules : {
                month: {required : true},
                year: {required : true},
            },
            messages : {
                month: {required : "Chọn tháng"},
                year: {required : "Chọn năm"},
            },
            errorPlacement: function (error, element) {
                var name = $(element).attr("name");
                error.appendTo($(element).parent());
            },
        });
        $('#btnSearch').on('click',function (e) {
            e.preventDefault();
            if(form.valid()){
                $(this).closest('form').append('<input type="hidden" name="isSubmit" value=1>');
                table.submit();
            }

        });
        // $("select").on("select2:close", function (e) {
        //     $(this).valid();
        // });
        $('#btnExport').on('click',function (e) {
            e.preventDefault();
            if(form.valid())
                $(this).closest('form').submit();
            return false
        });

        $('#subject_id').on('change', function () {
            var subject_id = $(this).select2('val');

           $('input[name=subject_id]').val(subject_id);
        });
    });
</script>
