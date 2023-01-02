@extends('layouts.backend')

@section('page_title', trans('lamenu.news'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.news'),
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
                @include('news::backend.news.filter')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('news-list-create')
                            <a href="{{ route('module.news.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('news-list-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('news-list-status')
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
                    <th data-field="title" data-formatter="name_formatter">{{trans('backend.titles')}}</th>
                    <th data-field="type" data-formatter="type">{{ trans('latraining.type') }}</th>
                    <th data-field="status" data-align="center" data-width="5%" data-formatter="status_formatter">{{trans('latraining.status')}}</th>
                    <th data-field="category_name" style="width:70px">{{ trans('lamenu.category') }}</th>
                    <th data-field="info" data-formatter="info_formatter" data-align="center">{{ trans('latraining.info') }}</th>
                    <th data-field="views">{{ trans('backend.views') }}</th>
                    <th data-field="like_new">{{ trans('lahandle_situations.likes') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function info_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info+'"><i class="fa fa-info-circle"></i></a>';
        }
        function index_formatter(value, row, index) {
            return (index+1);
        }
        function type(value, row, index) {
            if (row.type == 1) {
               return '<span>{{ trans("latraining.post") }}</span>';
            } else if (row.type == 2) {
                return '<span>{{ trans("lamenu.video") }}</span>';
            } else {
                return '<span>{{ trans("latraining.picture") }}</span>';
            }
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

        // function action_formatter(value, row, index) {
        //     return '<i class="fa fa-eye"></i>';
        // }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.news.getdata') }}',
            remove_url: '{{ route('module.news.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.news.ajax_isopen_publish') }}";

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
    <script src="{{ asset('styles/module/news/js/news.js') }}"></script>
@endsection
