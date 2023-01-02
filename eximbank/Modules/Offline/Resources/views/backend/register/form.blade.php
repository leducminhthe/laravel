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
                'name' => trans('lamenu.offline_course'),
                'url' => route('module.offline.management')
            ],
            [
                'name' => $offline->name,
                'url' => route('module.offline.edit', ['id' => $course_id])
            ],
            /*[
                'name' => trans('latraining.classroom'),
                'url' => route('module.offline.class', ['id' => $course_id])
            ],*/
            [
                'name' => trans('latraining.register').': '.$class->name,
                'url' => route('module.offline.register', ['id' => $course_id,'class_id'=>$class->id]),
                'drop-menu'=>$classArray
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
<div role="main" class="form_offline_course">
        <div class="row">
            <div class="col-md-6 ">
                @include('offline::backend.register.filter_create')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="submit" id="button-register" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.register') }}</button>
                        <a href="{{ route('module.offline.register', ['id' => $course_id,'class_id'=>$class->id]) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
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
                    <th data-field="unit_name" data-width="20%">{{ trans('latraining.unit') }}</th>
                    <th data-field="join_company">{{ trans('latraining.day_work') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }
        var ajax_get_user = '{{ route('module.offline.register.class.save', ['id' => $course_id,'class_id'=>$class->id]) }}';

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.register.getDataNotRegister', ['id' => $course_id,'class_id'=>$class->id]) }}',
            field_id: 'user_id'
        });
    </script>
    <script type="text/javascript" src="{{ asset('styles/module/online/js/register.js') }}"></script>

@stop
