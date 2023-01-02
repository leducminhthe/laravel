@extends('layouts.backend')

@section('page_title', __('Quản lý vai trò'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('backend.role_management'),
                'url' => route('backend.roles')
            ],
            [
                'name' => trans('backend.role'). ' ' .$role->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="role">
        <div class="row">
            <div class="col-md-6">
                @include('role::user.filter')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn" href="{{ route('backend.roles.user.unassign_role', ['role' => $role->id]) }}"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
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
                    <th data-field="code">{{ trans('backend.code') }}</th>
                    <th data-field="full_name">{{ trans('backend.fullname') }}</th>
                    <th data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name">{{ trans('lamenu.unit') }}</th>
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: '{{ route('backend.role.user.getdata.assign.role', ['role' => $role->id]) }}',
                remove_url: '{{ route('backend.roles.user.delete', ['role' => $role->id]) }}',
                field_id: 'user_id'
            });
        </script>
@endsection
