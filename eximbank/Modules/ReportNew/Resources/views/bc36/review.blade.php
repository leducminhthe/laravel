@php
    $year = date('Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC36">
    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-md-7">
            <div class="form-group row wrapped_title">
                <div class="col-md-3 control-label">
                    <label>{{ trans('lacategory.title') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-9">
                    <select name="title" id="title_id" class="form-control load-title" data-placeholder="-- {{ trans('lacategory.title') }} --">
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('lamenu.category') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-9">
                    <select class="form-control select2" id="training_title_category" data-placeholder="-- {{ trans('lamenu.category') }} --">
                    </select>
                    <input type="hidden" name="training_title_category" value="">
                </div>
            </div>
            {{-- <div class="form-group row wrapped_user">
                <div class="col-md-3 control-label">
                    <label>{{ trans('lamenu.user') }}</label>
                </div>
                <div class="col-md-9">
                    <select class="form-control load-user" id="user_id" data-placeholder="-- {{ trans('lamenu.user') }} --" multiple>
                    </select>
                    <input type="hidden" name="users" value="">
                </div>
            </div> --}}
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table text-nowrap" data-url="{{route('module.report_new.getData')}}">
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
                title: {required : true},
                training_title_category: {required : true},
            },
            messages : {
                title: {required : "Chức danh"},
                training_title_category: {required : "Danh mục"},
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
                var title = $('#title_id').val();
                var training_title_category = $('#training_title_category').val();
                $.ajax({
                    url: '{{ route("module.report_new.filter") }}',
                    type: 'POST',
                    data: {'training_title_category': training_title_category, 'type': 'TrainingTitle'},
                    dataType: 'json',
                    success: function (data) {
                        columns = [];
                        columns.push({field:'code',title:'{{ trans('latraining.employee_code') }}',sortable:false});
                        columns.push({field:'full_name',title:'{{ trans('latraining.fullname') }}',sortable:false});
                        columns.push({field:'title_name',title:'{{ trans('latraining.title') }}',sortable:false});
                        columns.push({field:'unit_name',title:'{{ trans('lareport.unit_direct') }}',sortable:false});
                        columns.push({field:'email',title:'Email',sortable:false});
                        columns.push({field:'join_company',title:'{{ trans('latraining.day_work') }}',sortable:false});
                        columns.push({field:'training_category_name',title:'{{ trans('latraining.object') }}', sortable:false});

                        data.results.forEach(function(item, index) {
                            columns.push({
                                field: 'subject_'+ item.id,
                                title: item.text,
                                sortable: false,
                            });
                        });

                        $('#bootstraptable').bootstrapTable({
                            url: $('#bootstraptable').data('url'),
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

        $('#user_id').on('change', function () {
            var users = $(this).select2('val');
           $('input[name=users]').val(users);
        });

        $('.load-title').on('change', function() {
            var titleId = $(this).val()
            $.ajax({
                url: '{{ route('module.ajax_training_by_title_category') }}',
                type: 'post',
                data: {
                    titleId: titleId,
                },
            }).done(function(result) {
                var data=[];
                $.each(result, function (index, obj) {
                    data.push({
                        id: obj.id,
                        text: obj.name,
                    });
                });
                $('#training_title_category').empty().select2({
                    data: data,
                    width: '100%',
                }).val('').trigger('change');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        })

        var dataDetail = '';
        $('#training_title_category').on('select2:select', function () {
            var categoryId = $(this).select2('val');
            $('input[name=training_title_category]').val(categoryId);
        });
    });
</script>
