@extends('layouts.backend')

@section('page_title', trans('lamenu.indemnify'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.indemnify'),
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
                @include('indemnify::backend.filter')
            </div>
            <div class="col-md-6 text-right">
                <a class="btn" href="javascript:void(0)" id="export-excel">
                    <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                </a>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-sortable="true" data-field="firstname" data-formatter="name_formatter" class="text-nowrap">{{ trans('latraining.user') }}</th>
                <th data-sortable="true" data-field="title_name">@lang('latraining.title')</th>
                <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                <th data-field="parent">{{ trans('backend.unit_manager') }}</th>
                <th data-field="num_course" data-align="center" data-width="5%">@lang('backend.number_committed_keys')</th>
                <th data-field="total_indemnify" data-align="center" data-width="5%">{{ trans('latraining.total_chargeback') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.detail_url +'"> ('+ row.code +') '+ row.lastname +' '+row.firstname+'</a> <br>' + row.email;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.indemnify.getdata') }}',
        });

        $("#export-excel").on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.indemnify.export') }}?'+form_search;
        });
    </script>

@endsection
