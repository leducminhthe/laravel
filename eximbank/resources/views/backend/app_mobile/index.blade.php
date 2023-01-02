@extends('layouts.backend')

@section('page_title', trans('lasetting.app_mobile'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.app_mobile'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#android" class="nav-link active" role="tab" data-toggle="tab">Android</a></li>
                <li class="nav-item"><a href="#apple" class="nav-link" role="tab" data-toggle="tab">Apple</a></li>
            </ul>
            <div class="tab-content">
                <div id="android" class="tab-pane active">
                    @include('backend.app_mobile.form.android')
                </div>
                <div id="apple" class="tab-pane">
                    @include('backend.app_mobile.form.apple')
                </div>
            </div>
        </div>
    </div>
@endsection
