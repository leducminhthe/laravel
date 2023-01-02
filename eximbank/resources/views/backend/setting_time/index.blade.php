@extends('layouts.backend')

@section('page_title', trans('lasetting.setting_time'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.setting_time'),
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
                        @can('setting-time-create')
                            <a href="{{ route('backend.setting_time.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('setting-time-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="image" data-formatter="time_formatter" data-width="20%">{{ trans('lasetting.time') }}</th>
                    <th data-field="name" data-formatter="content_formatter">{{ trans('lasetting.content') }}</th>
                    <th data-field="name" data-formatter="object_formatter">{{ trans('lasetting.unit') }}</th>
                    <th data-field="name" data-align="center" data-formatter="edit_formatter">{{ trans('lasetting.edit') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function time_formatter(value, row, index) {
            var html = '';
            $.each(row.time, function (index, value) {
                html += value;
                html += '</br>'
            });
            return html;
        }

        function content_formatter(value, row, index) {
            var html = '';
            $.each(row.value, function (index, value) {
                html += value;
                html += '</br>'
            });
            return html;
        }

        function object_formatter(value, row, index) {
            if (row.object != 'All') {
                let rhtml = '';
                $.each(row.object, function(i, item) {
                    rhtml += '<p><span>'+ item +'</span></p>';
                });
                return rhtml;
            } else {
                return '<p><span>Tất cả đơn vị</span></p>'
            }
        }

        function edit_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'"><i class="fas fa-edit"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.setting_time.getdata') }}',
            remove_url: '{{ route('backend.setting_time.remove') }}'
        });
    </script>
@endsection
