@extends('layouts.backend')

@section('page_title', trans('lasetting.email_configuration'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.setting'),
                'url' => route('backend.setting')
            ],
            [
                'name' => trans('lasetting.email_configuration'),
                'url' => route('backend.config.email.index')
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
<div role="main" id="config_email">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ trans('lasetting.configuration_generals_email') }}</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('backend.config.email.save') }}" method="post" class="form-ajax">
                <div class="form-group">
                    <label>{{ trans('lasetting.company') }}</label>
                    <select class="load-unit" data-level="0" name="email_company" data-placeholder="{{trans('lasetting.company')}}">
                        @if(isset($unit))
                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ trans('lasetting.email_driver') }}</label>
                    <input type="text" class="form-control" name="email_driver" placeholder="smtp" value="{{ get_config('email_driver') }}">
                </div>

                <div class="form-group">
                    <label>{{ trans('lasetting.email_host') }}</label>
                    <input type="text" class="form-control" name="email_host" placeholder="smtp.gmail.com" value="{{ get_config('email_host') }}">
                </div>

                <div class="form-group">
                    <label>{{ trans('lasetting.email_port') }}</label>
                    <input type="text" class="form-control" name="email_port" placeholder="587" value="{{ get_config('email_port') }}">
                </div>
                <div class="form-group">
                    <label>{{ trans('lasetting.send_from') }}</label>
                    <input type="text" class="form-control" name="email_from_name" value="{{ get_config('email_from_name') }}" placeholder="Hệ thống đào tạo">
                </div>
                <div class="form-group">
                    <label>{{ trans('lasetting.address_email_send') }}</label>
                    <input type="text" class="form-control" name="email_address" value="{{ get_config('email_address') }}" placeholder="hello@example.com">
                </div>
                <div class="form-group">
                    <label>{{ trans('lasetting.user_login') }}</label>
                    <input type="text" class="form-control" name="email_user" value="{{ get_config('email_user') }}">
                </div>
                <div class="form-group">
                    <label>{{ trans('lasetting.email_password') }}</label>
                    <input type="password" class="form-control" name="email_password" value="{{ get_config('email_password') }}">
                </div>
                <div class="form-group">
                    <label>{{ trans('lasetting.email_encryption') }}</label>
                    <input type="text" class="form-control" name="email_encryption" placeholder="tls" value="{{ get_config('email_encryption') }}">
                </div>
                @can('config-email-save')
                    <button type="submit" class="btn"><i class="fa fa-save"></i> @lang('labutton.save')</button>
                @endcan
                <a href="{{route('backend.config.email.index')}}" class="btn"><i class="fa fa-reply"></i> @lang('labutton.back')</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ trans('lasetting.test_configuration_email') }}</h5>
        </div>

        <div class="card-body">
            <p class="description">{{ trans('lasetting.save_configuration') }}</p>
            <form action="{{ route('backend.config.email.test') }}" method="post" class="form-ajax">
                <div class="form-group">
                    <label>{{ trans('lasetting.receive_email') }}</label>
                    <input type="text" class="form-control" name="email" placeholder="youmailtest@gmail.com">
                </div>

                <button type="submit" class="btn"><i class="fa fa-save"></i> {{ trans('labutton.send_mail_test') }}</button>
            </form>
        </div>
    </div>
</div>
@stop
