@extends('layouts.backend')

@section('page_title', trans('lasetting.mailhistory'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.mailhistory'),
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
                    <input type="text" name="search" class="form-control" placeholder="{{ trans('lasetting.enter_code_name_mail') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    {{--<th data-field="state" data-checkbox="true"></th>--}}
                    <th data-field="code" data-sortable="true" data-width="5%">{{ trans('lasetting.code') }}</th>
                    <th data-field="name" data-sortable="true" data-width="20%">{{ trans('lasetting.email_name') }}</th>
                    <th data-field="content">{{ trans('lasetting.content') }}</th>
                    <th data-field="emails" data-width="15%">{{ trans('lasetting.list_mail_send') }}</th>
                    <th data-field="send_time" data-width="10%">{{ trans('lasetting.time_send_mail') }}</th>
                    <th data-field="status" data-width="5%" data-formatter="status_formatter" data-align="center">{{trans('lasetting.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function status_formatter(value, row, index) {
            switch (value) {
                case '0': return '<span class="text-muted">Chưa gửi</span>';
                case '1': return '<span class="text-success">Đã gửi</span>';
                case '2': return '<span class="text-success">Chưa cấu hình mail server</span>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.mailhistory.getdata') }}',
            sort_order: 'desc'
        });
    </script>
@endsection
