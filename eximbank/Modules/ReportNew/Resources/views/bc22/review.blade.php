<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC22">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-4 control-label required">
                    <label>{{ trans('backend.date_from') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="from_date" value="{{date('d/m/Y')}}" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label required">
                    <label>{{ trans('backend.date_to') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="to_date" value="{{date('t/m/Y')}}" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label ">
                    <label>{{trans('backend.type')}} </label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control " name="type" id="type_id" >
                        <option value="1">{{ trans('lamenu.merge_subject') }}</option>
                        <option value="2">{{ trans('lamenu.split_subject') }}</option>
                    </select>
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap" data-url="{{route('module.report_new.getData')}}">
        <thead>
        <tr class="tbl-heading">
            <th data-align="center" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
            <th data-field="subject_merge_split_code" class="subject_code">{{ trans('lareport.new_subject_code') }}</th>
            <th data-field="subject_merge_split_name" class="subject_name" data-width="300">{{ trans('lareport.new_subject_name') }}</th>
            <th data-field="subject_merges_splits" class="subjects" data-width="300">{{ trans('lareport.subject_to_merge') }}</th>
            <th data-field="created_date">{{ trans('lareport.date_merge') }}</th>
            <th data-field="user_code">{{ trans('latraining.employee_code') }}</th>
            <th data-field="full_name">{{ trans('latraining.fullname') }}</th>
            <th data-field="email">Email</th>
            <th data-field="phone">{{ trans('latraining.phone') }}</th>
            <th data-field="area_name">{{ trans('lacategory.area') }}</th>
            <th data-field="unit1_name">{{ trans('lareport.unit_direct') }}</th>
            <th data-field="unit2_name">{{ trans('lareport.unit_management') }}</th>
            {{-- <th data-field="unit1_code">Mã đơn vị 1</th>
            <th data-field="unit1_name">{{ trans('latraining.unit') }} 1</th>
            <th data-field="unit2_code">Mã đơn vị 2</th>
            <th data-field="unit2_name">{{ trans('latraining.unit') }} 2</th>
            <th data-field="unit3_code">Mã đơn vị 3</th>
            <th data-field="unit3_name">{{ trans('latraining.unit') }} 3</th> --}}
            <th data-field="title_name">{{ trans('laprofile.position') }}</th>
            <th data-field="position_name">{{ trans('latraining.title') }}</th>
            <th data-field="note">{{ trans('latraining.note') }}</th>
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
            let $type = $(this).closest('form').find("select[name=type]").val();
            if($type==1){
                $('.subject_code').find('div.th-inner').html('Mã');
                $('.subject_name').find('div.th-inner').html('Tên chuyên đề mới');
                $('.subjects').find('div.th-inner').html('Chuyên đề cần gộp');
            }else if($type==2){
                $('.subject_code').find('div.th-inner').html('Mã');
                $('.subject_name').find('div.th-inner').html('Tên chuyên đề cần tách');
                $('.subjects').find('div.th-inner').html('Chuyên đề mới');
            }
            if(form.valid()){
                $(this).closest('form').append('<input type="hidden" name="isSubmit" value=1>');
                table.submit();
            }

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
