@extends('layouts.backend')

@section('page_title', trans('lasetting.notification_template'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.notification_template'),
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
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="{{trans('lasetting.enter_name_title_code')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                   {{-- <th data-field="state" data-checkbox="true"></th>--}}
                    <th data-field="name" data-sortable="true" data-formatter="name_formatter" data-width="20%">{{ trans('lasetting.name') }}</th>
                    <th data-field="title" data-sortable="true" data-width="25%">{{trans('lasetting.titles')}}</th>
                    <th data-field="content">{{ trans('lasetting.content') }}</th>
                    {{--<th data-field="note" data-width="15%">{{ trans('lasetting.note') }}</th>--}}
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +' </a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.notify.template.getdata') }}',
            sort_order: 'asc'
        });
    </script>
@endsection
