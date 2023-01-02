@extends('layouts.backend')

@section('page_title', trans('lamenu.authorized_unit_manager'))

@section('breadcrumb')
    @php
    $breadcum= [
        [
            'name' => trans('lamenu.authorized_unit_manager'),
            'url' => ''
        ]
    ]
    @endphp
    @include('layouts.backend.menu_breadcum', $breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-6 form-inline">
                @include('authorizedunit::backend.authorizedunit.filter')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    @if(\App\Models\Permission::isUnitManager())
                    <div class="btn-group">
                        <a href="{{ route('module.authorized_unit.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">{{ trans('backend.employee_name') }}</th>
                <th data-field="email" >{{ trans('backend.employee_email') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="unit_name" data-formatter="unit_formatter" data-with="5%">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_parent_name">{{ trans('backend.unit_manager') }}</th>
                <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="7%">{{ trans('latraining.status') }}</th>
                <th data-field="unit_manager" data-align="center" data-formatter="unit_manager_formatter" data-width="5%">Uỷ quyền</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return  row.lastname + ' ' + row.firstname;
        }

        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        function area_formatter(value, row, index) {
            return row.area_name ? row.area_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.area_url+'"> <i class="fa fa-info-circle"></i></a>' : '';
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0:
                    return '<span>{{ trans('backend.inactivity') }}</span>';
                case 1:
                    return '<span>{{ trans('backend.doing') }}</span>';
                case 2:
                    return '<span>{{ trans('backend.probationary') }}</span>';
                case 3:
                    return '<span>{{ trans('backend.pause') }}</span>';
            }
        }

        function unit_manager_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.modal_unit_manager+'"> <i class="fa fa-info-circle"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.authorized_unit.getdata') }}',
            remove_url: '{{ route('module.authorized_unit.remove') }}',
            field_id: 'user_id'
        });
    </script>
@endsection
