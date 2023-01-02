@extends('layouts.backend')

@section('page_title', trans('lamenu.quiz_structure'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.quiz_structure'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_exam')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('quiz-template-approved')
                            <button class="btn approved" data-model="el_quiz_templates" data-status="1">
                                <i class="fa fa-check-circle"></i> {{ trans('labutton.approve') }}
                            </button>
                            <button class="btn approved" data-model="el_quiz_templates" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> {{trans('labutton.deny')}}
                            </button>
                        @endcan
                    </div>

                    @can('quiz-template-open')
                        <div class="btn-group">
                            <button class="btn" onclick="changeStatus(0,1)">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.enable') }}
                            </button>
                            <button class="btn" onclick="changeStatus(0,0)">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.disable') }}
                            </button>
                        </div>
                    @endcan

                    <div class="btn-group">
                        @can('quiz-template-create')
                            <a href="{{ route('module.quiz_template.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('quiz-template-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-width="1%" data-checkbox="true"></th>
                    <th data-field="is_open" data-width="3%" data-formatter="is_open_formatter" data-align="center">{{trans('latraining.open_off')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('lacategory.name') }}</th>
                    <th data-field="cate" data-formatter="cate_question">{{ trans('laquestion_lib.question_category') }}</th>
                    <th data-field="quiz_type" data-width="7%" data-align="center">{{trans('backend.quiz_form')}}</th>
                    <th data-field="limit_time" data-width="5%" data-align="center" data-formatter="limit_time_formatter">
                        {{trans('backend.time')}} <br> {{trans('backend.do_quiz')}}
                    </th>
                    <th data-field="regist" data-width="10%" data-align="center" data-formatter="register_formatter">{{trans('backend.action')}}</th>
                    <th data-field="regist" data-align="center" data-formatter="info_formatter" data-width="5%">{{ trans('latraining.info') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a> <br> (' + row.code +')';
        }

        function limit_time_formatter(value, row, index) {
            return row.limit_time + " phút";
        }

        function cate_question(value, row, index) {
            var get_cates = row.get_cates;
            var html = '';
            for (let index = 0; index < get_cates.length; index++) {
                html += '<p>'+ get_cates[index] +'</p>';
            }
            return html;
        }

        function status_formatter(value, row, index) {
            var text_status = '';
            value = parseInt(value);
            switch (value) {
                case 0: text_status = '<span class="text-danger">{{ trans("backend.deny") }}</span>'; break;
                case 1: text_status = '<span class="text-success">{{trans("backend.approve")}}</span>'; break;
                case 2 || null: text_status = '<span class="text-warning">{{ trans("backend.not_approved") }}</span>'; break;
            }

            if(row.approved_step){
                text_status += `<br> <a href="javascript:void(0)" data-id="${row.id}" data-model="el_quiz_templates" class="text-success font-weight-bold load-modal-approved-step">(${row.approved_step})</a>`;
            }

            return text_status;
        }

        function is_open_formatter(value, row, index) {
            var status = row.is_open == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function info_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info+'"><i class="fa fa-user"></i></a>';
        }

        function user_approved_formatter(value, row, index) {
            if (row.user_approved_url){
                return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_approved_url+'"><i class="fa fa-user"></i></a>';
            }
            return '';
        }

        function register_formatter(value, row, index) {
            let str = '';
            if (row.question) {
                str += '<a href="'+ row.question +'" class="btn"><i class="fa fa-question-circle"></i> {{ trans("backend.question") }}</a> ';
            }
            if (row.export_url) {
                str += ' <a href="'+ row.export_url +'" class="btn btn-link"><i class="fa fa-download"></i> {{ trans("backend.print_the_exam") }}</a>';
            }

            return str;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz_template.getdata') }}',
            remove_url: '{{ route('module.quiz_template.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.quiz_template.ajax_is_open') }}";
        var ajax_status = "{{ route('module.quiz_template.ajax_status') }}";

        // BẬT/TẮT
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
                url: ajax_isopen_publish,
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
    </script>
    <script src="{{ asset('styles/module/quiz/js/quiz.js') }}"></script>
@endsection
