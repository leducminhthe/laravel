<style>
    #bootstraptable p {
        margin-bottom: 0px;
        color: #212529;
    }
</style>
@php
    $year = date('Y');
@endphp
<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC37">
    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-md-8">
            <div class="form-group row ">
                <div class="col-md-2 control-label">
                    <label>{{ trans('latraining.quiz_list') }} (<span class="text-danger">*</span>)</label>
                </div>
                <div class="col-md-10">
                    <select name="quiz_id" id="quiz_id" class="form-control load-quizs" data-placeholder="-- {{ trans('latraining.quiz_list') }} --">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2 control-label">
                    <label>{{ trans('latraining.part') }} </label>
                </div>
                <div class="col-md-10">
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
    var arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    function index_formatter(value, row, index) {
        return (index + 1);
    }

    $(document).ready(function () {
        var form = $('#form-search');
        form.validate({
            ignore: [],
            rules : {
                quiz_id: {required : true},
            },
            messages : {
                quiz_id: {required : "Kỳ thi"},
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
                var quiz_id = $('#quiz_id').val();
                $.ajax({
                    url: '{{ route("module.report_new.filter") }}',
                    type: 'POST',
                    data: {'quiz_id': quiz_id, 'type': 'Quiz'},
                    dataType: 'json',
                    success: function (data) {
                        columns = [];
                        columns.push({field:'quiz_name', title: '{{ trans('latraining.quiz_name') }}', sortable:false});
                        columns.push({field:'part_name', title: '{{ trans('latraining.part') }}', sortable:false});
                        columns.push({field:'date_exam', title: 'Ngày thi', sortable:false});
                        columns.push({field:'limit_time', title: ('{{ trans('lareport.duration') }}' + '(Phút)'), sortable:false});
                        columns.push({field:'code', title: '{{ trans('latraining.employee_code') }}', sortable:false});
                        columns.push({field:'full_name', title: '{{ trans('latraining.fullname') }}', sortable:false});
                        columns.push({field:'title_name', title: '{{ trans('latraining.title') }}', sortable:false});
                        columns.push({field:'unit_name', title: '{{ trans('lareport.unit_direct') }}', sortable:false});
                        columns.push({field:'email', title: 'Email', sortable:false});
                        columns.push({field:'unit_create_quiz', title: 'Đơn vị ra đề', sortable:false});
                        columns.push({field:'result', title: '{{ trans('latraining.result') }}', sortable:false});
                        columns.push({field:'score', title: '{{ trans('latraining.score') }}', sortable:false});
                        columns.push({field:'question', title: '{{ trans('latraining.question') }}', sortable:false});
                        columns.push({field:'corect_answer', title: 'Đáp án đúng', sortable:false});
                        columns.push({field:'choose_answer', title: 'Đáp án chọn', sortable:false});

                        for (let index = 1; index <= data; index++) {
                            columns.push({
                                field: 'answer_'+ index,
                                title: 'Câu trả lời '+ arr_char[index - 1],
                                sortable: false,
                            });
                        }

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

        $('#quiz_id').on('change', function() {
            var quizId = $(this).val()
            $("#quiz_part").empty();
            $('#quiz_part').attr('data-quiz_id', quizId);
            $('#quiz_part').trigger('change');
        })

        $('#quiz_part').on('change', function () {
            var quizPart = $(this).select2('val');
            $('input[name=quiz_part]').val(quizPart);
        });
    });
</script>
