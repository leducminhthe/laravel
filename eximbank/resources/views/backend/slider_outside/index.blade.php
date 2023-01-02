@extends('layouts.backend')

@section('page_title', trans('lasetting.extenal_banner'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.extenal_banner'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
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
                        <a href="{{ route('backend.slider_outside.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="image" data-formatter="image_formatter" data-width="50%">{{trans('lasetting.picture')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('lasetting.name') }}</th>
                    <th data-field="status" data-formatter="status_formatter" data-width="5%" data-align="center">{{trans('lasetting.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function image_formatter(value, row, index) {
            return '<img src="'+ row.image_url +'" class="w-50">'
        }

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'" class="a-color">Banner '+ (index + 1) +' </a>';
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
            url: '{{ route('backend.slider_outside.getdata') }}',
            remove_url: '{{ route('backend.slider_outside.remove') }}'
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans("lacore.min_one_course") }}', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('backend.slider_outside.ajax_isopen_publish') }}",
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
