@extends('layouts.backend')

@section('page_title', trans('lamenu.news_list_outside'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.news_list_outside'),
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
                @include('newsoutside::backend.news.filter')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('news-outside-list-create')
                            <a href="{{ route('module.news_outside.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('news-outside-list-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('news-outside-list-status')
                            <button class="btn" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp; {{ trans('labutton.enable') }}
                            </button>
                            <button class="btn" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp; {{ trans('labutton.disable') }}
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter">#</th>
                    <th data-field="check" data-checkbox="true"></th>
                    <th data-field="title" data-formatter="name_formatter">{{ trans('backend.titles') }}</th>
                    <th data-field="category_name" style="width:70px">{{ trans('lamenu.category') }}</th>
                    <th data-field="created_by">{{ trans('backend.writer') }}</th>
                    <th data-field="created_at2">{{ trans('backend.created_at') }}</th>
                    <th data-field="updated_by">{{ trans('backend.edited_by') }}</th>
                    <th data-field="updated_at2">{{ trans('backend.edit_at') }}</th>
                    <th data-field="views">{{ trans('backend.views') }}</th>
                    <th data-field="like_new">{{ trans('lahandle_situations.likes') }}</th>
                    <th data-field="status" data-width="5%" data-align="center" data-formatter="status_formatter">{{ trans('latraining.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.title +'</a>';
        }

        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function created_by_formatter(value, row, index) {
            return row.user_name;
        }
        function updated_by_formatter(value, row, index) {
            return row.user_name;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.news_outside.getdata') }}',
            remove_url: '{{ route('module.news_outside.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.news_outside.ajax_isopen_publish') }}";

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 bài viết', 'error');
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
@endsection
