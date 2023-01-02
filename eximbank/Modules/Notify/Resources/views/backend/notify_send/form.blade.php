@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.notify'),
                'url' => route('module.notify_send')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">
    @if(isset($errors))
        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach
    @endif
    <div class="clear"></div>
    <br>
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('lasetting.info') }}</a></li>
            @if($model->id && userCan(['config-notify-create', 'config-notify-edit']))
                <li class="nav-item"><a href="#object" class="nav-link" data-toggle="tab">{{ trans('lasetting.object') }}</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                @include('notify::backend.notify_send.form.info')
            </div>
            @if($model->id)
                <div id="object" class="tab-pane">
                    @include('notify::backend.notify_send.form.object')
                </div>
            @endif
        </div>
    </div>
</div>
@stop
