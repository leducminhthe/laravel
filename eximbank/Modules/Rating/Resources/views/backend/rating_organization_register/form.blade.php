@extends('layouts.backend')

@section('page_title', trans('labutton.add_new'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.training_evaluation'),
                'url' => ''
            ],
            [
                'name' => 'Mô hình Kirkpatrick',
                'url' => route('module.rating_organization')
            ],
            [
                'name' => $rating_levels->name,
                'url' => route('module.rating_organization.edit', ['id' => $rating_levels->id])
            ],
            [
                'name' => trans('lamenu.user'),
                'url' => route('module.rating_organization.register', ['id' => $rating_levels->id])
            ],
            [
                'name' => trans('labutton.add_new'),
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
                @include('rating::backend.rating_organization_register.filter_create')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="submit" id="button-register" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.register') }}</button>
                        <a href="{{ route('module.rating_organization.register', ['id' => $rating_levels->id]) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-width="5px">{{ trans('backend.employee_code') }}</th>
                    <th data-field="full_name" data-width="20%">{{ trans('backend.employee_name') }}</th>
                    <th data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent_unit_name">{{ trans('backend.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var ajax_get_user = "{{ route('module.rating_organization.register.save', ['id' => $rating_levels->id]) }}";

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_organization.register.getdata_not_register', ['id' => $rating_levels->id]) }}',
            field_id: 'user_id',
        });

        $('#button-register').on('click', function() {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: ajax_get_user,
                dataType: 'json',
                data: {
                    ids: ids
                },
            }).done(function(data) {
                show_message(
                    'Ghi danh thành công',
                    'success'
                );
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {

                show_message(
                    'Lỗi hệ thống',
                    'error'
                );
                return false;
            });
        });
    </script>

@stop
