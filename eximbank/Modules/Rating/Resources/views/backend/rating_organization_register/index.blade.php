@extends('layouts.backend')

@section('page_title', 'Quản lý Ghi danh')

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
                @include('rating::backend.rating_organization_register.filter_register')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('module.rating_organization.register.create', ['id' => $rating_levels->id]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]" id="list-user-registed">
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
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_organization.register.getdata', ['id' => $rating_levels->id]) }}',
            remove_url: '{{ route('module.rating_organization.register.remove', ['id' => $rating_levels->id]) }}',
            table: '#list-user-registed',
            form_search: '#form-search-user'
        });
    </script>
@endsection
