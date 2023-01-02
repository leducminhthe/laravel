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
                'name' => trans('backend.role'). ' '. $role->name,
                'url' => route('backend.roles.user.assign_role', ['role' => $role->id])
            ],
            [
                'name' => trans('backend.list_employee'),
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
                        <button type="submit" id="btnSave" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.save') }}</button>
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
                <th data-field="title">{{ trans('latraining.title') }}</th>
                <th data-field="unit">{{ trans('lamenu.unit') }}</th>
            </tr>
            </thead>
        </table>
        <input type="hidden" name="role" value="{{ $role->id }}">

        <script>
            $(function() {
                $('#btnSave').on('click', function () {
                    var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                    var role = $('input[name=role]').val();
                    var btn = $(this),
                        btn_text = btn.html();
                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
                    if (ids.length <= 0) {
                        show_message('Vui lòng chọn người cần phân quyền', 'error');
                        btn.prop('disabled', false).html(btn_text);
                        return false;
                    }

                    $.ajax({
                        url: '{{ route('backend.roles.user.save', ['role' => $role->id]) }}',
                        type: 'post',
                        dataType:'json',
                        data: {
                            ids: ids,
                            role: role
                        }
                    }).done(function(data) {
                        btn.prop('disabled', false).html(btn_text);
                        show_message(data.message, data.status);
                        if(data.status=='success')
                            $(table.table).bootstrapTable('refresh');
                    }).fail(function(data) {
                        btn.prop('disabled', false).html(btn_text);
                        show_message('Lỗi hệ thống', 'error');
                        return false;
                    });
                });
            })
        </script>
        <script type="text/javascript">
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: '{{ route('backend.role.user.getdata.unassign.role', ['role' => $role->id]) }}',
                field_id: 'user_id'
            });
        </script>
@endsection
