@extends('layouts.backend')

@section('page_title', 'Sales Kit')

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => 'Sales Kit',
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-7 form-inline">
                <form class="form-inline w-100 form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control w-50" placeholder="--Tên Sales Kit--">
                    <div class="w-24">
                        <select name="category_id" class="form-control select2" data-placeholder="{{trans('lamenu.category')}}">
                            <option value=""></option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-5 text-right act-btns">
                <div class="pull-right">
                    @can('libraries-salekit-edit')
                        <div class="btn-group">
                            <button class="btn" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.enable') }}
                            </button>
                            <button class="btn" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.disable') }}
                            </button>
                        </div>
                    @endcan
                    <div class="btn-group">
                        <a class="btn" href="{{ route('module.libraries.salekit.export') }}"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</a>
                        @can('libraries-salekit-create')
                            <a href="{{ route('module.libraries.salekit.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('libraries-salekit-delete')
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
                    <th data-field="index" data-align="center" data-width="2%" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="name" data-width="25%" data-formatter="name_formatter">{{trans('backend.name')}}</th>
                    <th data-field="name_author" data-align="center" data-width="20%">{{ trans('lalibrary.author_name') }}</th>
                    <th data-field="category_name">{{trans('backend.category')}}</th>
                    <th data-field="updated_at2" data-align="center" data-width="10%">{{trans('backend.last_updated')}}</th>
                    <th data-field="user_name" data-align="center" data-width="5%">{{trans('backend.update_by')}}</th>
                    <th data-field="status" data-align="center" data-width="5%" data-formatter="status_formatter">{{trans('latraining.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">

        function index_formatter(value, row, index) {
            return (index+1);
        }

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.libraries.salekit.getdata') }}',
            remove_url: '{{ route('module.libraries.salekit.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.salekit.ajax_isopen_publish') }}";

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
@endsection




