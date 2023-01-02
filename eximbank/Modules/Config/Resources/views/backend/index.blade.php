@extends('layouts.backend')

@section('page_title', trans('lasetting.email_configuration'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.email_configuration'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

 @section('content')
    <div role="main">
        @if(isset($notifications))
            @foreach($notifications as $notification)
                @if(@$notification->data['messages'])
                    @foreach($notification->data['messages'] as $message)
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}: {!! $message !!}</div>
                    @endforeach
                @else
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}</div>
                @endif
                @php
                $notification->markAsRead();
                @endphp
            @endforeach
        @endif
        <p></p>
        <div class="row">
                <div class="col-md-3">

                </div>
                <div class="pull-right text-right col-md-9">
                    <div class="btn-group">
                        @can('user-create')
                            <a href="{{ route('backend.config.email.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('user-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="unit_company" data-width="5%">{{ trans('lasetting.company') }}</th>
                <th data-field="driver" data-width="150">{{ trans('lasetting.email_driver') }}</th>
                <th data-field="host" data-formatter="fullname_formatter" data-width="20%">{{ trans('lasetting.email_host') }}</th>
                <th data-field="port" >{{ trans('lasetting.email_port') }}</th>
                <th data-field="from_name" >{{ trans('lasetting.send_from') }}</th>
                <th data-field="address" data-with="5%">{{ trans('lasetting.address_email_send') }}</th>
                <th data-field="user" data-with="5%">{{ trans('lasetting.user_login') }}</th>
                <th data-field="encryption" data-width="10%">{{ trans('lasetting.email_encryption') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.host + '</a>';
        }


        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.config.email.getdata') }}',
            remove_url: '{{ route('backend.config.email.remove') }}',
        });


    </script>
 @endsection
