@section('header')
    <link rel="stylesheet" href="{{asset('/css/bootstrap-select.min.css')}}">
    <script src="{{asset('/js/bootstrap-select.min.js')}}"></script>
@endsection
@php
    $date = date('Y-m-d');
    $firstDate = date("Y-m-01", strtotime($date));
    $lastDate = date("Y-m-t", strtotime($date));

    $firstDateFormat = get_date($firstDate, 'd/m/Y');
    $lastDateFormat = get_date($lastDate, 'd/m/Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC05">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_from') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8 pl-1">
                    <input type="text" name="from_date" class="form-control datepicker-date" value="{{ $firstDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_to') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8 pl-1">
                    <input type="text" name="to_date" class="form-control datepicker-date" value="{{ $lastDateFormat }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('lareport.training_type')}}</label>
                </div>
                <div class="col-md-8 pl-1">
                    <select class="form-control select2" id="course_type" data-placeholder="{{trans('lareport.training_type')}}" >
                        <option value=""></option>
                        <option value="1">Trực tuyến</option>
                        <option value="2">{{ trans("latraining.offline") }}</option>
                    </select>
                    <input type="hidden" name="course_type" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('lareport.subject_training')}}</label>
                </div>
                <div class="col-md-8 pl-1">
                    <select class="form-control load-subject" id="subject_id" data-course_type="" data-placeholder="{{trans('lareport.subject_training')}}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="subject_id" value="">
                </div>
            </div>
            {{--<div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('laprofile.area')}}</label>
                </div>
                <div class="col-md-8 pl-1 type">
                    <select name="area" id="area" class="form-control load-area" data-placeholder="-- {{trans('laprofile.area')}} --"></select>
                </div>
            </div>--}}
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('latraining.training_form')}}</label>
                </div>
                <div class="col-md-8 pl-1 type">
                    <select class="form-control load-training-form" id="training_type_id" data-placeholder="{{trans('latraining.training_form')}}" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="training_type_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4 control-label">
                    <label>{{trans('lacategory.area')}}</label>
                </div>
                <div class="col-md-8 pl-1">
                    <select class="form-control load-area-all" name="area_id"  autocomplete="off" data-placeholder="{{trans('lacategory.area_choose')}}" >
                        <option value="">{{trans('lacategory.area_choose')}}</option>
                    </select>
                </div>
            </div>
            {{-- <div class="form-group row">
                <div class="col-sm-4 control-label">
                    <label for="unit_id">{{ trans('lacategory.unit') }}</label>
                </div>
                <div class="col-md-8 pl-1">
                    <select name="unit_id" id="unit_id" class="load-unit-by-level" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. trans('lacategory.unit') }} --"  >
                    </select>
                </div>
            </div> --}}
            <div class="form-group row">
                <div class="col-sm-4 control-label">
                    <label for="choose_unit">{{ trans('latraining.choose_unit') }}</label>
                </div>
                <div class="col-md-8 pl-1">
                    @include('backend.form_choose_unit')
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{trans('laprofile.title')}}</label>
                </div>
                <div class="col-md-8 pl-1 type">
                    <select class="form-control select2" id="title_id" data-placeholder="{{trans('laprofile.title')}}" multiple>
                        <option value="0">Tất cả</option>
                        @foreach($titles as $title)
                            <option value="{{ $title->id }}"> {{ $title->name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="title_id" value="">
                </div>
            </div>

        </div>
        {{--<div class="col-md-6">
            @for($i = 1; $i <= 5; $i++)
                <div class="form-group row">
                    <div class="col-sm-4 control-label">
                        <label for="unit_id_{{ $i }}">{{ data_locale($level_name($i)->name, $level_name($i)->name_en) }}</label>
                    </div>
                    <div class="col-md-8 pl-1">
                        <select name="unit_id" id="unit_id_{{ $i }}" class="load-unit" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. data_locale($level_name($i)->name, $level_name($i)->name_en) }} --" data-level="{{ $i }}" data-parent="{{ empty($unit[$i-1]->id) ? '' : $unit[$i-1]->id }}" data-loadchild="unit_id_{{ $i+1 }}">
                        </select>
                    </div>
                </div>
            @endfor
        </div>--}}
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
                <th data-align="center" data-formatter="index_formatter">#</th>
                <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="course_name" class="text-nowrap">{{ trans('lacourse.course_name') }}</th>
                <th data-field="class_name" class="text-nowrap">{{ trans('latraining.classroom') }}</th>
                <th data-field="user_code">{{trans('laprofile.employee_code')}}</th>
                <th data-field="fullname" class="text-nowrap">{{trans('laprofile.full_name')}}</th>
                <th data-field="email">Email</th>
                <th data-field="phone">{{trans('laprofile.phone')}}</th>
                <th data-field="area_name">{{trans('laprofile.area')}}</th>
                <th data-field="unit_name_1">{{trans('lareport.unit_direct')}}</th>
                <th data-field="unit_name_2">{{trans('lareport.unit_management')}}</th>
                <th data-field="title_name" class="text-nowrap">{{trans('laprofile.title')}}</th>
                <th data-field="training_unit">{{trans('lareport.training_unit')}}</th>
                <th data-field="training_type_name">{{trans('lareport.training_form')}}</th>
                <th data-field="course_time">{{trans('lareport.course_duration')}}</th>
                <th data-field="attendance">{{trans('lareport.course_duration_total')}}</th>
                <th data-field="start_date">{{trans('lareport.from_date')}}</th>
                <th data-field="end_date">{{trans('lareport.to_date')}}</th>
                <th data-field="time_register">Thời gian ghi danh</th>
                <th data-field="time_schedule" class="text-nowrap">{{trans('lareport.time')}}</th>
                <th data-field="entrance_quiz">{{trans('lareport.entrance_exam')}}</th>
                <th data-field="score">{{trans('lareport.score')}}</th>
                <th data-field="result">{{trans('lareport.result')}}</th>
                <th data-field="time_complete">Thời gian hoàn thành</th>
                <th data-field="status_user" class="text-nowrap">{{trans('lareport.status')}}</th>
                <th data-field="note" class="text-nowrap">{{trans('lareport.note')}}</th>
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


        $('#subject_id').on('change', function () {
            var subject_id = $(this).select2('val');

           $('input[name=subject_id]').val(subject_id);
        });

        $('#training_type_id').on('change', function () {
            var training_type_id = $(this).select2('val');

            $('input[name=training_type_id]').val(training_type_id);
        });

        $('#title_id').on("select2:select", function (e) {
            var data = e.params.data.id;
            if(data=='0'){
                $("#title_id > option").prop("selected","selected");
                $("#title_id").trigger("change");
            }
        });

        $('#title_id').on('change', function () {
            var title_id = $(this).select2('val');

            $('input[name=title_id]').val(title_id);
        });
        $('select[name=unit_level]').on('change',function () {
            $('select[name=unit_id]').empty().change();
        })
    });
</script>


