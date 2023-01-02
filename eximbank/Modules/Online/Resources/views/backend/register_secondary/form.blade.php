@extends('layouts.backend')

@section('page_title', trans('latraining.add_new'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training'),
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
                'name' => trans('latraining.external_enrollment'),
                'url' => route('module.online.register_secondary', ['id' => $online->id])
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
            <div class="col-md-12">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    <div class="w-24">
                        <input type="text" name="search" class="form-control w-100" autocomplete="off" placeholder="{{ trans('latraining.enter_code_name') }}">
                    </div>
                    <div class="w-24">
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @canany(['online-course-register-create', 'online-course-register-edit'])
                        <button type="submit" id="button-register" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.register') }}</button>
                        @endcanany
                        {{-- <a href="{{ route('module.online.register_secondary', ['id' => $online->id]) }}" class="btn
                        btn-warning"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a> --}}
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
                    <th data-field="name">{{ trans('latraining.employee_name') }}</th>
                    <th data-field="email">{{ trans('latraining.email') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var ajax_get_user = "{{ route('module.online.register_secondary.save', ['id' => $online->id]) }}";

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.register_secondary.getDataNotRegister', ['id' => $online->id]) }}',
        });
    </script>
    <script type="text/javascript" src="{{ asset('styles/module/online/js/register.js') }}"></script>

@stop
