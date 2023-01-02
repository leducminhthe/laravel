<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC33">
    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-md-7">

            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('lasurvey.survey') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-9 type">
                    <select class="form-control load-survey" id="survey_id" data-placeholder="{{ trans('lasurvey.survey') }}">
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="survey_id" value="">
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
            {{-- @for($i = 1; $i <= 6; $i++)
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="unit_id_{{ $i }}">{{ data_locale($level_name($i)->name, $level_name($i)->name_en) }}</label>
                    </div>
                    <div class="col-md-9">
                        <select name="unit_id" id="unit_id_{{ $i }}" class="unit_id load-unit" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. data_locale($level_name($i)->name, $level_name($i)->name_en) }} --" data-level="{{ $i }}" data-parent="{{ empty($unit[$i-1]->id) ? '' : $unit[$i-1]->id }}" data-loadchild="unit_id_{{ $i+1 }}">
                        </select>
                    </div>
                </div>
            @endfor --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="choose_unit">{{ trans('latraining.choose_unit') }}</label>
                </div>
                <div class="col-md-9">
                    @include('backend.form_choose_unit')
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
</form>
<br>
<div class="table-responsive">
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report_new.getData').'?id='}}">
        <thead>
        <tr class="tbl-heading">
        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

    $(document).ready(function () {

        var form = $('#form-search');
        form.validate({
            ignore: [],
            rules : {
                survey_id: {
                    required: {

                    }
                }
            },
            messages : {
                survey_id: {required : "Chọn khảo sát"},
            },
            errorPlacement: function (error, element) {
                var name = $(element).attr("name");
                error.appendTo($(element).parent());
            },
        });

        $('#btnSearch').on('click',function (e) {
            e.preventDefault();
            if(form.valid()){
                $('#bootstraptable').bootstrapTable('destroy');
               $('#bootstraptable').find("tbody").empty();
                $('#bootstraptable').find("thead").empty();
                var survey_id = $('#survey_id').val();
                $.ajax({
                    url: '{{route("module.report_new.get_question_survey")}}',
                    type: 'POST',
                    data: {'survey_id':survey_id},
                    dataType: 'json',
                    success: function (data) {
                        columns = [];

                        columns.push({field:'code',title:'{{ trans('laprofile.employee_code') }}',sortable:false});
                        columns.push({field:'full_name',title:'{{ trans('laprofile.full_name') }}',sortable:false});
                        columns.push({field:'email',title:'Email',sortable:false});
                        columns.push({field:'phone',title:'{{ trans('laprofile.phone') }}',sortable:false});
                        columns.push({field:'title_name',title:'{{ trans('laprofile.title') }}',sortable:false});
                        columns.push({field:'survey_user_status',title:'{{ trans('latraining.status') }}',sortable:false});

                        $('#bootstraptable').bootstrapTable({
                            url: $('#bootstraptable').data('url') + survey_id,
                            locale: 'vi-VN',
                            sidePagination: 'server',
                            pagination: true,
                            sortName: 'user_id',
                            sortOrder: 'desc',
                            toggle: 'table',
                            search: this.search,
                            pageSize: 20,
                            idField: 'id',
                            cache: false,
                            columns:columns,
                            queryParams: function (params) {
                                let field_search = $('#form-search').serializeArray();
                                $.each(field_search, function (i, item) {
                                    params[item.name] = item.value;
                                });
                                return params;
                            }
                        });
                    }
                });

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

        $('#title_id').on('change', function () {
            var title_id = $(this).select2('val');
            $('input[name=title_id]').val(title_id);
            if(title_id) {
                console.log(1);
                // $('.unit_id').val('');
                $(".unit_id").val('').trigger("change");
            }
        });

        $('#survey_id').on('change', function () {
            var survey_id = $(this).select2('val');
            $('input[name=survey_id]').val(survey_id);

        });

        $('.unit_id').on('change', function () {
            if($(this).val()) {
                $('input[name=title_id]').val('');
                $("#title_id").val('').trigger("change");
            }
        });


    });
</script>
