@extends('layouts.backend')

@section('page_title', trans('latraining.add_new'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.online_course'),
                'url' => route('module.online.management')
            ],
            [
                'name' => $online->name,
                'url' => route('module.online.edit', ['id' => $online->id])
            ],
            [
                'name' => trans('latraining.internal_registration'),
                'url' => route('module.online.register', ['id' => $online->id])
            ],
            [
                'name' => trans('latraining.add_new'),
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
                @include('online::backend.register.filter_create')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @canany(['online-course-register-create', 'online-course-register-edit'])
                        <button type="submit" id="button-register" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.register') }}</button>
                        @endcanany
                        <a href="{{ route('module.online.register', ['id' => $online->id]) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code">{{ trans('latraining.employee_code') }}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('latraining.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name" data-width="20%">{{ trans('latraining.work_unit') }}</th>
                    <th data-field="parent_unit_name" data-width="20%">{{ trans('latraining.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }
        var ajax_get_user = "{{ route('module.online.register.save', ['id' => $online->id]) }}";

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.register.getDataNotRegister', ['id' => $online->id]) }}',
            field_id: 'user_id'
        });
    </script>
    <script type="text/javascript" src="{{ asset('styles/module/online/js/register.js') }}"></script>

@stop
