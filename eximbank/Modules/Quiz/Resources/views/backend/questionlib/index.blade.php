@extends('layouts.backend')

@section('page_title', trans('lamenu.questionlib'))
@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/vendor/jquery-treegrid/jquery.treegrid.min.css?v='.time()) }}" />
    <script type="text/javascript" src="{{ asset('styles/vendor/jquery-treegrid/jquery.treegrid.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('styles/vendor/jquery-treegrid/bootstrap-table-treegrid.min.js') }}"></script>
@endsection
@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.questionlib'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')

    <div role="main">
        @if(session()->has('errors'))
            @foreach(session()->get('errors')  as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
            {{\Session::forget('errors')}}
        @endif
        <div class="row">
            <div class="col-md-8">
                @include('quiz::backend.questionlib.filter')
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn" onclick="changeStatus(0,1)" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.enable') }}
                        </button>
                        <button class="btn" onclick="changeStatus(0,0)" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.disable') }}
                        </button>
                    </div>
                    <div class="btn-group">
                        @can('quiz-category-question-create')
                            <a href="javascript:void(0)" class="btn load-modal" data-url="{{ route('module.quiz.questionlib.get_modal') }}"><i class="fa fa-plus-circle"></i> @lang('labutton.add_new')</a>
                        @endcan
                        @can('quiz-category-question-delete')
                            <button class="btn" id="delete-item-libquestion"><i class="fa fa-trash"></i> @lang('labutton.delete')</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="table-question-lib"
               data-tree-enable="true"
               data-page-list="[10, 50, 100, 200, 500]"
               data-page-size="50"
        >
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true" data-width="3%"></th>
                <th data-field="status" data-formatter="status_formatter" data-width="5%" data-align="center">{{ trans('latraining.status') }}</th>
                <th data-field="name" data-formatter="name_formatter">{{ trans('backend.category_questions') }}</th>
                <th data-field="question" data-width="5%" data-align="center" data-formatter="question_formatter">{{ trans('latraining.question') }}</th>
                <th data-field="quantity" data-width="10%" data-align="center">{{ trans('backend.number_questions') }}</th>
                <th data-field="export" data-width="10%" data-align="center" data-formatter="export_question_formatter">{{ trans('latraining.export_question') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var $table_question = $('#table-question-lib');

        // var table = new LoadBootstrapTable({
        //     url: '{{ route('module.quiz.questionlib.getdata_category') }}',
        //     locale: '{{ \App::getLocale() }}'
        // });

        $table_question.bootstrapTable({
            url: '{{ route('module.quiz.questionlib.getdata_category') }}',
            striped: true,
            sidePagination: 'server',
            pagination: true,
            idField: 'id',
            treeShowField: 'name',
            parentIdField: "parent_id",
            onPostBody: function() {
                var columns = $table_question.bootstrapTable('getOptions').columns;
                //if (columns && columns[0][3].visible) {
                $table_question.treegrid({
                        treeColumn: 2,
                        onChange: function() {
                            $table_question.bootstrapTable('resetView')
                        }
                    });
                $table_question.treegrid('getAllNodes').each(function() {
                    if ($(this).treegrid("getRootNodes")) {
                        if(! /(^|\s)treegrid-parent/.test($(this).attr('class'))){
                            ($(this).find('td:eq(2)').prepend('<span class="treegrid-indent-root"></span>'))
                        }
                        if(/(^|\s)treegrid-expanded/.test($(this).attr('class'))) {
                            $(this).find('td:eq(2) span.treegrid-indent').last().css('background-image', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGrSURBVDjLxZO7ihRBFIa/6u0ZW7GHBUV0UQQTZzd3QdhMQxOfwMRXEANBMNQX0MzAzFAwEzHwARbNFDdwEd31Mj3X7a6uOr9BtzNjYjKBJ6nicP7v3KqcJFaxhBVtZUAK8OHlld2st7Xl3DJPVONP+zEUV4HqL5UDYHr5xvuQAjgl/Qs7TzvOOVAjxjlC+ePSwe6DfbVegLVuT4r14eTr6zvA8xSAoBLzx6pvj4l+DZIezuVkG9fY2H7YRQIMZIBwycmzH1/s3F8AapfIPNF3kQk7+kw9PWBy+IZOdg5Ug3mkAATy/t0usovzGeCUWTjCz0B+Sj0ekfdvkZ3abBv+U4GaCtJ1iEm6ANQJ6fEzrG/engcKw/wXQvEKxSEKQxRGKE7Izt+DSiwBJMUSm71rguMYhQKrBygOIRStf4TiFFRBvbRGKiQLWP29yRSHKBTtfdBmHs0BUpgvtgF4yRFR+NUKi0XZcYjCeCG2smkzLAHkbRBmP0/Uk26O5YnUActBp1GsAI+S5nRJJJal5K1aAMrq0d6Tm9uI6zjyf75dAe6tx/SsWeD//o2/Ab6IH3/h25pOAAAAAElFTkSuQmCC');
                        }
                    }
                });
            },
            queryParams: function (params) {
                let field_search = $('#form-search').serializeArray();
                $.each(field_search, function (i, item) {
                    if (params[item.name]) {
                        params[item.name] += ';' + item.value;
                    }
                    else {
                        params[item.name] = item.value;
                    }

                });
                return params;
            }
        });
        $('#form-search').on('submit', function (event) {
            if (event.isDefaultPrevented()) {
                return false;
            }
            event.preventDefault();
            $table_question.bootstrapTable('refresh',{pageNumber: 1});
            // $('#modalFilter').modal('hide');
            return false;
        });
        function index_formatter(value, row, index) {
            return (index+1);
        }

        function name_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="edit-item" data-id="'+ row.id +'">'+ value + ' (' + row.num_child + ') </a>';
        }

        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function question_formatter(value, row, index) {
            var html = '';
            @can('quiz-question')
                html = '<a href="'+ row.question_url +'"><i class="fa fa-question-circle"></i></a>';
            @endcan
                return html;
        }

        function cate_user_formatter(value, row, index) {
            var html = '';
            @can('quiz-category-question-permission')
                html = '<a href="'+ row.cate_user_url +'"><i class="fa fa-users"></i></a>';
            @endcan
                return html;
        }

        function export_question_formatter(value, row, index) {
            let str = '';
            if (row.export_word) {
                str += '<a href="'+ row.export_word +'" class="btn" title="In Word"><i class="fa fa-file-word"></i></a>';
            }
            if (row.export_excel) {
                str += ' <a href="'+ row.export_excel +'" class="btn" title="In Excel"><i class="fa fa-file-excel"></i></a>';
            }

            return str;
        }

        $('#delete-item-libquestion').prop('disabled', true);

        $('#delete-item-libquestion').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            Swal.fire({
                title: '',
                text: 'Bạn có chắc muốn xóa các mục đã chọn không ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans("laother.agree") }}!',
                cancelButtonText: '{{ trans("labutton.cancel") }}!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('module.quiz.questionlib.remove_category') }}',
                        dataType: 'json',
                        data: {
                            'ids': ids
                        },
                        success: function (result) {
                            if (result.redirect) {
                                window.location = result.redirect;
                                return false;
                            }
                            /*if (result.status === "success") {
                                $table_question.bootstrapTable('refresh');
                                return false;
                            }*/
                            else {
                                show_message(result.message, result.status);
                                return false;
                            }
                        }
                    });
                }
            });

            return false;
        });

        function success_submit(form) {
            $("#app-modal #myModal").modal('hide');
            $table_question.bootstrapTable('refresh');
        }

        $table_question.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', () => {
            $('#delete-item-libquestion').prop('disabled', !$table_question.bootstrapTable('getSelections').length);
        });

        $("div[role=main]").on('click', '.edit-item', function () {
            let item = $(this);
            let oldtext = item.html();
            let id = item.data('id');
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.questionlib.get_modal') }}',
                dataType: 'html',
                data: {
                    'id': id,
                },
            }).done(function(data) {
                item.html(oldtext);
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();
            }).fail(function(data) {
                item.html(oldtext);
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                    return false;
                }
            }
            $.ajax({
                url: '{{ route('module.quiz.questionlib.save_status_category') }}',
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                if (id == 0) {
                    show_message(data.message, data.status);
                }
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });

        };
        $(document).ready(function () {
        })

    </script>
@endsection
