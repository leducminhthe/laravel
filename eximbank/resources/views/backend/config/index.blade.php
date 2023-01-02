@extends('layouts.backend')

@section('page_title', trans('lasetting.generals_setting'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('lamenu.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold"> {{ trans('lasetting.generals_setting') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group config-menu">

                        <a href="javascript:void(0)" class="list-group-item list-group-item-action" data-form="ldap">
                            <i class="fa fa-sign-in"></i> {{ trans('lasetting.general_ldap') }}
                        </a>

                    </div>
                </div>
                <div class="col-md-9" id="form-config">

                </div>
            </div>
        </div>
@endsection

