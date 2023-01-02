<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">    
    @csrf
    <input type="hidden" name="report" value="BC23">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-4 control-label ">
                    <label>{{trans('backend.choose_title')}} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-title" name="title_id" id="title_id" data-placeholder="{{trans('backend.choose_title')}}">
                        @if($title)
                            <option value="{{ $title->id }}"> {{ $title->name }}</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <button id="btnSearch" class="btn">{{trans('labutton.view_report')}}</button>
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
            <th data-field="name" rowspan="2" data-width="300">{{ trans('latraining.title') }}</th>
            <th data-field="employees" rowspan="2" data-width="200">{{ trans('lareport.num_officer') }}</th>
            <th colspan="{{ $levelSubjects ? count($levelSubjects)*2 : 1 }}">{{ trans('lareport.num_finished_officer') }}</th>
        </tr>
        <tr>
            @if($levelSubjects)
            @foreach ($levelSubjects as $item)
                <th data-field="num_{{ $item->subject_id }}"  data-width="200">{{$item->subject->name}}</th>
                <th data-field="rate_{{ $item->subject_id }}"  data-width="200">Tỷ lệ %</th>
            @endforeach
            @endif
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
                title_id: {required : true},
            },
            messages : {
                title_id: {required : "{{ trans('latraining.choose_title') }}"},
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
