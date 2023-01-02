@extends('layouts.backend')

@section('page_title', trans('lasetting.email_signature'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.email_signature'),
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
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('backend.mail_signature.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
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
                    <th data-field="unit_name" data-sortable="true" data-formatter="name_formatter" data-width="20%">{{ trans('lasetting.company') }}</th>
                    <th data-field="content">{{ trans('lasetting.content') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.unit_name +' </a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.mail_signature.getdata') }}',
            remove_url: '{{ route('backend.mail_signature.remove') }}',
            sort_order: 'asc'
        });
    </script>
@endsection
